<?php

namespace GoMoney;

defined('CORE_PATH') OR exit('No direct script access allowed');

class ErrorException extends \Exception
{
    /**
     * @var int
     */
    public $ob_level;

    /**
     * @var array
     */
    public static $error_type = [
        E_ERROR => 'E_ERROR', //1
        E_WARNING => 'E_WARNING', //2
        E_PARSE => 'E_PARSE', //4
        E_NOTICE => 'E_NOTICE', //8
        E_CORE_ERROR => 'E_CORE_ERROR',//16
        E_CORE_WARNING => 'E_CORE_WARNING',//32
        E_COMPILE_ERROR => 'E_COMPILE_ERROR',//64
        E_COMPILE_WARNING => 'E_COMPILE_WARNING',//128
        E_USER_ERROR => 'E_USER_ERROR',//256
        E_USER_WARNING => 'E_USER_WARNING',//512
        E_USER_NOTICE => 'E_USER_NOTICE',//1024
        E_STRICT => 'E_STRICT',//2048
        E_RECOVERABLE_ERROR => 'E_RECOVERABLE_ERROR',//4096
        E_DEPRECATED => 'E_DEPRECATED',//8192,
        E_USER_DEPRECATED => 'E_USER_DEPRECATED',//16384
    ];

    /**
     * @param int $type
     * @return mixed
     */
    private static function get_type_name(int $type)
    {
        if (isset(self::$error_type[$type])) {
            return self::$error_type[$type];
        } else {
            return 'E_UNKNOWN_ERROR';
        }
    }

    /**
     *
     */
    public function show_404()
    {

    }

    /**
     *
     */
    public function show_500()
    {

    }

    /**
     * @param int $type
     * @param string $message
     * @param string $file
     * @param int $line
     * @param int $stats_code
     */
    public static function show_error(int $type, string $message, string $file = '', int $line = 0, int $stats_code = 500)
    {
        if (is_cli()) {
            $template = strtolower(PHP_SAPI) . DIRECTORY_SEPARATOR . 'error.php';
        } else {
//            set_status_header($status_code);
            $template = 'html' . DIRECTORY_SEPARATOR . 'error.php';
        }

        ob_start();
        $type_name = self::get_type_name($type);
        include trim(VIEW_PATH, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'error' . DIRECTORY_SEPARATOR . trim($template, DIRECTORY_SEPARATOR);
        $buffer = ob_get_contents();
        ob_end_clean();
        echo $buffer;
        exit(1);
    }
}
