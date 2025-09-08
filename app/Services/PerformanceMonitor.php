<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class PerformanceMonitor
{
    private static $startTime;
    private static $startMemory;
    private static $queries = [];

    /**
     * Start monitoring
     */
    public static function start()
    {
        self::$startTime = microtime(true);
        self::$startMemory = memory_get_usage(true);
        
        // Enable query logging
        DB::enableQueryLog();
    }

    /**
     * End monitoring and log results
     */
    public static function end(string $operation = 'Operation')
    {
        $endTime = microtime(true);
        $endMemory = memory_get_usage(true);
        
        $executionTime = round(($endTime - self::$startTime) * 1000, 2); // in milliseconds
        $memoryUsed = round(($endMemory - self::$startMemory) / 1024 / 1024, 2); // in MB
        $queries = DB::getQueryLog();
        
        // Log performance metrics
        Log::info("Performance Monitor - {$operation}", [
            'execution_time_ms' => $executionTime,
            'memory_used_mb' => $memoryUsed,
            'queries_count' => count($queries),
            'queries' => array_map(function($query) {
                return [
                    'sql' => $query['query'],
                    'time' => $query['time'],
                    'bindings' => $query['bindings']
                ];
            }, $queries)
        ]);

        // Log slow queries
        $slowQueries = array_filter($queries, function($query) {
            return $query['time'] > 100; // Queries taking more than 100ms
        });

        if (!empty($slowQueries)) {
            Log::warning("Slow queries detected in {$operation}", [
                'slow_queries' => $slowQueries
            ]);
        }

        // Log high memory usage
        if ($memoryUsed > 50) { // More than 50MB
            Log::warning("High memory usage detected in {$operation}", [
                'memory_used_mb' => $memoryUsed
            ]);
        }

        // Disable query logging
        DB::disableQueryLog();
    }

    /**
     * Monitor a specific operation
     */
    public static function monitor(callable $callback, string $operation = 'Operation')
    {
        self::start();
        
        try {
            $result = $callback();
            self::end($operation);
            return $result;
        } catch (\Exception $e) {
            self::end($operation . ' (Failed)');
            throw $e;
        }
    }

    /**
     * Get current memory usage
     */
    public static function getMemoryUsage(): string
    {
        return round(memory_get_usage(true) / 1024 / 1024, 2) . ' MB';
    }

    /**
     * Get peak memory usage
     */
    public static function getPeakMemoryUsage(): string
    {
        return round(memory_get_peak_usage(true) / 1024 / 1024, 2) . ' MB';
    }

    /**
     * Log database query performance
     */
    public static function logQueryPerformance(string $query, float $time, array $bindings = [])
    {
        if ($time > 100) { // Log queries taking more than 100ms
            Log::warning('Slow query detected', [
                'query' => $query,
                'time' => $time,
                'bindings' => $bindings
            ]);
        }
    }

    /**
     * Get system performance metrics
     */
    public static function getSystemMetrics(): array
    {
        return [
            'memory_usage' => self::getMemoryUsage(),
            'peak_memory' => self::getPeakMemoryUsage(),
            'load_average' => sys_getloadavg(),
            'disk_free' => disk_free_space('/'),
            'disk_total' => disk_total_space('/')
        ];
    }
}
