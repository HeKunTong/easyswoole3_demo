<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 18-10-10
 * Time: 上午10:10
 */

namespace App\Model;


use App\Utility\Pool\MysqlPool;
use App\Utility\Pool\MysqlPoolObject;
use EasySwoole\Component\Pool\PoolManager;

class BaseModel
{
    private $db;
    function __construct(MysqlPoolObject $db)
    {
        $this->db = $db;
    }

    protected function getDb(): MysqlPoolObject {
        return $this->db;
    }

}