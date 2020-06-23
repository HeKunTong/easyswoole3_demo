<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 18-10-10
 * Time: 上午10:10
 */

namespace App\Model;

use EasySwoole\ORM\AbstractModel;

class BaseModel extends AbstractModel
{
    protected $tableName;

    public function getTableName() {
        return $this->tableName;
    }
}