<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 18-10-12
 * Time: 下午1:59
 */

namespace App\HttpController;


use App\Utility\Pool\RedisObject;
use App\Utility\Pool\RedisPool;
use EasySwoole\Component\Pool\PoolManager;
use EasySwoole\Http\AbstractInterface\Controller;

class Pool extends Controller
{

    private $redis;

    function onRequest(?string $action): ?bool
    {
        $this->redis = PoolManager::getInstance()->getPool(RedisPool::class)->getObj();
        if(!$this->redis instanceof RedisObject){
            throw new \Exception('Redis Pool is Empty');
        }
        return true;
    }

    public function gc() {
        /*
        * 回收连接池对象
        */
        if(!$this->redis instanceof RedisObject){
            PoolManager::getInstance()->getPool(RedisPool::class)->recycleObj($this->redis);
        }
        parent::gc();
    }


    function index()
    {
        // TODO: Implement index() method.
        $name = $this->redis->get('name');
        $this->response()->write($name);
    }
}