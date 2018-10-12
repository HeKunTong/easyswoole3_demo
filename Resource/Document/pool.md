# 连接池

  注册连接池
  
```

    PoolManager::getInstance()->register(RedisPool::class);

```  

  连接池对象
  
```

    class RedisPool extends AbstractPool
    {
        /**
         * 创建redis连接池对象
         * @return bool
         */
        protected function createObject()
        {
            // TODO: Implement createObject() method.
            if (!extension_loaded('redis')) {
                throw new \BadFunctionCallException('not support: redis');
            }
            $conf = Config::getInstance()->getConf('REDIS');
            $redis = new Redis;
            if ($conf['persistent'] != 'false') {
                $redis->pconnect($conf['host'], $conf['port'], $conf['timeout'], 'persistent_id_'.$conf['select']);
            } else {
                $redis->connect($conf['host'], $conf['port'], $conf['timeout']);
            }
    
            if (!empty($conf['password'])) {
                $redis->auth($conf['password']);
            }
    
            if (0 !== $conf['select']) {
                $redis->select($conf['select']);
            }
    
            return $redis;
        }
    }

``` 

 使用连接池对象
 
```

    PoolManager::getInstance()->getPool(RedisPool::class)->getObj();

```

 回收连接池对象
 
```
    
    PoolManager::getInstance()->getPool(RedisPool::class)->recycleObj($redis);
    
```     