<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 18-10-22
 * Time: 上午11:03
 */

namespace App\Socket;


use App\Socket\Bean\Caller;
use App\Socket\Bean\Response;
use App\Socket\Client\Tcp;
use App\Socket\Client\Udp;
use App\Socket\Client\WebSocket;

class Dispatcher
{

    private $register;
    function __construct(Register $register)
    {
        $this->register = $register;
        if($register->getParser() == null){
            throw new \Exception('Package parser is required');
        }
    }
    /*
     * $args:
     *  Tcp  $fd，$reactorId
     *  Web Socket swoole_websocket_frame $frame
     *  Udp array $client_info;
     */
    function dispatch(\swoole_server $server, string $data, ...$args):void
    {
        $clientIp = null;
        $type = $this->register->getType();
        switch ($type){
            case Register::TCP:{
                $client = new Tcp( ...$args);
                $clientIp = $server->getClientInfo($client->getFd())['remote_ip'];
                break;
            }
            case Register::WEB_SOCKET:{
                $client = new WebSocket( ...$args);
                $clientIp = $server->getClientInfo($client->getFd())['remote_ip'];
                break;
            }
            case Register::UDP:{
                $client = new Udp( ...$args);
                $clientIp = $client->getAddress();
                break;
            }
            default:{
                throw new \Exception('dispatcher type error : '.$type);
            }
        }
        if($this->register->getIpWhiteList() && (!$this->register->getIpWhiteList()->check($clientIp))){
            $this->close($server,$client);
            return;
        }
        $caller = null;
        $response = new Response();
        try{
            $caller = $this->register->getParser()->decode($data, $client);
        }catch (\Throwable $throwable){
            //注意，在解包出现异常的时候，则调用异常处理，默认是断开连接，服务端抛出异常
            $this->hookException($server, $throwable, $data, $client, $response);
        }
        //如果成功返回一个调用者，那么执行调用逻辑
        if($caller instanceof Caller){
            $caller->setClient($client);
            //解包正确
            $controller = "App\\Socket\\Controller\\".$caller->getControllerClass();
            try{
                (new $controller($caller, $response));
            }catch (\Throwable $throwable){
                //若控制器中没有重写异常处理，默认是断开连接，服务端抛出异常
                $this->hookException($server, $throwable, $data, $client, $response);
            }
        }
        switch ($response->getStatus()){
            case Response::STATUS_OK:{
                $res = $this->register->getParser()->encode($response, $client);
                var_dump($res);
                $this->response($server, $client, $res);
                break;
            }
            case Response::STATUS_RESPONSE_AND_CLOSE:{
                $res = $this->register->getParser()->encode($response, $client);
                $this->response($server, $client, $res);
                $this->close($server, $client);
                break;
            }
            case Response::STATUS_RESPONSE_DETACH:{
                break;
            }
            case Response::STATUS_CLOSE:{
                $this->close($server, $client);
                break;
            }
        }
    }

    private function response(\swoole_server $server, $client, $data)
    {
        if($client instanceof WebSocket){
            $server->push($client->getFd(), $data);
        }else if($client instanceof Tcp){
            $server->send($client->getFd(), $data);
        }else if($client instanceof Udp){
            $server->sendto($client->getAddress(), $client->getPort(), $data, $client->getServerSocket());
        }
    }

    private function close(\swoole_server $server,$client)
    {
        if($client instanceof Tcp){
            $server->close($client->getFd());
        }
    }
    private function hookException(\swoole_server $server, \Throwable $throwable, string $raw,$client, Response $response)
    {
        if(is_callable($this->register->getOnExceptionHandler())){
            call_user_func($this->register->getOnExceptionHandler(), $server, $throwable, $raw,$client, $response);
        }else{
            $this->close($server, $client);
            throw $throwable;
        }
    }

}