<?php
/**
 * Created by PhpStorm.
 * User: yf
 * Date: 2018/5/28
 * Time: 下午6:33
 */

namespace EasySwoole\EasySwoole;

use App\Actor\RoomActor;
use App\Process\Inotify;
use App\Queue\Queue;
use App\Task\Jd;
use App\Task\JdClient;
use App\Task\JdGood;
use App\Task\JdGoodClient;
use App\Utility\Pool\MysqlPool;
use App\Utility\Pool\RedisPool;
use EasySwoole\Actor\Actor;
use EasySwoole\Component\Pool\PoolManager;
use EasySwoole\Component\Timer;
use EasySwoole\EasySwoole\Swoole\EventRegister;
use EasySwoole\EasySwoole\AbstractInterface\Event;
use EasySwoole\Http\Request;
use EasySwoole\Http\Response;

class EasySwooleEvent implements Event
{

    public static function initialize()
    {
        // TODO: Implement initialize() method.
        date_default_timezone_set('Asia/Shanghai');

        require_once EASYSWOOLE_ROOT."/App/Utility/simple_dom_html.php";

        // 注入redis池和mysql池

        PoolManager::getInstance()->register(RedisPool::class, Config::getInstance()->getConf('REDIS.POOL_MAX_NUM'));
        PoolManager::getInstance()->register(MysqlPool::class, Config::getInstance()->getConf('MYSQL.POOL_MAX_NUM'));
    }

    public static function mainServerCreate(EventRegister $register)
    {
        // TODO: Implement mainServerCreate() method.
        // 清空数据
        $conf = Config::getInstance()->getConf('REDIS');
        $redis = new \Redis();
        $redis->connect($conf['host'], $conf['port']);
        if (!empty($conf['auth'])) {
            $redis->auth($conf['auth']);
        }
        $redis->del(Queue::$queue);

        // TODO: Implement mainServerCreate() method.
        $register->add(EventRegister::onWorkerStart, function (\swoole_server $server, $workerId) {
            if ($workerId === 0) {
                // 定时器循环10次
//                $count = 0;
//                $timer = Timer::getInstance()->loop(10 * 1000, function () use(&$timer, &$count) {
//                    if ($count == 10) {
//                        unset($count);
//                        Timer::getInstance()->clear($timer);
//                    } else {
//                        echo 'test'.PHP_EOL;
//                        $count = $count + 1;
//                        echo 'count'.$count.PHP_EOL;
//                    }
//                });
                \Co::create(function (){
                    $redis = PoolManager::getInstance()->getPool(RedisPool::class)->getObj();
                    if ($redis) {
//                        $jd = new Jd($redis);     // curl模式
//                        $jd->run();
                        $client = new JdClient($redis);     // 协程客户端
                        $client->run();
                    } else {
                        echo 'redis pool is empty'.PHP_EOL;
                    }
                });
                Timer::getInstance()->after(5 * 1000, function () {
                    // 定时任务
                    $timer = Timer::getInstance()->loop(1 * 1000, function () use (&$timer) {
                        \Co::create(function () use (&$timer){
                            $db = PoolManager::getInstance()->getPool(MysqlPool::class)->getObj();
                            $redis = PoolManager::getInstance()->getPool(RedisPool::class)->getObj();
                            if ($db && $redis) {
                                $queue = new Queue($redis);
                                // $goodTask = new JdGood($db);        // curl模式
                                $goodTask = new JdGoodClient($db);        // 协程客户端
                                $task = $queue->rPop();
                                if($task) {
                                    echo 'task-----'.$task.PHP_EOL;
                                    $goodTask->handle($task);
                                } else {
                                    if ($timer) {
                                        Timer::getInstance()->clear($timer);
                                    }
                                    echo 'end-----'.PHP_EOL;
                                }
                                PoolManager::getInstance()->getPool(MysqlPool::class)->recycleObj($db);
                                PoolManager::getInstance()->getPool(RedisPool::class)->recycleObj($redis);
                            } else {
                                if ($redis) {
                                    echo 'mysql pool is empty'.PHP_EOL;
                                    PoolManager::getInstance()->getPool(MysqlPool::class)->recycleObj($db);
                                } else {
                                    echo 'redis pool is empty'.PHP_EOL;
                                    PoolManager::getInstance()->getPool(RedisPool::class)->recycleObj($redis);
                                }
                            }
                        });
                    });
                });
            }
        });

        // 开启热重启进程
        ServerManager::getInstance()->getSwooleServer()->addProcess((new Inotify('autoReload', ['disableInotify' => false]))->getProcess());

        Actor::getInstance()->register(RoomActor::class)->setActorProcessNum(3)//设置保存actor的进程数目
        ->setActorName('RoomActor')//设置Actor的名称，注意一定要注册，且不能重复
        ->setMaxActorNum(1000);//设置当前actor中最大的actor数目
    }

    public static function onRequest(Request $request, Response $response): bool
    {
        // TODO: Implement onRequest() method.
        $response->withHeader('Content-type','application/json;charset=utf-8');
        return true;
    }

    public static function afterRequest(Request $request, Response $response): void
    {
        // TODO: Implement afterAction() method.
    }
}

