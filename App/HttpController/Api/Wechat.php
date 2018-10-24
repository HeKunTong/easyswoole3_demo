<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 18-10-15
 * Time: 下午3:32
 */

namespace App\HttpController\Api;


use EasySwoole\EasySwoole\Config;
use EasySwoole\EasySwoole\Logger;
use EasySwoole\Http\AbstractInterface\Controller;
use EasyWeChat\Factory;

class Wechat extends Controller
{

    function index()
    {
        // TODO: Implement index() method.
    }

    //现在来实现getToken方法
    function getToken()
    {
        //从小程序端接受code
        $code = $this->request()->getRequestParam('code');
        $config = $this->getConfig();
        $app = Factory::MiniProgram($config);
        try {
            //执行外部请求，将从微信服务器获取 session_key，注意目前这个是同步操作
            $ret = $app->auth->session($code);
            if(!isset($ret['session_key'])){
                Logger::getInstance()->log('微信session_key获取失败:('.$ret['errcode'].')'.$ret['errmsg']);
                throw new \Exception('系统繁忙，请稍后再试', 101);
            }
            //返回成功后将 session_key 回传给小程序，以便执行第二阶段。
            $this->success($ret);
        } catch (\Exception $e){
            $this->writeJson($e->getCode(), [], $e->getMessage());
        }
    }

    private function getConfig()
    {
        $wechat = Config::getInstance()->getConf('WECHAT');
        return [
            'app_id' => $wechat['app_id'],
            'secret' => $wechat['secret'],
            'response_type' => 'array',
            'log' => [
                'level' => 'debug',
                'file' => Config::getInstance()->getConf('LOG_DIR').'/wechat.log',
            ],
        ];
    }
}