<?php
/**
 * Created by PhpStorm.
 * User: yf
 * Date: 2018/5/28
 * Time: 下午6:33
 */

namespace EasySwoole\EasySwoole;

use App\Process\Inotify;
use App\Queue\Queue;
use App\Task\JdClient;
use App\Task\JdGoodClient;
use App\Template;
use App\Utility\Pool\MysqlPool;
use App\Utility\Pool\RedisPool;
use EasySwoole\Component\Timer;
use EasySwoole\EasySwoole\Swoole\EventRegister;
use EasySwoole\EasySwoole\AbstractInterface\Event;
use EasySwoole\Http\Request;
use EasySwoole\Http\Response;
use EasySwoole\Pool\Manager;
use EasySwoole\Template\Render;

class EasySwooleEvent implements Event
{

    public static function initialize()
    {
        // TODO: Implement initialize() method.
        date_default_timezone_set('Asia/Shanghai');

        // 注入redis池和mysql池

        Manager::getInstance()->register(new RedisPool(new \EasySwoole\Pool\Config()), 'redis');
        Manager::getInstance()->register(new MysqlPool(new \EasySwoole\Pool\Config()), 'mysql');
    }

    public static function mainServerCreate(EventRegister $register)
    {
        // TODO: Implement mainServerCreate() method.
        // 清空数据
//        $conf = Config::getInstance()->getConf('REDIS');
//        $redis = new \Redis();
//        $redis->connect($conf['host'], $conf['port']);
//        if (!empty($conf['auth'])) {
//            $redis->auth($conf['auth']);
//        }
//        $redis->del(Queue::$queue);
//
//        $register->add(EventRegister::onWorkerStart, function (\swoole_server $server, $workerId) {
//            if ($workerId === 0) {
//                \Co::create(function (){
//                    $client = new JdClient();     // 协程客户端
//                    $client->run();
//                });
//                Timer::getInstance()->after(5 * 1000, function () {
//                    // 定时任务
//                    $timer = Timer::getInstance()->loop(1 * 1000, function () use (&$timer) {
//                        \Co::create(function () use (&$timer){
//                            $goodTask = new JdGoodClient(); // 协程客户端
//                            $res = $goodTask->run();
//                            if (!$res) {
//                                if ($timer) {
//                                    Timer::getInstance()->clear($timer);
//                                }
//                                echo 'end-----'.PHP_EOL;
//                            }
//                        });
//                    });
//                });
//            }
//        });

        Render::getInstance()->getConfig()->setRender(new Template());
        Render::getInstance()->attachServer(ServerManager::getInstance()->getSwooleServer());

        // 开启热重启进程
        // ServerManager::getInstance()->getSwooleServer()->addProcess((new Inotify('autoReload', ['disableInotify' => false]))->getProcess());

    }

    public static function onRequest(Request $request, Response $response): bool
    {
        // TODO: Implement onRequest() method.
        return true;
    }

    public static function afterRequest(Request $request, Response $response): void
    {
        // TODO: Implement afterAction() method.
    }
}

