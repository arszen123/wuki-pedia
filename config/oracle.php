<?php

return [
    'oracle' => [
        'driver'         => 'oracle',
//        'tns'            => "(DESCRIPTION = (ADDRESS = (PROTOCOL = TCP)(HOST = 127.0.0.1)(PORT = 6521)) (CONNECT_DATA = (SERVICE_NAME = XE) (SID = XE)))", //'oci8', //,env('DB_TNS', ''),
        'host'           => env('DB_HOST', '127.0.0.1'),
        'port'           => env('DB_PORT', '1521'),
        'database'       => env('DB_DATABASE', ''),
        'username'       => env('DB_USERNAME', ''),
        'password'       => env('DB_PASSWORD', ''),
        'charset'        => env('DB_CHARSET', 'UTF8'), //AL32UTF8
        'prefix'         => env('DB_PREFIX', ''),
        'prefix_schema'  => env('DB_SCHEMA_PREFIX', ''),
        'edition'        => env('DB_EDITION', 'ora$base'),
        'server_version' => env('DB_SERVER_VERSION', '11g'),
    ],
];
