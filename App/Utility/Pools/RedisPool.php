<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 18-9-25
 * Time: 下午4:41
 */

namespace App\Utility\Pools;


use EasySwoole\Component\Pool\AbstractPool;
use EasySwoole\EasySwoole\Config;
use Swoole\Coroutine\Redis;

class RedisPool extends AbstractPool
{
    /**
     * 创建redis连接池对象
     * @return bool
     */
    protected function createObject()
    {
        // TODO: Implement createObject() method.
        if (!extension_loaded('redis')) {
            throw new \BadFunctionCallException('not support: redis');
        }
        $conf = Config::getInstance()->getConf('REDIS');
        $redis = new Redis;

        if ($conf['persistent'] != 'false') {
            $redis->pconnect($conf['host'], $conf['port'], intval($conf['timeout']), 'persistent_id_'.$conf['select']);
        } else {
            $redis->connect($conf['host'], $conf['port'], intval($conf['timeout']));
        }

        if ('' !== $conf['password']) {
            $redis->auth($conf['password']);
        }

        if ('0' !== $conf['select']) {
            $redis->select(intval($conf['select']));
        }
        return $redis;
    }
}