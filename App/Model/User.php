<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 18-9-25
 * Time: 下午4:29
 */

namespace App\Model;

/**
 * Class User
 * @package App\Model\User
 * @property $id
 * @property $name
 * @property $age
 */
class User extends BaseModel
{
    protected $tableName = 'user';
    function getUser() {
        return $this->get($this->id);
    }
}