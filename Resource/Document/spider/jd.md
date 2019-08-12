# 爬虫案例（京东苹果手机为例子）

> 清空队列数据

```php
// 清空数据
$conf = Config::getInstance()->getConf('REDIS');
$redis = new \Redis();
$redis->connect($conf['host'], $conf['port']);
if (!empty($conf['auth'])) {
    $redis->auth($conf['auth']);
}
$redis->del(Queue::$queue);
```

> 开启协程式任务采集任务


```php
\Co::create(function (){
    $client = new JdClient();     // 协程客户端
    $client->run();
});
```

```备注```:使用协程客户端要在安装swoole时开启openssl选项。

> 采集京东苹果手机任务

协程客户端模式:

```php
<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 19-1-11
 * Time: 上午11:17
 */

namespace App\Task;


use App\Queue\Queue;
use App\Utility\Pool\RedisPool;
use EasySwoole\HttpClient\HttpClient;

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
            'trans' => 1,
            'JL' => '3_品牌_Apple'
        ];
        $url = $url.'?'.http_build_query($params);
        echo $url.PHP_EOL;
        $client = new HttpClient($url);
        $client->setTimeout(5);
        $client->setHeader('Referer', 'https://list.jd.com/list.html?cat=9987,653,655');
        $ret = $client->get();
        if ($ret->getErrMsg()) {
            var_dump($ret->getErrCode());
            var_dump($ret->getErrMsg());
        } else {
            $body = $ret->getBody();
            $html = new \simple_html_dom();
            $html->load($body);
            $curr = $html->find('.p-num a.curr', 0);
            $skip = $html->find('.p-skip b', 0);
            if (!empty($curr) && !empty($skip)) {
                $currentPage = 'https://list.jd.com'.$curr->href;
                $total = intval($skip->plaintext);
                $i = 2;
                echo $currentPage.PHP_EOL;
                $redis = RedisPool::defer();
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
}
```


> 开启两个协程任务,处理采集任务

```php
// 定时任务
Timer::getInstance()->after(5 * 1000, function () {
    // 定时任务
    $timer = Timer::getInstance()->loop(1 * 1000, function () use (&$timer) {
        \Co::create(function () use (&$timer){
            $goodTask = new JdGoodClient(); // 协程客户端
            $res = $goodTask->run();
            if (!$res) {
                if ($timer) {
                    Timer::getInstance()->clear($timer);
                }
                echo 'end-----'.PHP_EOL;
            }
        });
    });
});
```

> 采集任务处理逻辑

协程客户端:

```php
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
use App\Queue\Queue;
use App\Utility\Pool\MysqlPool;
use App\Utility\Pool\RedisPool;
use EasySwoole\HttpClient\HttpClient;

class JdGoodClient
{
    private $db;

    function __construct()
    {
        $this->db = MysqlPool::defer();
    }

    function run() {
        $redis = RedisPool::defer();
        $queue = new Queue($redis);
        $task = $queue->rPop();
        if ($task) {
            echo 'task-----'.$task.PHP_EOL;
            try {
                $this->handle($task);
            } catch (\Exception $exception) {   // 失败重回队列任务
                $queue->lPush($task);
            }
            return true;
        } else {
            return false;
        }
    }

    private function handle($url)
    {
        $client = new HttpClient($url);
        $client->setTimeout(3);
        $ret = $client->get();
        if ($ret->getErrMsg()) {
            throw new \Exception($ret->getErrMsg());
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

        $client = new HttpClient($url);
        $client->setTimeout(3);
        $ret = $client->get();
        if ($ret->getErrMsg()) {
            throw new \Exception($ret->getErrMsg());
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
```

采集任务分两步。

1. 采集手机名，sku以及店铺
2. 采集手机价格

> 数据表结构

```sql
CREATE TABLE `jd` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sku` varchar(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` int(11) DEFAULT NULL,
  `shop` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;
```