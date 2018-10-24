<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 18-10-22
 * Time: 上午10:58
 */

namespace App\Socket\Bean;


use EasySwoole\Spl\SplBean;

class Response extends SplBean
{

    const STATUS_RESPONSE_DETACH = 'RESPONSE_DETACH';//不响应客户端，可能是在异步时返回。
    const STATUS_RESPONSE_AND_CLOSE = 'RESPONSE_AND_CLOSE';//响应后关闭
    const STATUS_CLOSE = 'CLOSE';//不响应，直接关闭连接
    const STATUS_OK = 'OK';
    protected $status = self::STATUS_OK;
    protected $result = [];
    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }
    /**
     * @param string $status
     */
    public function setStatus(string $status): void
    {
        $this->status = $status;
    }
    /**
     * @param array $args
     */
    public function setArgs(array $args): void
    {
        $this->args = $args;
    }
    public function setResult(array $result)
    {
        $this->result = $result;
    }
    public function getResult():array
    {
        return $this->result;
    }
    public function addResult($key,$value):Response
    {
        $this->result[$key] = $value;
        return $this;
    }

}