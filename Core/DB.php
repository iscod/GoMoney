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
    protected static $instance = null;

    private static function getConfig()
    {
        return Config::load(strtolower(__CLASS__));
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

        if (count($arguments) == 0) {
            return $instance->$name();
        } else {
            return call_user_func_array([$instance, $name], $arguments);
        }
    }
}