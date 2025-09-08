<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Optimized Database Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains optimized database configuration for better performance
    | and security.
    |
    */

    'mysql_optimized' => [
        'driver' => 'mysql',
        'url' => env('DATABASE_URL'),
        'host' => env('DB_HOST', '127.0.0.1'),
        'port' => env('DB_PORT', '3306'),
        'database' => env('DB_DATABASE', 'forge'),
        'username' => env('DB_USERNAME', 'forge'),
        'password' => env('DB_PASSWORD', ''),
        'unix_socket' => env('DB_SOCKET', ''),
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
        'prefix' => '',
        'prefix_indexes' => true,
        'strict' => true,
        'engine' => 'InnoDB',
        'options' => extension_loaded('pdo_mysql') ? array_filter([
            PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            PDO::ATTR_PERSISTENT => true, // Enable persistent connections
            PDO::ATTR_EMULATE_PREPARES => false, // Use native prepared statements
            PDO::ATTR_STRINGIFY_FETCHES => false, // Don't convert to strings
            PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true, // Use buffered queries
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Default fetch mode
        ]) : [],
        
        // Connection pooling settings
        'pool' => [
            'min_connections' => env('DB_POOL_MIN', 1),
            'max_connections' => env('DB_POOL_MAX', 10),
            'connect_timeout' => env('DB_CONNECT_TIMEOUT', 10),
            'wait_timeout' => env('DB_WAIT_TIMEOUT', 60),
            'heartbeat' => env('DB_HEARTBEAT', -1),
            'max_idle_time' => env('DB_MAX_IDLE_TIME', 60),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Query Optimization Settings
    |--------------------------------------------------------------------------
    */

    'query_optimization' => [
        'enable_query_log' => env('DB_QUERY_LOG', false),
        'slow_query_threshold' => env('DB_SLOW_QUERY_THRESHOLD', 100), // milliseconds
        'max_query_time' => env('DB_MAX_QUERY_TIME', 30), // seconds
        'enable_query_cache' => env('DB_QUERY_CACHE', true),
        'query_cache_size' => env('DB_QUERY_CACHE_SIZE', 64), // MB
    ],

    /*
    |--------------------------------------------------------------------------
    | Connection Monitoring
    |--------------------------------------------------------------------------
    */

    'monitoring' => [
        'enable_connection_monitoring' => env('DB_MONITOR_CONNECTIONS', true),
        'log_connection_errors' => env('DB_LOG_CONNECTION_ERRORS', true),
        'max_connection_errors' => env('DB_MAX_CONNECTION_ERRORS', 5),
        'connection_retry_delay' => env('DB_CONNECTION_RETRY_DELAY', 5), // seconds
    ],

    /*
    |--------------------------------------------------------------------------
    | Security Settings
    |--------------------------------------------------------------------------
    */

    'security' => [
        'enable_ssl' => env('DB_SSL_ENABLED', false),
        'ssl_cert' => env('DB_SSL_CERT'),
        'ssl_key' => env('DB_SSL_KEY'),
        'ssl_ca' => env('DB_SSL_CA'),
        'ssl_verify' => env('DB_SSL_VERIFY', true),
        'enable_encryption' => env('DB_ENCRYPTION_ENABLED', false),
    ],
];
