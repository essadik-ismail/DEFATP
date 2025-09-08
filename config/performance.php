<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Performance Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains performance-related configuration options for the
    | application to optimize speed and resource usage.
    |
    */

    'cache' => [
        'default_ttl' => env('CACHE_DEFAULT_TTL', 300), // 5 minutes
        'dashboard_ttl' => env('CACHE_DASHBOARD_TTL', 300), // 5 minutes
        'reports_ttl' => env('CACHE_REPORTS_TTL', 600), // 10 minutes
        'user_data_ttl' => env('CACHE_USER_DATA_TTL', 1800), // 30 minutes
    ],

    'database' => [
        'query_log' => env('DB_QUERY_LOG', false),
        'slow_query_threshold' => env('DB_SLOW_QUERY_THRESHOLD', 100), // milliseconds
        'connection_pool_size' => env('DB_CONNECTION_POOL_SIZE', 10),
    ],

    'pagination' => [
        'default_per_page' => env('PAGINATION_DEFAULT_PER_PAGE', 15),
        'max_per_page' => env('PAGINATION_MAX_PER_PAGE', 100),
        'allowed_per_page' => [10, 15, 25, 50, 100],
    ],

    'rate_limiting' => [
        'login_attempts' => env('RATE_LIMIT_LOGIN_ATTEMPTS', 5),
        'api_requests' => env('RATE_LIMIT_API_REQUESTS', 60),
        'form_submissions' => env('RATE_LIMIT_FORM_SUBMISSIONS', 10),
    ],

    'compression' => [
        'enable_gzip' => env('COMPRESSION_ENABLE_GZIP', true),
        'enable_brotli' => env('COMPRESSION_ENABLE_BROTLI', true),
        'minify_css' => env('COMPRESSION_MINIFY_CSS', true),
        'minify_js' => env('COMPRESSION_MINIFY_JS', true),
    ],

    'monitoring' => [
        'enable_performance_logging' => env('PERFORMANCE_LOGGING', true),
        'log_slow_queries' => env('LOG_SLOW_QUERIES', true),
        'log_memory_usage' => env('LOG_MEMORY_USAGE', true),
        'memory_threshold_mb' => env('MEMORY_THRESHOLD_MB', 50),
    ],

    'optimization' => [
        'lazy_load_images' => env('LAZY_LOAD_IMAGES', true),
        'defer_javascript' => env('DEFER_JAVASCRIPT', true),
        'preload_critical_css' => env('PRELOAD_CRITICAL_CSS', true),
        'enable_service_worker' => env('ENABLE_SERVICE_WORKER', false),
    ],
];
