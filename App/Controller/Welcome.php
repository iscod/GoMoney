<?php
/**
 * Welcome Controller
 */

namespace App\Controller;

use GoMoney;

class Welcome extends Controller
{
    public function index()
    {
        $data = array(
            'name' => 'ning',
            'addr' => 'shanghai',
        );

        GoMoney::view('error', $data);
//        $this->returnSuccess('success', '200', [1]);
    }

}

