<?php

return [
    'host'     => getenv('DB_HOST') ?: 'localhost',
    'db_name'  => getenv('DB_NAME') ?: 'azmigrantat',
    'username' => getenv('DB_USER') ?: 'root',
    'password' => getenv('DB_PASS') ?: '',
    'port' => getenv('DB_PORT') ?: '8889',
    'charset'  => 'utf8mb4'
];