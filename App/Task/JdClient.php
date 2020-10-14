<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 19-1-11
 * Time: 上午11:17
 */

namespace App\Task;


use App\Model\Jd;
use App\Queue\Queue;
use EasySwoole\HttpClient\HttpClient;
use EasySwoole\RedisPool\Redis;

class JdClient
{
    public function run()
    {
        // TODO: Implement run() method.
        $url = 'https://list.jd.com/list.html';
        $params = [
            'cat' => '9987,653,655',
            'ev' => 'exbrand_Apple^',
            'cid3' => '655',
        ];
        $url = $url.'?'.http_build_query($params);
        $client = new HttpClient();
        $client->setHeader('user-agent', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_5) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.121 Safari/537.36');
        $client->setUrl($url);
        $ret = $client->get();
        if (!$ret->getErrMsg()) {
            echo 'task-----'.$url.PHP_EOL;
            $html = new \simple_html_dom();
            $html->load($ret->getBody());
            $list = $html->find('ul.gl-warp', 0);
            $len = count($list->find('.gl-item'));
            for ($i = 0; $i < $len; $i++) {
                $item = $list->find('.gl-item', $i);
                $sku = $item->getAttribute('data-sku');
                $name = trim($item->find('.p-name em', 0)->plaintext);
                $shopNode = $item->find('.curr-shop', 0);
                $shop = $shopNode ? $shopNode->plaintext : '';
                $price = floatval($item->find('.p-price i', 0)->plaintext) * 100;
                $model = new Jd();
                $model->name = $name;
                $model->shop = $shop;
                $model->sku = $sku;
                $model->price = $price;
                $model->save();
            }
            preg_match_all('/s\.init\(\d+, (\d+),/', $ret->getBody(), $match);
            if (isset($match[1]) && isset($match[1][0])) {
                $last = intval($match[1][0]);
                $redis = Redis::defer('redis');
                $queue = new Queue($redis);
                $i = 2;
                while ($i <= $last) {
                    $queue->lPush($url.'&page='.$i);
                    $i = $i + 1;
                }
            }
        }
    }
}