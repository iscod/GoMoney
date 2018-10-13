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
    public function post(string $index = NULL, $xss_clean = FALSE)
    {

        return $_POST[$index] ?? $_POST;
    }

}

