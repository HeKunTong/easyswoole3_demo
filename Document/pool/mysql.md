# mysql连接池

> mysql 连接信息

```php
'MYSQL' => [
    'host' => '192.168.75.1',
    'port' => 3306,
    'user' => 'root',
    'password' => 'root',
    'database' => 'cry',
    'timeout' => 5,
    'charset' => 'utf8mb4',
]
```

> 注册mysql连接池
  
在EasySwooleEvent.php的initialize方法注册mysql连接池
```php
Manager::getInstance()->register(new MysqlPool(new \EasySwoole\Pool\Config()), 'mysql');
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


use EasySwoole\EasySwoole\Config;
use EasySwoole\Pool\AbstractPool;

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

use EasySwoole\Mysqli\Mysqli;
use EasySwoole\Pool\ObjectInterface;

class MysqlPoolObject extends Mysqli implements ObjectInterface
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
$db = \EasySwoole\Pool\Manager::getInstance()->get('mysql')->getObj();
```

> 回收连接池对象

```php
\EasySwoole\Pool\Manager::getInstance()->get('mysql')->recycleObj($db);
```     