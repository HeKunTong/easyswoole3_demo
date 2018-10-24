<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 18-9-25
 * Time: 下午3:18
 */

namespace App\Socket;


use App\Socket\AbstractInterface\ParserInterface;
use App\Socket\Bean\Caller;
use App\Socket\Bean\Response;

class Parse implements ParserInterface
{
//    function handler($frame)
//    {
//        $json = json_decode($frame->data, true);
//        if(!($json['class'] === 'heartbeat' && $json['action'] === 'index')) {
//            $control = isset($json['class']) ? 'App\\WebSocket\\Controller\\'. ucfirst($json['class']) : 'App\\WebSocket\\Controller\\Index';
//            if (class_exists($control)) {
//                $class = new $control();
//                $action = $json['action'] ?? 'index';
//                $data = $json['data'] ?? null;
//                if (is_callable(array($class, $action))) {
//                    call_user_func(array($class, $action), $frame->fd, $data);
//                }
//            }
//        }
//    }


    public function decode($raw, $client): ?Caller
    {
        // TODO: Implement decode() method.
        $json = json_decode($raw, true);
        $ret =  new Caller();
        $ret->setControllerClass($json['class']);
        $ret->setAction($json['action']);
        return $ret;
    }

    public function encode(Response $response, $client): ?string
    {
        // TODO: Implement encode() method.
        return $response->__toString();
    }
}