<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 18-9-21
 * Time: 上午9:36
 */

namespace App\Task;



use EasySwoole\EasySwoole\Swoole\Task\AbstractAsyncTask;

class Async extends AbstractAsyncTask
{

    function run($taskData, $taskId, $fromWorkerId)
    {
        // TODO: Implement run() method.
        echo $taskData.'   ...'.PHP_EOL;
        return $taskData;
    }

    function finish($result, $task_id)
    {
        // TODO: Implement finish() method.
        echo $result.' end   ...'.PHP_EOL;
    }
}