<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 19-1-9
 * Time: 上午10:03
 */

namespace App\Actor;


use EasySwoole\Actor\AbstractActor;
use EasySwoole\Actor\ActorConfig;

class RoomActor extends AbstractActor
{

    static function configure(ActorConfig $actorConfig)
    {
        // TODO: Implement configure() method.
        $actorConfig->setActorName('RoomActor');    //配置actor名称
    }

    /**
     * 当该Actor被创建的时候
     */
    function onStart($arg)
    {
        // TODO: Implement onStart() method.
        var_dump("actor".$this->actorId() . "on start");
        return "on start success\n";
    }

    /**
     * 当发送消息时的回调
     * @author: blank < 1161709455@qq.com >
     */
    function onMessage($msg)
    {
        // TODO: Implement onMessage() method.
        var_dump("actor".$this->actorId()."on message:" . $msg . PHP_EOL);
        return "on message success\n";
    }

    /**
     * 当actor退出时的回调
     * @author: blank < 1161709455@qq.com >
     */
    function onExit($arg)
    {
        // TODO: Implement onExit() method.
        var_dump("actor".$this->actorId() . "已经退出,退出参数:".json_encode($arg)."\n");
        return "on exit success\n";
    }

    /**
     * 当执行出现异常时的回调
     * @author: blank < 1161709455@qq.com >
     */
    protected function onException(\Throwable $throwable)
    {
        // TODO: Implement onException() method.
    }
}