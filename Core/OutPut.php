<?php

namespace GoMoney;

class OutPut
{
    public static function returnSuccess($data)
    {

    }

    public static function returnView($file_name, $arguments)
    {
        extract($arguments, EXTR_PREFIX_SAME);
        ob_start();
        require_once trim(VIEW_PATH, DIRECTORY_SEPARATOR) . trim($file_name, DIRECTORY_SEPARATOR) . '.php';
        $content = ob_get_contents();
        ob_end_clean();
        echo $content;
        exit(0);
    }
}
