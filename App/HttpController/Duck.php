<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 18-9-21
 * Time: 下午2:29
 */

namespace App\HttpController;


use App\Model\User\UserModel;
use App\Model\User\UserBean;
use EasySwoole\EasySwoole\ServerManager;

class Duck extends BaseWithDb
{

    public function ip()
    {
        $fd = $this->request()->getSwooleRequest()->fd;
        $ip = ServerManager::getInstance()->getSwooleServer()->connection_info($fd);
        $this->response()->write($ip['remote_ip']);
    }

    public function users()
    {

        $model = new UserModel($this->getDbConnection());
        $list = $model->getList();
        $this->writeJson(200, $list, '成功');
    }

    public function user()
    {
        $bean = new UserBean();
        $bean->setId(1);
        $model = new UserModel($this->getDbConnection());
        $user = $model->getUser($bean);
        $this->writeJson(200, $user);
    }

}