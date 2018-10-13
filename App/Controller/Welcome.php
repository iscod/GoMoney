<?php
/**
 * Welcome Controller
 */

namespace App\Controller;

use GoMoney\Controller;

class Welcome extends Controller
{
    public function index()
    {
        $this->view('welcome');
    }
}

