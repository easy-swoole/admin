<?php
return [
    'SERVER_NAME' => "EasySwoole",
    'MAIN_SERVER' => [
        'LISTEN_ADDRESS' => '0.0.0.0',
        'PORT' => 9501,
        'SERVER_TYPE' => EASYSWOOLE_WEB_SERVER, //可选为 EASYSWOOLE_SERVER  EASYSWOOLE_WEB_SERVER EASYSWOOLE_WEB_SOCKET_SERVER,EASYSWOOLE_REDIS_SERVER
        'SOCK_TYPE' => SWOOLE_TCP,
        'RUN_MODEL' => SWOOLE_PROCESS,
        'SETTING' => [
            'worker_num' => 4,
            'reload_async' => true,
            'max_wait_time'=>3,
            'document_root' => EASYSWOOLE_ROOT.'/Static/',
            'enable_static_handler' => true,
        ]
    ],
    'TEMP_DIR' => null,
    'LOG_DIR' => null,
    "MYSQL"=>[
        'host'     => 'rm-uf6ez54y779446i21fo.mysql.rds.aliyuncs.com',
        'user'     => 'root',
        'password' => 'gaobinzhanMysql0604',
        'database' => 'easyswoole',
        'charset'  => 'utf8mb4'
    ]
];
