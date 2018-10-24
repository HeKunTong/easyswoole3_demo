<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 18-10-22
 * Time: 上午10:53
 */

namespace App\Socket\AbstractInterface;


use App\Socket\Bean\Caller;
use App\Socket\Bean\Response;

abstract class Controller
{
    private $response;
    private $caller;
    function __construct(Caller $request, Response $response)
    {
        $this->caller = $request;
        $this->response = $response;
        $this->__hook();
    }
    protected function actionNotFound(?string $actionName)
    {
    }
    protected function afterAction(?string $actionName)
    {
    }
    protected function onException(\Throwable $throwable):void
    {
        throw $throwable;
    }
    /*
     * 返回false的时候为拦截
     */
    protected function onRequest(?string $actionName):bool
    {
        return true;
    }
    protected function response():Response
    {
        return $this->response;
    }
    protected function caller():Caller
    {
        return $this->caller;
    }
    private function __hook()
    {
        try{
            if($this->onRequest($this->caller->getAction()) === false){
                return;
            }else{
                $list = [];
                $ref = new \ReflectionClass(static::class);
                $public = $ref->getMethods(\ReflectionMethod::IS_PUBLIC);
                foreach ($public as $item){
                    array_push($list,$item->getName());
                }
                $all = array_diff($list,
                    [
                        'gc','__hook','__destruct',
                        '__clone','__construct','__call',
                        '__callStatic','__get','__set',
                        '__isset','__unset','__sleep',
                        '__wakeup','__toString','__invoke',
                        '__set_state','__clone','__debugInfo'
                    ]
                );
                $action = $this->caller->getAction();
                if(in_array($action,$all)){
                    $this->$action();
                }else{
                    $this->actionNotFound($action);
                }
            }
        }catch (\Throwable $throwable){
            //若没有重构onException，直接抛出给上层
            $this->onException($throwable);
        }finally{
            $this->afterAction($this->caller->getAction());
        }
    }
}