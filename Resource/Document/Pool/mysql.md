# mysql连接池

> mysql 连接信息

```dotenv
################ MYSQL CONFIG ##################

MYSQL.host = 192.168.75.1
MYSQL.port = 3306
MYSQL.user = root
MYSQL.password = root
MYSQL.database = cry
MYSQL.timeout = 5
MYSQL.charset = utf8mb4
MYSQL.POOL_MAX_NUM = 2
MYSQL.POOL_TIME_OUT = 0.1
```

> 注册mysql连接池
  
在EasySwooleEvent.php的initialize方法注册mysql连接池
```php
PoolManager::getInstance()->register(MysqlPool::class, Config::getInstance()->getConf('MYSQL.POOL_MAX_NUM'));
```  

> mysql连接池类

```php
<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 18-9-25
 * Time: 下午4:37
 */

namespace App\Utility\Pool;


use EasySwoole\Component\Pool\AbstractPool;
use EasySwoole\EasySwoole\Config;

class MysqlPool extends AbstractPool
{

    protected function createObject()
    {
        // TODO: Implement createObject() method.
        $conf = Config::getInstance()->getConf('MYSQL');
        $dbConf = new \EasySwoole\Mysqli\Config($conf);
        return new MysqlPoolObject($dbConf);
    }
}
``` 

> mysql连接池操作类

```php
<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 18-9-25
 * Time: 下午4:37
 */

namespace App\Utility\Pool;


use EasySwoole\Component\Pool\PoolObjectInterface;
use EasySwoole\Mysqli\Mysqli;

class MysqlPoolObject extends Mysqli implements PoolObjectInterface
{

    function gc()
    {
        $this->resetDbStatus();
        $this->getMysqlClient()->close();
    }

    function objectRestore()
    {
        $this->resetDbStatus();
    }

    function beforeUse(): bool
    {
        // TODO: Implement beforeUse() method.
        //使用前调用,当返回true，表示该对象可用。返回false，该对象失效，需要回收
        //根据个人逻辑修改,只要做好了断线处理逻辑,就可直接返回true
        return $this->getMysqlClient()->connected;
    }
}
```

> 从连接池获取redis对象

```php
$db = PoolManager::getInstance()->getPool(MysqlPool::class)->getObj(Config::getInstance()->getConf('MYSQL.POOL_TIME_OUT'));
```

> 回收连接池对象

```php
PoolManager::getInstance()->getPool(MysqlPool::class)->recycleObj($db);   
```     