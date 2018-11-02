<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 18-10-22
 * Time: 上午10:57
 */

namespace App\Socket\Bean;


class IpWhiteList
{
    protected $list = ['127.0.0.1'];
    public function add(string $ip):IpWhiteList
    {
        array_push($this->list,$ip);
        return $this;
    }
    public function set(array $list):IpWhiteList
    {
        $this->list = $list;
        return $this;
    }
    public function getList():array
    {
        return $this->list;
    }
    public function check(string $ip)
    {
        return in_array($ip,$this->list);
    }
}