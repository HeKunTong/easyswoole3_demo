<?php


namespace App\HttpController;


use EasySwoole\EasySwoole\ServerManager;
use EasySwoole\EasySwoole\Task\TaskManager;
use EasySwoole\Http\AbstractInterface\Controller;
use EasySwoole\HttpClient\HttpClient;
use EasySwoole\Spl\SplString;
use EasySwoole\Template\Render;

class Index extends Controller
{

    public function index()
    {
        $file = EASYSWOOLE_ROOT.'/vendor/easyswoole/easyswoole/src/Resource/Http/welcome.html';
        if(!is_file($file)){
            $file = EASYSWOOLE_ROOT.'/src/Resource/Http/welcome.html';
        }
        $this->response()->write(file_get_contents($file));
    }

    protected function actionNotFound(?string $action)
    {
        $this->response()->withStatus(404);
        $file = EASYSWOOLE_ROOT.'/vendor/easyswoole/easyswoole/src/Resource/Http/404.html';
        if(!is_file($file)){
            $file = EASYSWOOLE_ROOT.'/src/Resource/Http/404.html';
        }
        $this->response()->write(file_get_contents($file));
    }

    /**
     * 返回json的格式
     */
    function test() {
        $params = $this->request()->getRequestParam();
        $this->writeJson(200, $params, '成功123');
    }

    /**
     * 通过curl获取歌词
     */
    function lyric() {
        $url = 'https://c.y.qq.com/lyric/fcgi-bin/fcg_query_lyric.fcg';
        $params = [
            'nobase64' => 1,
            'musicid' => 109332150,
            'inCharset' => 'utf8',
            'outCharset' => 'utf-8'
        ];
        $url = $url.'?'.http_build_query($params);
        $client = new HttpClient($url);
        $client->setHeader('user-agent', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_5) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.121 Safari/537.36');
        $client->setConnectTimeout(30);
        $response = $client->get(['referer' => 'https://y.qq.com/n/yqq/song/001xiJdl0t4NgO.html']);
        $content = $response->getBody();
        $string = new SplString($content);
        $content = $string->regex('/\{.*\}/');
        $json = json_decode($content, true);
        $lyric = $json['lyric'];
        $this->response()->withHeader("content-type", "application/json;charset=UTF-8");
        $this->response()->write(html_entity_decode($lyric));
    }

    /**
     * 投递闭包任务
     */
    function async() {
        TaskManager::getInstance()->async(function() {
            try {
                \Co::sleep(2);
                var_dump('async');
            } catch (\Throwable $throwable) {
                var_dump('throwable');
            }

        });
        $this->response()->write('async');
    }

    function template() {
        $html = Render::getInstance()->render('index', [
            'name'=> 'easyswoole'
        ]);
        $this->response()->write($html);
    }


    function reload() {
        ServerManager::getInstance()->getSwooleServer()->reload();
        $this->response()->write('reload');
    }
}