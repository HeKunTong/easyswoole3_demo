<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 18-10-10
 * Time: 下午1:49
 */

namespace App\Task;


use App\Queue\JdQueue;
use EasySwoole\Curl\Request;

class Jd
{

    public function run()
    {
        // TODO: Implement run() method.
        $url = 'https://list.jd.com/list.html';
        $params = [
            'cat' => '9987,653,655',
            'ev' => 'exbrand_14026',
            'sort' => 'sort_rank_asc',
            'trans' => 1,
            'JL' => '3_品牌_Apple#J_crumbsBar'
        ];
        $url = $url.'?'.http_build_query($params);
        $request = new Request($url);
        $request->setUserOpt([CURLOPT_REFERER => 'https://list.jd.com/list.html?cat=9987,653,655']);
        $body = $request->exec()->getBody();
        $html = new \simple_html_dom();
        $html->load($body);
        $currentPage = 'https://list.jd.com'.$html->find('.p-num a.curr', 0)->href;
        $total = intval($html->find('.p-skip b', 0)->plaintext);
        $i = 2;
        echo $currentPage.PHP_EOL;
        $queue = new JdQueue();
        $queue->lPush($currentPage);
        while($i <= $total) {
            $page = str_replace('page=1', "page=$i", $currentPage);
            echo $page.PHP_EOL;
            $queue->lPush($page);
            $i++;
        }
    }
}