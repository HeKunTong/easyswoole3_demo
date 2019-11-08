# redis连接池

> redis 连接信息

```php
'REDIS' => [
    'host' => '127.0.0.1',
    'port' => 6379,
    'password' => '',
]
```

> 注册redis连接池
  
在EasySwooleEvent.php的initialize方法注册redis连接池
```php
Manager::getInstance()->register(new RedisPool(new \EasySwoole\Pool\Config()), 'redis');
```  

> redis连接池类

```php
<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 18-9-25
 * Time: 下午4:41
 */

namespace App\Utility\Pool;

use EasySwoole\EasySwoole\Config;
use EasySwoole\Pool\AbstractPool;

class RedisPool extends AbstractPool
{
    /**
     * 创建redis连接池对象
     */
    protected function createObject()
    {
        // TODO: Implement createObject() method.
        if (!extension_loaded('redis')) {
            throw new \BadFunctionCallException('not support: redis');
        }
        $conf = Config::getInstance()->getConf('REDIS');
        $redis = new RedisObject();
        $connected = $redis->connect($conf['host'], $conf['port']);
        if($connected){
            if(!empty($conf['auth'])){
                $redis->auth($conf['auth']);
            }
            return $redis;
        }else{
            return null;
        }
    }
}
``` 

> redis连接池操作类

```php
<?php
/**
 * Created by PhpStorm.
 * User: yf
 * Date: 2018/10/26
 * Time: 7:23 PM
 */

namespace App\Utility\Pool;


use EasySwoole\Pool\ObjectInterface;
use Swoole\Coroutine\Redis;

class RedisObject extends Redis implements ObjectInterface
{
    function gc()
    {
        // TODO: Implement gc() method.
        $this->close();
    }

    function objectRestore()
    {
        // TODO: Implement objectRestore() method.
    }

    function beforeUse(): bool
    {
        // TODO: Implement beforeUse() method.
        return true;
    }
}
```

> 从连接池获取redis对象

```php
$redis = \EasySwoole\Pool\Manager::getInstance()->get('redis')->getObj();
```

> 回收连接池对象

```php
\EasySwoole\Pool\Manager::getInstance()->get('redis')->recycleObj($redis);    
```     