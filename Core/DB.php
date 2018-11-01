<?php
/**
 * Created by PhpStorm.
 * User: ning
 * Date: 2018/11/1
 * Time: 下午5:33
 */

namespace GoMoney;

use GoMoney\DB_driver\DBX;
use GoMoney\DB_driver\PDO;

/**
 * Class DB
 * @package GoMoney
 * @method static PDO table(string $name)
 * @method static PDO connect(string $name)
 */
class DB
{
    protected static $instance = NULL;

    private static function getConfig()
    {
        $config = Config::load('database');
        return $config;
    }

    public static function __callStatic($name, $arguments)
    {
        $config = self::getConfig();

        if (in_array($config['driver'], ['mysql', 'mysqli', 'pdo'])) {
            $instance = new PDO(self::getConfig());
        } elseif (in_array($config['driver'], ['dbx'])) {
            $instance = new DBX(self::getConfig());
        } else {
            $instance = new PDO(self::getConfig());
        }

        if (!$instance) {
            throw new ErrorException('A facade root has not been set.');
        }

        switch (count($arguments)) {
            case 0:
                return $instance->$name();
            case 1:
                return $instance->$name($arguments[0]);
            case 2:
                return $instance->$name($arguments[0], $arguments[1]);
            case 3:
                return $instance->$name($arguments[0], $arguments[1], $arguments[2]);
            case 4:
                return $instance->$name($arguments[0], $arguments[1], $arguments[2], $arguments[3]);
            default:
                return call_user_func_array([$instance, $name], $arguments);
        }
    }
}