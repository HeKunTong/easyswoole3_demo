<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 19-1-11
 * Time: 上午11:17
 */

namespace App\Task;


use App\Queue\Queue;
use EasySwoole\RedisPool\Redis;

class JdClient
{
    public function run()
    {
        // TODO: Implement run() method.
        $url = 'https://list.jd.com/list.html';
        $params = [
            'cat' => '9987,653,655',
            'ev' => 'exbrand_14026',
            'sort' => 'sort_rank_asc',
        ];
        $url = $url.'?'.http_build_query($params);
        echo $url.PHP_EOL;
        $content = shell_exec("phantomjs template.js $url");
        $html = new \simple_html_dom();
        $html->load($content);
        $curr = $html->find('.p-num a.curr', 0);
        $skip = $html->find('.p-skip b', 0);
        if (!empty($curr) && !empty($skip)) {
            $currentPage = 'https://list.jd.com'.$curr->href;
            $total = intval($skip->plaintext);
            $i = 2;
            echo $currentPage.PHP_EOL;
            $redis = Redis::defer('redis');
            $queue = new Queue($redis);
            $queue->lPush($currentPage);
            while($i <= $total) {
                $page = str_replace('page=1', "page=$i", $currentPage);
                echo $page.PHP_EOL;
                $queue->lPush($page);
                $i++;
            }
        }
    }
}