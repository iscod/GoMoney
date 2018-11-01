<?php

namespace GoMoney\DB_driver;

/**
 * Class PDO
 * @package GoMoney
 */
class PDO extends \PDO
{

    private $dbh = NULL;
    public $database = NULL;
    public $table = NULL;
    public $config = NULL;

    /**
     * PDO constructor.
     * @param array|NULL $config
     */
    public function __construct(array $config = NULL)
    {
        $this->config = $config;
    }

    public function __destruct()
    {
        $this->close();
    }

    /**
     * @param null $database
     * @return $this
     * @throws ErrorException
     */
    public function connect($database = NULL)
    {
        $this->database = $database;
        $this->getConnect();
        return $this;
    }

    /**
     * @param string $table
     * @param null $database
     * @return $this
     * @throws ErrorException
     */
    public function table(string $table = '', $database = NULL)
    {
        $this->table = $table;
        if ($database != NULL) $this->database = $database;
        $this->getConnect();
        return $this;
    }

    /**
     * @throws ErrorException
     */
    private function getConnect()
    {
        if (!$this->dbh) {
            $config = $this->config;
            if ($this->database === NULL || ($this->database && array_key_exists($this->database, $config))) {
                $config = $this->database ? $config[$this->database] : $config;
            } else {
                throw new ErrorException('Undefined DB ' . $this->database . ' Config');
            }

            if (empty($config['dsn'])) {
                $config['dsn'] = ($config['driver'] ?? 'mysql') . ':host=' . ($config['host'] ?? 'localhost') . ((!empty($config['port'])) ? (';port=' . $config['port']) : '') . ';dbname=' . ($config['database'] ?? '');
            }

            try {
                $this->dbh = new \PDO($config['dsn'], $config['username'] ?? 'root', $config['password'] ?? '', array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8', PDO::ATTR_PERSISTENT => true));
            } catch (\PDOException $e) {
                throw new ErrorException($e->getMessage(), $e->getCode());
            }
        }
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
            return NULL;
        }

    }

    /**
     * @param string $statement
     * @return int|mixed
     */
    public function exec($statement)
    {
        return $this->dbh->exec($statement);
    }

    public function where(array $data = [])
    {

    }

    public function close()
    {
        $this->dbh = null;
    }

    /**
     * @param array $data
     * @return bool|int
     */
    public function insert(array $data = [])
    {
        if (empty($data)) return FALSE;
        $keys = ':' . implode(',:', array_keys($data));
        $param_key = explode(',', $keys);
        $column = "`" . implode("`, `", array_keys($data)) . "`";
        $param_mark = ":" . implode(", :", array_keys($data)) . "";

        $data = array_combine($param_key, array_values($data));
        $sql = 'INSERT INTO ' . $this->table . ' (' . $column . ')' . ' VALUES ' . '(' . $param_mark . ')';
        try {
            $this->dbh->beginTransaction();
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
     * @param string $statement
     * @param int $mode
     * @param null $arg3
     * @param array $ctorargs
     * @return mixed|\PDOStatement
     */
    public function query($statement, $mode = \PDO::ATTR_DEFAULT_FETCH_MODE, $arg3 = null, array $ctorargs = array())
    {
        $query = $this->dbh->query($statement);
        return $query->fetchAll();
    }
}