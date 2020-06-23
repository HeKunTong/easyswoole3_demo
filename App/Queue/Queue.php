<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 18-10-10
 * Time: 下午1:53
 */

namespace App\Queue;

use EasySwoole\Redis\Redis;

class Queue
{
    private $redis;
    static public $queue = 'queue';

    function __construct(Redis $redis)
    {
        $this->redis = $redis;
    }

    function rPop()
    {
        return $this->redis->rPop(self::$queue);
    }

    function lPush($data)
    {
        return $this->redis->lpush(self::$queue, $data);
    }

}