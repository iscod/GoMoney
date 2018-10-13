<?php

namespace GoMoney;

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
 *
 */
class PDO extends \PDO
{
    private $database;
    private $table;
    private $dbh;

    function __construct($database)
    {
        $dsn = 'mysql:host= {$host};dbname={$dbname}';
//        $this->dbh = parent::__construct($dsn, $username, $password, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8'));
    }

    function __destruct()
    {
        $this->dbh->close();
    }

    function __call($func, $arrgs)
    {
        return call_user_func_array(array(&$this->dbh, $func), $arrgs);
    }

    static function table($table)
    {
        self::$table = $table;
    }
}