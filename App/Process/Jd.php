<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 18-10-10
 * Time: 下午3:32
 */

namespace App\Process;


use App\Queue\JdQueue;
use App\Task\JdGood;
use EasySwoole\EasySwoole\Swoole\Process\AbstractProcess;
use Swoole\Process;

class Jd extends AbstractProcess
{
    public function run(Process $process)
    {
        // TODO: Implement run() method.
        $queue = new JdQueue();
        $goodTask = new JdGood();
        $this->addTick(1000, function() use ($queue,$goodTask) {
            \Co::create(function ()use ($queue,$goodTask){
                $task = $queue->lPop();
                if($task) {
                    echo '======'.$task.'======'.PHP_EOL;
                    $goodTask->handle($task);
                } else {
                    echo 'end-----'.PHP_EOL;
                }
            });
        });
    }

    public function onShutDown()
    {
        // TODO: Implement onShutDown() method.
    }

    public function onReceive(string $str)
    {
        // TODO: Implement onReceive() method.
    }
}