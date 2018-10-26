<?php
/**
 * Created by PhpStorm.
 * User: ning
 * Date: 2018/10/24
 * Time: 下午6:20
 */

namespace App\Console;


use GoMoney\Controller;

class Welcome extends Controller
{
    public function index()
    {
        echo "ok";
        die();
    }

}