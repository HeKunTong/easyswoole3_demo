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
use EasySwoole\RedisPool\Redis;

class Pool extends Controller
{

    function index()
    {
        // TODO: Implement index() method.
        $redis = Redis::defer('redis');
        $redis->set('name', 'blank');
        $name = $redis->get('name');
        $this->response()->write($name);
    }
}