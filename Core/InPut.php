<?php

namespace GoMoney;

class InPut
{

    public function __construct()
    {

    }

    /**
     * @param string|NULL $index
     * @param bool $xss_clean
     * @return mixed
     */
    public static function post(string $index = NULL, $xss_clean = FALSE)
    {

        return $_POST[$index] ?? $_POST;
    }

    /**
     * @param String|NULL $index
     * @param bool $xss_clean
     * @return string
     */
    public static function get(String $index = NULL, $xss_clean = FALSE)
    {
        if ($index === NULL) {
            return $_GET;
        } else {
            return $_GET[$index] ?? '';
        }

    }

    public static function cookie(string $index = NULL) {
        if ($index === NULL) {
            return $_COOKIE;
        } else {
            return $_COOKIE[$index] ?? '';
        }

    }

    public static function header(string $index = NULL) {
        if ($index === NULL) {
            return $_SERVER;
        } else {
            return $_SERVER[strtoupper($index)] ?? '';
        }
    }

    public static function url(){
//        return rtrim(preg_replace('/\?.*/', '', $this->getUri()), '/');
    }

    public static function host(){
        return self::header('HTTP_HOST');
    }

    public static function port(){
        return self::header('SERVER_PORT') ?? '80';
    }

    public static function argv(){
        return $_SERVER['argv'];
    }
}

