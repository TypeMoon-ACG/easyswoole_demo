<?php
return [
    'SERVER_NAME' => "TypeMoon",
    'MAIN_SERVER' => [
        'LISTEN_ADDRESS' => '127.0.6.1',
        'PORT' => 9506,
        'SERVER_TYPE' => EASYSWOOLE_WEB_SERVER, //可选为 EASYSWOOLE_SERVER  EASYSWOOLE_WEB_SERVER EASYSWOOLE_WEB_SOCKET_SERVER,EASYSWOOLE_REDIS_SERVER
        'SOCK_TYPE' => SWOOLE_TCP,
        'RUN_MODEL' => SWOOLE_PROCESS,
        'SETTING' => [
            'worker_num' => 16,
            'reload_async' => true,
            'max_wait_time'=>3,
        ],
        'TASK'=>[
            'workerNum'=>8,
            'maxRunningNum'=>128,
            'timeout'=>15
        ]
    ],
    "PAGE" => [
        "MAX_PAGE" => 100,
        "MAX_LIMIT" => 500,
    ],
    'TEMP_DIR' => "./temp_dev",
    'LOG_DIR' => "./log_dev",
    "MYSQL"=>[
        'host'          => '127.0.0.1',
        'port'          => 22331,
        'user'          => 'printemps',
        'password'      => 'lovelive',
        'database'      => 'easyswoole',
        'timeout'       => 5,
        'charset'       => 'utf8mb4',
    ],
    "REDIS_CONF" => [
        "host" => "127.0.0.1",
        "port" => "6379",
        "auth" => "765876",
        "timeout" => 3
    ],
    "ELASTIC" => [
        "host" => "127.0.1.1",
        "port" => "6001",
    ]
];
