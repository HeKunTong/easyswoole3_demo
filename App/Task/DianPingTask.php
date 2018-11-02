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
use EasySwoole\Curl\Request;

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

    function getPosition($url) {
        $shopId = str_replace('http://www.dianping.com/shop/', '', $url);
        $model = new DianPing();
        $root = EASYSWOOLE_ROOT;
        $content = shell_exec("phantomjs $root/Html/food.js $url");
        if ($content == 'Unable to access network') {
            var_dump('Unable to access network');
        } else {
            preg_match_all('/shopGlat: "(.*?)"/', $content, $shopGlat);
            preg_match_all('/shopGlng:"(.*?)"/', $content, $shopGlng);
            if (!empty($shopGlat[1]) && !empty($shopGlng[1])) {
                $latitude = $shopGlat[1][0] != '0.0' ? $shopGlat[1][0] : '';
                $longitude = $shopGlng[1][0] != '0.0' ? $shopGlng[1][0] : '';
                $thumb = '';
                $html = new \simple_html_dom();
                $html->load($content);
                $list = $html->find('#reviewlist-wrapper', 0);
                if ($list) {
                    $length = count($list->find('li'));
                    for($i = 0; $i < $length; $i++) {
                        $comment = $list->find('li', $i);
                        $stars = $comment->find('.sml-rank-stars', 0)->class;
                        $pos = strpos($stars, 'sml-str50');
                        if ($pos === false) {
                            $photosNode = $comment->find('.photos', 0);
                            if ($photosNode) {
                                $photoLength = count($photosNode->find('a'));
                                $photos = [];
                                for($j = 0; $j < $photoLength; $j++) {
                                    $src = $photosNode->find('a', $j)->find('img', 0)->getAttribute('data-lazyload');
                                    $photos[] = $src;
                                }
                                $thumb = join(',', $photos);
                                break;
                            }
                        }
                    }
                }
//                if (!empty($latitude) || !empty($longitude) || !empty($thumb)) {
//                    $params = [
//                        'latitude' => $latitude,
//                        'longitude' => $longitude,
//                        'thumb' => $thumb
//                    ];
//                    $bean = new DianPingBean($params);
//                    $model->update($shopId, $bean);
//                }
            } else {
//                $this->resolveLocation($url);
            }
        }
        unset($model);
    }

    function resolveLocation($url) {
        $request = new Request($url);
        $time = time();
        $request->setUserOpt([CURLOPT_COOKIE=> "_lxsdk_cuid=$time;"]);
        $headerLine = $request->exec()->getHeaderLine();
        preg_match_all("/Location: (.*?)\r\n/", $headerLine, $match);
        $url = $match[1][0];
        preg_match_all('/requestCode=([^&]+)/', $url, $requestMatch);

    }
}