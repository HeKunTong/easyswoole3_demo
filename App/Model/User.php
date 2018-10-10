<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 18-9-25
 * Time: 下午4:29
 */

namespace App\Model;


class User extends Base
{
    protected $table = 'test';

    function getList(){
        return $this->getDb()->get($this->table);
    }

    function getUser(UserBean $user) {
        return $this->getDb()->where('id', $user->getId())->getOne($this->table);
    }
}