<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 18-10-12
 * Time: 上午11:07
 */

namespace App\HttpController;


use EasySwoole\Http\AbstractInterface\REST;

class User extends REST
{
    public function GETInfo()
    {
        $this->response()->write('info.....');
    }
}