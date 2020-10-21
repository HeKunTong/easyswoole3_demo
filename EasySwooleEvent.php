<?php


namespace EasySwoole\EasySwoole;


use App\Crontab\TimerTask;
use App\Queue\Queue;
use App\Task\JdClient;
use App\Task\JdGoodClient;
use App\Template;
use EasySwoole\Component\Timer;
use EasySwoole\EasySwoole\AbstractInterface\Event;
use EasySwoole\EasySwoole\Crontab\Crontab;
use EasySwoole\EasySwoole\Swoole\EventRegister;
use EasySwoole\ORM\Db\Connection;
use EasySwoole\ORM\DbManager;
use EasySwoole\Redis\Config\RedisConfig;
use EasySwoole\RedisPool\Redis;
use EasySwoole\Template\Render;

class EasySwooleEvent implements Event
{
    public static function initialize()
    {
        // mysql 连接注册
        $mysqlConfig = Config::getInstance()->getConf('MYSQL');
        DbManager::getInstance()->addConnection(new Connection(new \EasySwoole\ORM\Db\Config($mysqlConfig)));

        // redis 连接注册
        $redisConfig = Config::getInstance()->getConf('REDIS');
        Redis::getInstance()->register('redis', new RedisConfig($redisConfig));
    }

    public static function mainServerCreate(EventRegister $register)
    {
        // 清空数据
//        $conf = Config::getInstance()->getConf('REDIS');
//        $redis = new \Redis();
//        $redis->connect($conf['host'], $conf['port']);
//        if (!empty($conf['auth'])) {
//            $redis->auth($conf['auth']);
//        }
//        $redis->del(Queue::$queue);

        $register->add(EventRegister::onWorkerStart, function (\swoole_server $server, $workerId) {
            if ($workerId === 0) {
//                \Co::create(function (){
//                    $client = new JdClient();     // 协程客户端
//                    $client->run();
//                });
//                Timer::getInstance()->after(5 * 1000, function () {
//                    // 定时任务
//                    $timer = Timer::getInstance()->loop(3 * 1000, function () use (&$timer) {
//                        \Co::create(function () use (&$timer){
//                            $goodTask = new JdGoodClient(); // 协程客户端
//                            $goodTask->run();
//                        });
//                    });
//                });
            }
        });

        Render::getInstance()->getConfig()->setRender(new Template());
        Render::getInstance()->attachServer(ServerManager::getInstance()->getSwooleServer());

        // 任务计划
        Crontab::getInstance()->addTask(TimerTask::class);

        // 开启热重启进程
        // ServerManager::getInstance()->getSwooleServer()->addProcess((new Inotify('autoReload', ['disableInotify' => false]))->getProcess());
    }
}