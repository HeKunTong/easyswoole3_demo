<?php


namespace App\Crontab;


use EasySwoole\EasySwoole\Crontab\AbstractCronTask;

class TimerTask extends AbstractCronTask
{

    public static function getRule(): string
    {
        // TODO: Implement getRule() method.
        // 每隔1分钟执行一次
        return '*/1 * * * *';
    }

    public static function getTaskName(): string
    {
        // TODO: Implement getTaskName() method.
        return 'timer';
    }

    function run(int $taskId, int $workerIndex)
    {
        // TODO: Implement run() method.
        echo('timer---------'.date('Y-m-d H:i:s').PHP_EOL);
    }

    function onException(\Throwable $throwable, int $taskId, int $workerIndex)
    {
        // TODO: Implement onException() method.
        echo('exception---------'.date('Y-m-d H:i:s').PHP_EOL);
    }
}