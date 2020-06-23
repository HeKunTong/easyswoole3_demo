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
use App\Utility\Pool\MysqlPool;
use EasySwoole\EasySwoole\ServerManager;
use EasySwoole\Pool\Manager;

class Duck extends Base
{

    public function ip()
    {
        $fd = $this->request()->getSwooleRequest()->fd;
        $ip = ServerManager::getInstance()->getSwooleServer()->connection_info($fd);
        $this->response()->write($ip['remote_ip']);
    }

    public function users()
    {
        $list = UserModel::create()->all();
        $this->writeJson(200, $list, '成功');
    }

    public function user()
    {
        $model = new UserModel();
        $model->id = 2;
        $user = $model->getUser();
        $this->writeJson(200, $user);
    }

}