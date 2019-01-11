<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 19-1-11
 * Time: 上午11:45
 */

namespace App\Task;


use App\Model\Jd\JdBean;
use App\Model\Jd\JdModel;
use App\Utility\Pool\MysqlPoolObject;
use EasySwoole\HttpClient\HttpClient;

class JdGoodClient
{
    protected $db;

    function __construct(MysqlPoolObject $db)
    {
        $this->db = $db;
    }

    function handle($url)
    {
        $client = new HttpClient();
        $client->setUrl($url);
        $client->setTimeout(3);
        $ret = $client->exec();
        if ($ret->getErrMsg()) {
            var_dump($ret->getErrCode());
            var_dump($ret->getErrMsg());
        } else {
            $body = $ret->getBody();
            $html = new \simple_html_dom();
            $html->load($body);
            $list = $html->find('ul.gl-warp', 0);
            $len = count($list->find('.gl-item'));
            $skus = [];
            for ($i = 0; $i < $len; $i++) {
                $item = $list->find('.gl-item', $i);
                $sku = $item->find('.j-sku-item', 0)->getAttribute('data-sku');
                $skus[] = 'J_'.$sku;
                $name = trim($item->find('.p-name em', 0)->plaintext);
                $shop = $item->find('.p-shop', 0)->getAttribute('data-shop_name');
                $data = [
                    'name' => $name,
                    'shop' => $shop,
                    'sku' => $sku,
                ];
                $bean = new JdBean($data);
                $model = new JdModel($this->db);
                $model->insert($bean);
            }
            $this->getPrice($skus);
        }
    }

    private function getPrice($skus)
    {
        $url = 'https://p.3.cn/prices/mgets';
        $params = [
            'skuIds' => implode(',', $skus)
        ];
        $url = $url.'?'.http_build_query($params);

        $client = new HttpClient();
        $client->setUrl($url);
        $client->setTimeout(3);
        $ret = $client->exec();
        if ($ret->getErrMsg()) {
            var_dump($ret->getErrCode());
            var_dump($ret->getErrMsg());
        } else {
            $body = $ret->getBody();
            $result = json_decode($body, true);

            foreach ($result as $item) {
                $sku = substr($item['id'], 2);
                $price = floatval($item['p']) * 100;
                $bean = new JdBean();
                $bean->setSku($sku);
                $model = new JdModel($this->db);
                $model->update($bean, $price);
            }
        }
    }
}