<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 18-9-26
 * Time: 上午11:21
 */

namespace App\Process;


use EasySwoole\EasySwoole\Swoole\Process\AbstractProcess;
use Swoole\Process;

class Test extends AbstractProcess
{

    public function run(Process $process)
    {
        // TODO: Implement run() method.
        $this->addTick(30000, function() {
            echo 'this is '.$this->getProcessName().' process tick'.PHP_EOL;
        });
    }

    public function onShutDown()
    {
        // TODO: Implement onShutDown() method.
    }

    public function onReceive(string $str)
    {
        // TODO: Implement onReceive() method.
        echo 'process rec '.$str.PHP_EOL;
    }
}