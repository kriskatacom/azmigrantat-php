<?php

return [
    'host'     => getenv('DB_HOST') ?: 'localhost',
    'db_name'  => getenv('DB_NAME') ?: 'az_migrantat',
    'username' => getenv('DB_USER') ?: 'root',
    'password' => getenv('DB_PASS') ?: '',
    'charset'  => 'utf8mb4'
];
