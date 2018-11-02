<?php
/**
 * Created by PhpStorm.
 * User: ning
 * Date: 2018/11/1
 * Time: 下午7:25
 */

namespace GoMoney\DB_driver;


class DBX
{
    public $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

}