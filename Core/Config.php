<?php
/**
 * Created by PhpStorm.
 * User: ning
 * Date: 2018/10/25
 * Time: 上午11:54
 */

namespace GoMoney;


class Config
{
    public static function load($file)
    {
        foreach ([CORE_PATH . CONFIG_PATH, CONFIG_PATH] as $path) {
            $ini = ltrim($path, 'DIRECTORY_SEPARATOR') . DIRECTORY_SEPARATOR . $file . '.ini';
            if (is_file($ini)) {
                $pare = parse_ini_file($ini, TRUE);

            }
        }

        if (isset($pare)) {
            return $pare;
        } else {
            throw new ErrorException('Not Config File ' . $file . '.php!');
        }
    }
}