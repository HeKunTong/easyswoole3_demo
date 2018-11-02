<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 18-10-22
 * Time: 上午11:02
 */

namespace App\Socket;


use App\Socket\AbstractInterface\ParserInterface;
use App\Socket\Bean\IpWhiteList;

class Register
{

    const UDP = 'UDP';
    const TCP = 'TCP';
    const WEB_SOCKET = 'WEB_SOCKET';
    protected $type;
    protected $onExceptionHandler = null;
    protected $parser;
    protected $ipWhiteList = null;
    public function getIpWhiteList():?IpWhiteList
    {
        return $this->ipWhiteList;
    }
    function setIpWhiteList():IpWhiteList
    {
        if(!isset($this->ipWhiteList)){
            $this->ipWhiteList = new IpWhiteList();
        }
        return $this->ipWhiteList;
    }
    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }
    /**
     * @param mixed $type
     */
    public function setType($type): void
    {
        $this->type = $type;
    }
    /**
     * @return null
     */
    public function getOnExceptionHandler()
    {
        return $this->onExceptionHandler;
    }
    /**
     * @param null $onExceptionHandler
     */
    public function setOnExceptionHandler($onExceptionHandler): void
    {
        $this->onExceptionHandler = $onExceptionHandler;
    }
    /**
     * @return mixed
     */
    public function getParser():?ParserInterface
    {
        return $this->parser;
    }
    /**
     * @param mixed $parser
     */
    public function setParser(ParserInterface $parser): void
    {
        $this->parser = $parser;
    }

}