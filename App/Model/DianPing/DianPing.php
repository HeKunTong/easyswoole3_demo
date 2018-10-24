<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 18-10-24
 * Time: 上午9:47
 */

namespace App\Model\DianPing;


use App\Model\Base;

class DianPing extends Base
{
    protected $table = 'dian_ping';

    function insert(DianPingBean $bean)
    {
        $this->getDb()->insert($this->table, $bean->toArray());
    }

    function getList() {
        return $this->getDb()->get($this->table, null, 'shopId');
    }
}