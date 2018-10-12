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
use App\Task\Jd;
use App\Utility\Pools\RedisPool;
use EasySwoole\Component\Pool\PoolManager;
use EasySwoole\EasySwoole\ServerManager;
use EasySwoole\Http\AbstractInterface\REST;
use Swoole\Coroutine\Redis;

class Duck extends REST
{
    private $redis;

    protected function onRequest(?string $action): ?bool
    {
        $this->redis = PoolManager::getInstance()->getPool(RedisPool::class)->getObj();
        if(!$this->redis instanceof Redis){
            throw new \Exception('Redis Pool is Empty');
        }
        return true;
    }

    public function gc() {
        parent::gc();
        /*
        * 因为控制器是对象池模式，因此请重置自定义属性,否则会被下个请求复用。
        */
        $this->redis = null;
    }

    public function GETInfo()
    {
        $fd = $this->request()->getSwooleRequest()->fd;
        echo $fd.PHP_EOL;
        $ip = ServerManager::getInstance()->getSwooleServer()->connection_info($fd);
        print_r($ip);
        $this->response()->write('duck info......');
    }

    public function GETRedis()
    {
        $this->response()->write($this->redis->get('name'));
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

    public function GETNotify()
    {
        $this->response()->write('notify...');
    }

    public function GETPublish()
    {
        $this->redis->publish('ch1', 'hello world');
        $this->response()->write('publish...');
    }

    public function GETGoods()
    {
        \Co::create(function (){
            $jd = new Jd();
            $jd->run();
        });

        $this->response()->write('goods is adding!');
    }

}