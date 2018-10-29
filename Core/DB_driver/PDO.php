<?php

namespace GoMoney\DB_driver;

use GoMoney\ErrorException;

/**
 * Class PDO
 * @package GoMoney\DB_driver
 */
class PDO
{
    private $dbh = null;
    public $database = null;
    public $table = null;
    public $config = null;

    public $bindings = null;

    /**
     * PDO constructor.
     * @param array|null $config
     */
    public function __construct(array $config = null)
    {
        $this->config = $config;
    }

    public function __destruct()
    {
        $this->close();
    }

    /**
     * @param string|null $database
     * @return $this
     */
    public function connect(string $database = null)
    {
        $this->database = $database;
        return $this;
    }

    /**
     * @param string $table
     * @param null $database
     * @return $this
     */
    public function table(string $table = null, $database = null)
    {
        if ($database !== null) {
            $this->connect($database);
        }

        $this->table = $table;
        return $this;
    }

    /**
     * @return null|\PDO
     * @throws ErrorException
     */
    private function getDbh()
    {
        if ($this->dbh instanceof \PDO) {
            return $this->dbh;
        }

        if ($this->database === null || ($this->database && array_key_exists($this->database, $this->config))) {
            $config = $this->config[$this->database] ?? $this->config;
        } else {
            throw new ErrorException('Undefined DB ' . $this->database . ' Config');
        }

        if (empty($config['dsn'])) {
            $config['dsn'] = ($config['driver'] ?? 'mysql') . ':host=' . ($config['host'] ?? 'localhost') . ((!empty($config['port'])) ? (';port=' . $config['port']) : '') . ';dbname=' . ($config['database'] ?? '');
        }

        try {
            $this->dbh = new \PDO($config['dsn'], $config['username'] ?? 'root', $config['password'] ?? '', array(\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8'));
        } catch (\PDOException $e) {
            throw new ErrorException($e->getMessage(), $e->getCode());
        }

        return $this->dbh;
    }

    /**
     * @param $method
     * @param $arguments
     * @return mixed|null
     */
    public function __call($method, $arguments)
    {
        $methods = get_class_methods(get_class($this));
        if (in_array($method, $methods)) {
            return call_user_func_array([$this, $method], $arguments);
        } else {
            return null;
        }

    }


    public function close()
    {
        $this->dbh = null;
    }

    /**
     * @param array $column
     * @param null $value
     * @return int|string
     * @throws ErrorException
     */
    public function insert($column = [], $value = null)
    {
        if (is_array($column)) {
            foreach ($column as $key => $value) {
                $this->bindings[__FUNCTION__][$key] = $value;
            }
        }

        if (func_num_args() == 2) {
            $this->bindings[__FUNCTION__][$column] = $value;
        }

        try {
            $this->dbh = $this->getDbh();
            $this->dbh->beginTransaction();
            $sql = $this->bindSql(__FUNCTION__);
            $data = array_values($this->bindings[__FUNCTION__]);
            $rs = $this->dbh->prepare($sql);
            $rs->execute($data);
            $id = $this->dbh->lastInsertId();
            $this->dbh->commit();
            return $id;
        } catch (\PDOException $e) {
            $this->dbh->rollBack();
        }

        return 0;
    }

    /**
     * @param array $data
     */
    public function update(array $data = [])
    {

    }

    /**
     * @param $column
     * @param string|null $operator
     * @param string|null $value
     * @return $this|PDO
     */
    public function where($column, string $operator = null, string $value = null)
    {
        if (is_array($column)) {
            return $this->addArrayOfArgs($column, __FUNCTION__);
        }

        if (func_num_args() == 2) {
            list($value, $operator) = [$operator, '='];
        }

        $this->bindings[__FUNCTION__][$column . $operator] = $value;

        return $this;
    }

    public function limit(int $limit = 1)
    {
        $this->bindings['limit'] = $limit;
        return $this;
    }

    public function addArrayOfArgs($column = [], $type = 'where')
    {
        if (is_array($column)) {
            foreach ($column as $key => $value) {
                if (is_array($value)) {
                    call_user_func_array([$this, $type], $value);
                } else {
                    $this->$type($key, $value);
                }
            }
        }

        return $this;
    }

    /**
     * bind sql
     *
     * @param string $type
     * @return string
     */
    private function bindSql($type = 'where')
    {
        $column = [];
        $dml = '';

        switch ($type) {
            case 'where':
                $dml = 'SELECT * FROM';
                $column[] = "WHERE";
                $column[] = implode('? and ', array_keys($this->bindings[$type])) . '?';
                break;
            case 'insert':
                $dml = 'INSERT INTO';
                $column[] = '(`' . implode('`, `', array_keys($this->bindings[$type])) . '`)';
                $column[] = 'VALUES (' . substr(str_repeat('?,', count($this->bindings[$type])), 0, -1) . ')';
                break;
            default:
                break;
        }

        if (isset($this->bindings['limit']) && $this->bindings['limit']) {
            $column[] = ' LIMIT ' . (int)$this->bindings['limit'];
        }

        $sql = $dml . ' `' . $this->table . '` ' . implode(' ', $column);
        return $sql;
    }

    /**
     * @param string $type
     * @return array
     * @throws ErrorException
     */
    private function execute($type = 'where')
    {
        $this->dbh = $this->getDbh();
        $sql = $this->bindSql($type);
        $rs = $this->dbh->prepare($sql);
        $data = array_values($this->bindings[$type]);
        $rs->execute($data);
        $return = $rs->fetchAll();
        return $return;
    }

    /**
     * exec for sql
     * @param $sql
     * @return int
     * @throws ErrorException
     */
    public function exec($sql){
        $this->dbh = $this->getDbh();
        return $this->dbh->exec($sql);
    }

    /**
     * @return array
     * @throws ErrorException
     */
    public function get()
    {
        return $this->execute();
    }

    /**
     * get one limit for fetch
     * @return mixed
     * @throws ErrorException
     */
    public function getOne()
    {
        $this->limit(1);
        $return = $this->execute();
        return array_pop($return);
    }

    /**
     * @param $statement
     * @param int $mode
     * @param null $arg3
     * @param array $ctorargs
     * @return array
     * @throws ErrorException
     */
    public function query($statement, $mode = \PDO::ATTR_DEFAULT_FETCH_MODE, $arg3 = null, array $ctorargs = [])
    {
        $this->dbh = $this->getDbh();
        $query = $this->dbh->query($statement, $mode, $arg3, $ctorargs);
        return $query->fetchAll();
    }
}