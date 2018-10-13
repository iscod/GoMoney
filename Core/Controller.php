<?php
/**
 * Controller for GoMoney
 */

namespace GoMoney;

class Controller
{
    protected function view($file, $param = [])
    {
        $view = new View($file, $param);
    }
}