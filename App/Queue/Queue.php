<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 18-10-10
 * Time: 下午1:53
 */

namespace App\Queue;

use App\Utility\Pool\RedisObject;

class Queue
{
    private $redis;
    static public $queue = 'queue';

    function __construct(RedisObject $redis)
    {
        $this->redis = $redis;
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