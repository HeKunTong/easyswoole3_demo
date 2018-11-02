<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 18-10-10
 * Time: 下午3:33
 */

namespace App\Task;


use App\Model\Jd\JdBean;
use App\Model\Jd\JdModel;
use App\Utility\Pool\MysqlPool;
use EasySwoole\Component\Pool\PoolManager;
use EasySwoole\Curl\Request;

class JdGood
{
    protected $db;

    function __construct()
    {
        $db = PoolManager::getInstance()->getPool(MysqlPool::class)->getObj();
        if ($db) {
            $this->db = $db;
        } else {
            throw new \Exception('mysql pool is empty');
        }
    }

    function handle($url)
    {
        $request = new Request($url);
        $body = $request->exec()->getBody();
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

    private function getPrice($skus)
    {
        $url = 'https://p.3.cn/prices/mgets';
        $params = [
            'skuIds' => implode(',', $skus)
        ];
        $url = $url.'?'.http_build_query($params);
        $request = new Request($url);

        $result = json_decode($request->exec()->getBody(), true);

        foreach ($result as $item) {
            $sku = substr($item['id'], 2);
            $price = floatval($item['p']) * 100;
            $bean = new JdBean();
            $bean->setSku($sku);
            $model = new JdModel($this->db);
            $model->update($bean, $price);
        }
    }

    function __destruct()
    {
        // TODO: Implement __destruct() method.
        PoolManager::getInstance()->getPool(MysqlPool::class)->recycleObj($this->db);
    }
}