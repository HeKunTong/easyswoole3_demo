<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 18-10-22
 * Time: 上午11:08
 */

namespace App\Socket;


use App\Socket\Client\Tcp;
use App\Socket\Client\Udp;
use App\Socket\Client\WebSocket;
use EasySwoole\EasySwoole\ServerManager;

class Deliverer
{
    /*
    *  int $opcode = 1, bool $finish = true在给websocket客户端回复时候有效
    */
    static function response($client,$data, int $opCode = 1, bool $finish = true)
    {
        $server = ServerManager::getInstance()->getServer();
        if($client instanceof Tcp){
            $server->send($client->getFd(), $data, $client->getReactorId());
        }else if($client instanceof WebSocket){
            return $server->push($client->getFd(), $data, $opCode, $finish);
        }else if($client instanceof Udp){
            return $server->sendto($client->getAddress(), $client->getPort(), $data,$client->getServerSocket());
        }else{
            return false;
        }
    }
}