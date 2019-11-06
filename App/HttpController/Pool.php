<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 18-10-12
 * Time: 下午1:59
 */

namespace App\HttpController;

use EasySwoole\Http\AbstractInterface\Controller;
use EasySwoole\Pool\Manager;

class Pool extends Controller
{

    function index()
    {
        // TODO: Implement index() method.
        $redis = Manager::getInstance()->get('redis')->defer();
        $redis->set('name', 'blank');
        $name = $redis->get('name');
        $this->response()->write($name);
    }
}