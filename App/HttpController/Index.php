<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 18-9-18
 * Time: 上午11:32
 */

namespace App\HttpController;

use App\Task\Async;
use EasySwoole\Curl\Request;
use EasySwoole\EasySwoole\ServerManager;
use EasySwoole\EasySwoole\Swoole\Task\TaskManager;
use EasySwoole\Http\AbstractInterface\Controller;
use EasySwoole\Spl\SplString;

class Index extends Controller
{
    /**
     * 输出字符串
     */
    function index()
    {
        // TODO: Implement index() method.
        $this->response()->write('hello world');
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
        $request = new Request($url);
        $request->setUserOpt([CURLOPT_REFERER => 'https://y.qq.com/n/yqq/song/001xiJdl0t4NgO.html']);
        $content = $request->exec()->getBody();
        $string = new SplString($content);
        $content = $string->regex('/\{.*\}/');
        $json = json_decode($content, true);
        $lyric = $json['lyric'];
        $this->response()->write(html_entity_decode($lyric));
    }

    /**
     * 异步任务
     */
    function async() {
        TaskManager::async(new Async('async'));
        // TaskManager::sync(new Async('async'));
        $this->response()->write('async...');
    }

    /**
     * 并发任务
     */
    function barrier() {
        $taskList[] = function () {
            echo 'task1'.PHP_EOL;
        };
        $taskList[] = function () {
            sleep(1);
            echo 'task2'.PHP_EOL;
        };
        $taskList[] = function () {
            echo 'task3'.PHP_EOL;
        };
        TaskManager::barrier($taskList);
    }

    function reload() {
        ServerManager::getInstance()->getSwooleServer()->reload();
        $this->response()->write('reload');
    }

}