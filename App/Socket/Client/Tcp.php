<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 18-10-22
 * Time: 上午10:59
 */

namespace App\Socket\Client;


class Tcp
{
    protected $reactorId;
    protected $fd;
    function __construct($fd = null,$reactorId = null)
    {
        $this->fd = $fd;
        $this->reactorId = $reactorId;
    }
    /**
     * @return mixed
     */
    public function getReactorId()
    {
        return $this->reactorId;
    }
    /**
     * @param mixed $reactorId
     */
    public function setReactorId($reactorId)
    {
        $this->reactorId = $reactorId;
    }
    /**
     * @return mixed
     */
    public function getFd()
    {
        return $this->fd;
    }
    /**
     * @param mixed $fd
     */
    public function setFd($fd)
    {
        $this->fd = $fd;
    }
}