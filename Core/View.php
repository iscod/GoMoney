<?php

namespace GoMoney;

defined('CORE_PATH') OR exit('No direct script access allowed');

class View
{
    /**
     * View constructor.
     * @param string $file_name
     * @param array|NUll $arguments
     */
    public function __construct(string $file_name, array $arguments = NUll)
    {
        $this->output($file_name, $arguments);
    }

//    public function __destruct()
//    {
//        exit(0);
//    }

    /**
     * @param $file_name
     * @param $arguments
     */
    private function output($file_name, $arguments)
    {
        if (file_exists(VIEW_PATH . $file_name . '.php')) {
            extract($arguments, EXTR_PREFIX_SAME);
            require_once VIEW_PATH . $file_name . '.php';
        } else {
            echo print_r($arguments);
        }
        ob_start();
        $content = ob_get_contents();
        ob_end_clean();
        echo $content;
    }
}
