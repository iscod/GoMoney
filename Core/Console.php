<?php
/**
 * Created by PhpStorm.
 * User: ning
 * Date: 2018/10/24
 * Time: 下午6:14
 */

namespace GoMoney;


class Console
{
    private $Uri;
    private $Route;
    public function __construct()
    {
        $this->Uri = new Uri();
        $this->Route = new Router();
    }

    public function init()
    {
        $argv = InPut::argv();
    }
}