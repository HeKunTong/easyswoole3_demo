<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 18-9-25
 * Time: 下午2:21
 */

namespace App\WebSocket\Controller;



use EasySwoole\EasySwoole\ServerManager;

class Info
{

    function index($fd, $data)
    {
        // TODO: Implement index() method.
        print_r($data);
        ServerManager::getInstance()->getSwooleServer()->push($fd, json_encode(['hello', 'world']));
    }
}