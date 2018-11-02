<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 18-10-22
 * Time: 上午10:56
 */

namespace App\Socket\Bean;


class Caller
{
    private $args = [];
    private $controllerClass = null;
    private $action = null;
    private $client;
    /**
     * @return array
     */
    public function getArgs(): array
    {
        return $this->args;
    }
    /**
     * @param array $args
     */
    public function setArgs(array $args): void
    {
        $this->args = $args;
    }
    public function getControllerClass():?string
    {
        return $this->controllerClass;
    }
    public function setControllerClass(?string $controllerClass): void
    {
        $this->controllerClass = ucfirst($controllerClass);
    }
    public function getAction():?string
    {
        return $this->action;
    }
    public function setAction(?string $action): void
    {
        $this->action = $action;
    }
    /**
     * @return mixed
     */
    public function getClient()
    {
        return $this->client;
    }
    /**
     * @param mixed $client
     */
    public function setClient($client): void
    {
        $this->client = $client;
    }
}