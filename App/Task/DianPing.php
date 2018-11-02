<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 18-10-24
 * Time: 上午9:53
 */

namespace App\Task;


use App\Queue\Queue;

class DianPing
{
    function run() {
        $url = 'http://www.dianping.com/xiamen/ch10/r8085';
        $root = EASYSWOOLE_ROOT;
        $content = shell_exec("phantomjs $root/Html/food.js $url");
        $html = new \simple_html_dom();
        $html->load($content);
        $length = count($html->find('.page', 0)->find('.PageLink'));
        $page = intval($html->find('.page', 0)->find('.PageLink', $length - 1)->plaintext);
        $queue = new Queue();
        $queue->lPush($url);
        echo $url.PHP_EOL;
        for ($i = 2; $i <= $page; $i++) {
            $queue->lPush($url."p$i");
            echo $url."p$i".PHP_EOL;
        }
    }

    function detail() {
        $model = new \App\Model\DianPing\DianPing();
        $list = $model->getList();
        $queue = new Queue();
        foreach ($list as $item) {
            $queue->lPush("http://www.dianping.com/shop/".$item['shopId']);
            echo "http://www.dianping.com/shop/".$item['shopId'].PHP_EOL;
        }
        unset($model);
    }
}