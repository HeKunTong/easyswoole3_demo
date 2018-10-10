<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 18-10-10
 * Time: 上午10:10
 */

namespace App\Model;


use App\Utility\Pools\MysqlPool;
use App\Utility\Pools\MysqlPoolObject;
use EasySwoole\Component\Pool\PoolManager;

class Base
{
    private $db;
    function __construct()
    {
        $db = PoolManager::getInstance()->getPool(MysqlPool::class)->getObj();
        if ($db instanceof MysqlPoolObject) {
            $this->db = $db;
        } else {
            throw new \Exception('Db pool is empty');
        }
    }

    protected function getDb() {
        return $this->db;
    }

    function __destruct()
    {
        // TODO: Implement __destruct() method.
        if ($this->db instanceof MysqlPoolObject) {
            PoolManager::getInstance()->getPool(MysqlPool::class)->recycleObj($this->db);
        }
    }

}