<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 18-9-25
 * Time: 下午3:18
 */

namespace App\WebSocket;


class Parse
{
    function handler($frame)
    {
        $json = json_decode($frame->data, true);
        if(!($json['class'] === 'heartbeat' && $json['action'] === 'index')) {
            $control = isset($json['class']) ? 'App\\WebSocket\\Controller\\'. ucfirst($json['class']) : 'App\\WebSocket\\Controller\\Index';
            if (class_exists($control)) {
                $class = new $control();
                $action = $json['action'] ?? 'index';
                $data = $json['data'] ?? null;
                if (is_callable(array($class, $action))) {
                    call_user_func(array($class, $action), $frame->fd, $data);
                }
            }
        }
    }
}