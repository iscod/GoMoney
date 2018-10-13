<?php

defined('CORE_PATH') OR exit('No direct script access allowed');

if (!function_exists('is_cli')) {
    /**
     * @return bool
     */
    function is_cli()
    {
        return (strtolower(PHP_SAPI) === 'cli' OR strtolower(php_sapi_name()) === 'cli');
    }
}

if (!function_exists('_error_handle')) {
    /**
     * @param int $type
     * @param string $message
     * @param string $file
     * @param int $line
     */
    function _error_handler(int $type, string $message, string $file = '', int $line = 0)
    {
        GoMoney\ErrorException::show_error($type, $message, $file, $line);
    }
}

if (!function_exists('_shutdown_handler')) {
    function _shutdown_handler()
    {
        $error_last = error_get_last();
        if (isset($error_last)) {
            _error_handler($error_last['type'], $error_last['message'], $error_last['file'], $error_last['line']);
        }
    }
}


if (!function_exists('gm_file_put_contents')) {
    /**
     * @param string $file_path
     * @param $data
     * @param int $flags
     * @return bool|int
     */
    function gm_file_put_contents(string $file_path, $data, $flags = FILE_APPEND)
    {
        if (is_file($file_path)) {
            return file_put_contents($file_path, $data, $flags);
        }

        $parts = explode('/', $file_path);
        $file = array_pop($parts);
        $dir = '';
        foreach ($parts as $part) {
            if (!is_dir($dir .= "/$part")) mkdir($dir);
        }

        return file_put_contents("$dir/$file", $data, $flags);
    }
}