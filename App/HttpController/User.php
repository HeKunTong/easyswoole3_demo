<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 18-10-12
 * Time: 上午11:07
 */

namespace App\HttpController;


use EasySwoole\Http\AbstractInterface\REST;
use EasySwoole\Spl\SplString;
use EasySwoole\Validate\Validate;

class User extends REST
{
    public function GETInfo()
    {
        $this->response()->write('info.....');
    }

    function GETValid()
    {
        $params = [
            'name' => 'blank',
            'age' => 25
        ];

        $valid = new Validate();

        $valid->addColumn('name', '姓名必填');
        $valid->validate($params);
    }
}