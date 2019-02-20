<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 19-1-9
 * Time: 上午10:10
 */

//require './vendor/autoload.php';
//require_once  './App/Utility/simple_dom_html.php';
// use EasySwoole\Actor\DeveloperTool;
//
//go(function (){
//    $tool = new DeveloperTool(\App\Actor\RoomActor::class,'001000001',[
//        'startArg'=>'startArg....'
//    ]);
//    $tool->onReply(function ($data){
//        var_dump('reply :'.$data);
//    });
//    swoole_event_add(STDIN,function ()use($tool){
//        $ret = trim(fgets(STDIN));
//        if(!empty($ret)){
//            go(function ()use($tool,$ret){
//                $tool->push(trim($ret));
//            });
//        }
//    });
//    $tool->run();
//});

//try{
//    \EasySwoole\Component\Invoker::exec(function (){
//        sleep(2);
//    });
//}catch (Throwable $throwable){
//    echo $throwable->getMessage();
//}

//pcntl_signal(SIGALRM, function () {
//    echo '定时到时' . PHP_EOL;
//});
//
//pcntl_alarm(5);
//$i=0;
//while(1){
//    echo $i.PHP_EOL;$i++;
//    // pcntl_signal_dispatch();
//    sleep(1);
//}

