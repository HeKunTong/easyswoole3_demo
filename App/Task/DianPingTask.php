<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 18-10-24
 * Time: 上午10:08
 */

namespace App\Task;


use App\Model\DianPing\DianPingBean;
use App\Model\DianPing\DianPing;

class DianPingTask
{
    function handle($url) {
        $root = EASYSWOOLE_ROOT;
        $content = shell_exec("phantomjs $root/Html/food.js $url");
        $html = new \simple_html_dom();
        $html->load($content);
        $shops = $html->find('#shop-all-list', 0);
        $length = count($shops->find('li'));
        for ($i = 0; $i < $length; $i++) {
            $shop = $shops->find('li', $i);
            $info = $shop->find('.tit a', 0);
            $name = $info->title;
            $shopId = $info->getAttribute('data-shopid');
            $branchNode = $shop->find('.tit .shop-branch', 0);
            $branch = $branchNode ? trim($branchNode->plaintext) : '';
            $comment = $shop->find('.comment', 0);
            $rankClass = $comment->find('.sml-rank-stars', 0)->class;
            $pos = strpos($rankClass, 'sml-str');
            $stars = substr($rankClass, $pos + 7);
            $shopViewNode = $comment->find('a', 0)->find('b', 0);
            $view = $shopViewNode ? $shopViewNode->plaintext : 0;
            $shopPriceNode = $comment->find('a', 1)->find('b', 0);
            $shopPrice = $shopPriceNode ? mb_substr($shopPriceNode->plaintext, 1) : 0;
            $shopTagCate = $shop->find('.tag-addr a', 0)->plaintext;
            $shopTagRegion = $shop->find('.tag-addr a', 1)->plaintext;
            $address = $shop->find('.tag-addr .addr', 0)->plaintext;
            $recommendLength = count($shop->find('.recommend a'));
            $recommend = '';
            if ($recommendLength != 0) {
                $recommends = [];
                for($j = 0; $j < $recommendLength; $j++) {
                    $recommends[] = $shop->find('.recommend a', $j)->plaintext;
                }
                $recommend = join(',', $recommends);
            }
            $commentList = $shop->find('.comment-list', 0);
            $taste = '';
            $surrounding = '';
            $service = '';
            if ($commentList) {
                $tasteNode = $commentList->find('span', 0)->find('b', 0);
                $taste = $tasteNode ? $tasteNode->plaintext : $taste;
                $surroundingNode = $commentList->find('span', 1)->find('b', 0);
                $surrounding = $surroundingNode ? $surroundingNode->plaintext : $surrounding;
                $serviceNode = $commentList->find('span', 2)->find('b', 0);
                $service = $serviceNode ? $serviceNode->plaintext : $service;
            }
            $item = [
                'name' => $name,
                'shopId' => $shopId,
                'branch' => $branch,
                'stars' => $stars,
                'view' => $view,
                'shopPrice' => $shopPrice,
                'shopTagCate' => $shopTagCate,
                'shopTagRegion' => $shopTagRegion,
                'address' => $address,
                'recommend' => $recommend,
                'taste' => $taste,
                'surrounding' => $surrounding,
                'service' => $service
            ];
            $bean = new DianPingBean($item);
            $model = new DianPing();
            $model->insert($bean);
            unset($bean);
            unset($model);
        }
    }
}