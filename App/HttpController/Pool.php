<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 18-10-12
 * Time: 下午1:59
 */

namespace App\HttpController;


use App\Utility\Pool\RedisPool;
use EasySwoole\Http\AbstractInterface\Controller;

class Pool extends Controller
{

    function index()
    {
        // TODO: Implement index() method.
        $redis = RedisPool::defer();
        $redis->set('name', 'blank');
        $name = $redis->get('name');
        $this->response()->write($name);
    }
}