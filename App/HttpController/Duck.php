<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 18-9-21
 * Time: 下午2:29
 */

namespace App\HttpController;


use App\Model\Jd;
use App\Model\User;
use EasySwoole\EasySwoole\ServerManager;
use EasySwoole\Http\Message\Status;

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
        $list = User::create()->all();
        $this->writeJson(Status::CODE_OK, $list, '成功');
    }

    public function user()
    {
        $model = new User();
        $model->id = 2;
        $user = $model->getUser();
        $this->writeJson(Status::CODE_OK, $user);
    }

    public function jd() {
        $list = Jd::create()->all();
        $this->writeJson(Status::CODE_OK, $list);
    }

}