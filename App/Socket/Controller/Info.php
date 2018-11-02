<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 18-9-25
 * Time: 下午2:21
 */

namespace App\Socket\Controller;



use App\Socket\AbstractInterface\Controller;

class Info extends Controller
{

    function index()
    {
        $this->response()->addResult('time',time());
    }
}