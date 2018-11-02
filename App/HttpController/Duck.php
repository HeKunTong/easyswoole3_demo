<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 18-9-21
 * Time: 下午2:29
 */

namespace App\HttpController;


use App\Model\User;
use App\Model\UserBean;
use EasySwoole\EasySwoole\ServerManager;
use EasySwoole\Http\AbstractInterface\REST;

class Duck extends REST
{

    public function GETIp()
    {
        $fd = $this->request()->getSwooleRequest()->fd;
        $ip = ServerManager::getInstance()->getSwooleServer()->connection_info($fd);
        $this->response()->write($ip['remote_ip']);
    }

    public function GETUsers()
    {

        $model = new User();
        $list = $model->getList();
        $this->writeJson(200, $list, '成功');
    }

    public function GETUser()
    {
        $bean = new UserBean();
        $bean->setId(1);
        $model = new User();
        $user = $model->getUser($bean);
        $this->writeJson(200, $user);
    }

}