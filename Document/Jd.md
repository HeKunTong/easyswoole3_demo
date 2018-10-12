# 多进程爬虫案例（京东苹果手机为例子）

## 开启两个进程

```
    for($i = 1; $i <= 2; $i++) {
        Helper::addProcess("jd_process_$i", Jd::class);
    }
```

## 记录京东苹果手机要爬取的任务，也就是链接

```
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
```

    进入到京东苹果手机首页，将分页全部链接加入到redis队列任务里面。
 
## 处理redis队列任务

```
    class Jd extends AbstractProcess
    {
        public function run(Process $process)
        {
            // TODO: Implement run() method.
            $queue = new JdQueue();
            $goodTask = new JdGood();
            $this->addTick(1000, function() use ($queue,$goodTask) {
                \Co::create(function ()use ($queue,$goodTask){
                    $task = $queue->lPop();
                    if($task) {
                        echo '======'.$task.'======'.PHP_EOL;
                        $goodTask->handle($task);
                    } else {
                        echo 'end-----'.PHP_EOL;
                    }
                });
            });
        }
    
        public function onShutDown()
        {
            // TODO: Implement onShutDown() method.
        }
    
        public function onReceive(string $str)
        {
            // TODO: Implement onReceive() method.
        }
    }
```
    处理链接逻辑如下：
    
```
    class JdGood
    {
        function handle($url)
        {
            $request = new Request($url);
            $body = $request->exec()->getBody();
            $html = new \simple_html_dom();
            $html->load($body);
            $list = $html->find('.gl-warp', 0);
            $len = count($list->find('.gl-item'));
            $skus = [];
            for ($i = 0; $i < $len; $i++) {
                $item = $list->find('.gl-item', $i);
                $bean = new JdBean();
                $sku = $item->find('.j-sku-item', 0)->getAttribute('data-sku');
                $skus[] = 'J_'.$sku;
                // $price = $item->find('.p-price i', 0)->plaintext;
                $name = trim($item->find('.p-name em', 0)->plaintext);
                $shop = $item->find('.p-shop', 0)->getAttribute('data-shop_name');
                $bean->setSku($sku);
                $bean->setName($name);
                // $bean->setPrice($price);
                $bean->setShop($shop);
                $model = new Jd();
                $model->insert($bean);
                unset($bean);
                unset($model);
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
                $model = new Jd();
                $model->update($bean, $price);
                unset($model);
            }
        }
    }
```   

 
    