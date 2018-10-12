<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 18-10-12
 * Time: 下午1:59
 */

namespace App\HttpController;


use App\Utility\Pools\RedisPool;
use EasySwoole\Component\Pool\PoolManager;
use EasySwoole\Http\AbstractInterface\Controller;
use Swoole\Coroutine\Redis;

class Pool extends Controller
{

    private $redis;

    function onRequest(?string $action): ?bool
    {
        $this->redis = PoolManager::getInstance()->getPool(RedisPool::class)->getObj();
        if(!$this->redis instanceof Redis){
            throw new \Exception('Redis Pool is Empty');
        }
        return true;
    }

    public function gc() {
        parent::gc();
        /*
        * 回收连接池对象
        */
        if(!$this->redis instanceof Pool){
            PoolManager::getInstance()->getPool(RedisPool::class)->recycleObj($this->redis);
        }
    }


    function index()
    {
        // TODO: Implement index() method.
        $name = $this->redis->get('name');
        $this->response()->write($name);
    }
}