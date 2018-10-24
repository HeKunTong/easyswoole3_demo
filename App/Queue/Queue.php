<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 18-10-10
 * Time: 下午1:53
 */

namespace App\Queue;


use App\Utility\Pools\RedisPool;
use EasySwoole\Component\Pool\PoolManager;
use Swoole\Coroutine\Redis;

class Queue
{
    private $redis;
    static public $queue = 'queue';

    function __construct()
    {
        $redis = PoolManager::getInstance()->getPool(RedisPool::class)->getObj();
        if ($redis instanceof Redis) {
            $this->redis = $redis;
        } else {
            throw new \Exception('Redis pool is empty');
        }
    }

    function __destruct()
    {
        // TODO: Implement __destruct() method.
        if ($this->redis instanceof Redis) {
            PoolManager::getInstance()->getPool(RedisPool::class)->recycleObj($this->redis );
        }
    }

    function rPop()
    {
        $rs = $this->redis->rPop(self::$queue);
        return $rs;
    }

    function lPush($data)
    {
        $rs = $this->redis->lpush(self::$queue, $data);
        return $rs;
    }

}