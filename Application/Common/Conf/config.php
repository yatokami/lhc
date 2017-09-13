<?php
return array(
	'URL_MODEL' =>  2,  
    'DB_TYPE'   => 'mysql', // 数据库类型
    'DB_HOST'   => 'localhost', // 服务器地址
    'DB_NAME'   => 'db_liuhe', // 数据库名
    'DB_PREFIX'=>'',
    'DB_USER'   => 'root',
	'DB_PWD'	=> 'root',
    'DB_PORT'   =>  3306, // 端口

     //'配置项'=>'配置值'
    'APP_SUB_DOMAIN_DEPLOY'   =>    1, // 开启子域名配置
    'APP_SUB_DOMAIN_RULES'    =>    array(
            '192.168.0.10:93'         => 'Home',  // test子域名指向Test模块
            '192.168.0.10:94'         => 'AutoLottery',  // test子域名指向Test模块
    ),
    'APP_DOMAIN_SUFFIX'=>'com',    
);