<?php
$db['master'] = array(
    'driver' => 'mysql',
    'host' => "192.168.20.183",
    'port' => 3306,
    'dbms' => 'mysql',
    'engine' => 'Innodb',
    'username' => "root",
    'password' => "root",
    'database' => "test",
    'charset' => "utf8",
    'setname' => true,
    'persistent' => false, //MySQL长连接
    'use_proxy' => false,  //启动读写分离Proxy
    'slaves' => array(
        array('host' => '127.0.0.1', 'port' => '3307', 'weight' => 100,),
        array('host' => '127.0.0.1', 'port' => '3308', 'weight' => 99,),
        array('host' => '127.0.0.1', 'port' => '3309', 'weight' => 98,),
    ),
);

return $db;