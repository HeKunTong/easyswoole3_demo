<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 18-9-25
 * Time: 下午4:37
 */

namespace App\Utility\Pools;


class MysqlPoolObject extends \MysqliDb
{
    function __construct($config)
    {
        $host = $config['host'];
        $username = $config['username'];
        $password = $config['password'];
        $db = $config['db'];
        $port = $config['port'];
        $charset = $config['charset'] ?? 'utf-8';
        parent::__construct($host, $username, $password, $db, $port, $charset);
    }

    function gc()
    {
        $this->rollback();
        $this->disconnect();
    }

    function objectRestore()
    {
        $this->rollback();
        $this->reset();
    }
}