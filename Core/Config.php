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
            $filename = ltrim($path, 'DIRECTORY_SEPARATOR') . DIRECTORY_SEPARATOR . $file . '.php';
            if (is_file($filename)) {
                $retData = include $filename;
                if (empty($retData)) {
                    trigger_error(__CLASS__ . ": $filename no return data");
                }
            }
        }
        if (isset($retData)) {
            return $retData;
        } else {
            throw new ErrorException('Not Config File ' . $file . '.php!');
        }
    }
}