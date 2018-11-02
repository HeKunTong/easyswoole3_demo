<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 18-10-17
 * Time: 上午9:44
 */

namespace App\Model\Goods;


use App\Model\BaseModel;

class Goods extends BaseModel
{

    protected $tableName = 'goods_list';

    function add(GoodBean $bean){
        return $this->getDb()->insert($this->tableName,$bean->toArray(null, $bean::FILTER_NOT_NULL));
    }
}