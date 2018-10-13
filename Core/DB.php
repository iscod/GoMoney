<?php

namespace GoMoney;

defined('BASEPATH') OR exit('No direct script access allowed');

if (!file_exists($file_path = APPPATH . 'config/' . ENVIRONMENT . '/database.php')
    && !file_exists($file_path = APPPATH . 'config/database.php')) {
    show_error('The configuration file database.php does not exist.');
}

include($file_path);

// Make packages contain database config files,
// given that the controller instance already exists
if (class_exists('CI_Controller', FALSE)) {
    foreach (get_instance()->load->get_package_paths() as $path) {
        if ($path !== APPPATH) {
            if (file_exists($file_path = $path . 'config/' . ENVIRONMENT . '/database.php')) {
                include($file_path);
            } elseif (file_exists($file_path = $path . 'config/database.php')) {
                include($file_path);
            }
        }
    }
}


/**
 * Class DB
 * @package Core\DB
 */
class DB
{
    private $database;
    private $table_name;
    private $dbh;
    private $host;

    function __construct($database)
    {
        $dsn = "mysql:dbname=testdb;host=" . $this->host;
//        $this->dbh = parent::__construct($dsn, $username, $password, $driver_options);
    }

    function __destruct()
    {
        $this->dbh->close();
    }

    function __call($func, $arrgs)
    {
        return call_user_func_array(array(&$this->dbh, $func), $arrgs);
    }

    static function table($table_name)
    {
//        self::$table_name = $table_name;
//        $this->table_name = $table_name;
    }
}