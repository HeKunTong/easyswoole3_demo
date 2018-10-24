<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 18-10-22
 * Time: 上午10:55
 */

namespace App\Socket\AbstractInterface;


use App\Socket\Bean\Caller;
use App\Socket\Bean\Response;

interface ParserInterface
{
    public function decode($raw,$client):?Caller;

    public function encode(Response $response,$client):?string ;
}