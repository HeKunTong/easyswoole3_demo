<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 19-11-6
 * Time: 上午10:31
 */


require_once "vendor/autoload.php";

\EasySwoole\EasySwoole\Core::getInstance()->initialize();

co::create(function() {
    /**
     * @var $redis \Swoole\Coroutine\Redis
     */
    $redis = \EasySwoole\Pool\Manager::getInstance()->get('redis')->getObj();

    $redis->set('name', 'blank');
    $result = $redis->get('name');
    var_dump($result);

    \EasySwoole\Pool\Manager::getInstance()->get('redis')->recycleObj($redis);
});