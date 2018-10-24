<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 18-10-17
 * Time: 下午3:39
 */

namespace App\Model;


use EasySwoole\Trace\AbstractInterface\LoggerWriterInterface;

class Handler implements LoggerWriterInterface
{

    function writeLog($obj, $logCategory, $timeStamp)
    {
        // TODO: Implement writeLog() method.

    }
}