<?php

namespace GoMoney;

/**
 *
 */
class PDO extends \PDO
{

    private $dbh = NULL;
    private $config = NULL;
    public $table_name = '';

    /**
     * PDO constructor.
     * @param string|NULL $database
     * @throws ErrorException
     */
    public function __construct(string $database = NULL)
    {
        if (!$this->dbh) {
            $config = Config::load('database');

            if ($database === NULL || ($database && array_key_exists($database, $config))) {
                $config = $database ? $config[$database] : $config;
            } else {
                throw new ErrorException('Undefined DB ' . $database . ' Config');
            }

            if (empty($config['dsn'])) {
                $config['dsn'] = $config['driver'] . ':host=' . $config['host'] . ((!empty($config['port'])) ? (';port=' . $config['port']) : '') . ';dbname=' . $config['database'];
            }

            try {
                $this->dbh = new \PDO($config['dsn'], $config['username'], $config['password'], array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8', PDO::ATTR_PERSISTENT => true));

            } catch (\PDOException $e) {
                throw new ErrorException($e->getMessage(), $e->getCode());
            }
        }
    }

    public function __destruct()
    {
        $this->dbh = null;
    }


///    public static function __callStatic($method, $arguments)
////    {
////        call_user_func_array(array(self::connect(), $method), $arguments)
////    }

    static function connect($db = NULL)
    {
        try {
            return new self($db);
        } catch (\PDOException $e) {
            throw new ErrorException($e->getCode(), $e->getMessage());
        }
    }

    public function __call($method, $arguments)
    {
        $methods = get_class_methods(get_class($this));
        if (in_array($method, $methods)) {
            return call_user_func_array([$this, $method], $arguments);
        }
    }


    public function table(string $table_name = '')
    {
        $this->table_name = $table_name;
        return $table_name;
    }

    private function _getParamMark($data)
    {
        return ":" . implode(", :", array_keys($data)) . "";
    }

    private function _getColumn($data)
    {
        return "`" . implode("`, `", array_keys($data)) . "`";
    }

    private function _getExecParam($data)
    {
        $keys = ':' . implode(',:', array_keys($data));
        $param_key = explode(',', $keys);
        return array_combine($param_key, array_values($data));
    }

    public function close()
    {
        $this->dbh = null;
    }

    /**
     * @param array $data
     * @return bool|string
     */
    public function insert(array $data = [])
    {
        if (empty($data)) return FALSE;
        $keys = ':' . implode(',:', array_keys($data));
        $param_key = explode(',', $keys);
        $column = "`" . implode("`, `", array_keys($data)) . "`";
        $param_mark = ":" . implode(", :", array_keys($data)) . "";

        $this->table_name = "test";
        $data = array_combine($param_key, array_values($data));
        $sql = 'INSERT INTO ' . $this->table_name . ' (' . $column . ')' . ' VALUES ' . '(' . $param_mark . ')';
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
    }

    /**
     * @param string $statement
     * @param int $mode
     * @param null $arg3
     * @param array $ctorargs
     * @return array|\PDOStatement
     */
    public function query($statement, $mode = \PDO::ATTR_DEFAULT_FETCH_MODE, $arg3 = null, array $ctorargs = array())
    {
        $query = $this->dbh->query($statement);
        return $query->fetchAll();
    }
}