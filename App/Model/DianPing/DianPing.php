<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 18-10-24
 * Time: 上午9:47
 */

namespace App\Model\DianPing;


use App\Model\Base;
use EasySwoole\Spl\SplBean;

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

    function update($shopId, DianPingBean $bean) {
        $this->getDb()->where('shopId', $shopId)->update($this->table, $bean->toArray(null, SplBean::FILTER_NOT_NULL));
    }

    function getLatitude($shopId) {
        $shop = $this->getDb()->where('shopId', $shopId)->getOne($this->table, 'latitude');
        return empty($shop) ? $shop['latitude'] : '';
    }
}