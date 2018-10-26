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
        $data = [
            'title' => 'Welcome to GoMoney!',
        ];

        $this->view('welcome', $data);
    }
}

