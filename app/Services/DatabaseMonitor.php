<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class DatabaseMonitor
{
    /**
     * Monitor slow queries
     */
    public static function logSlowQuery(string $query, float $executionTime, array $bindings = [])
    {
        $threshold = config('database.slow_query_threshold', 100); // milliseconds
        
        if ($executionTime > $threshold) {
            Log::warning('Slow query detected', [
                'query' => $query,
                'execution_time' => $executionTime . 'ms',
                'bindings' => $bindings,
                'threshold' => $threshold . 'ms'
            ]);
        }
    }

    /**
     * Get database performance statistics
     */
    public static function getPerformanceStats(): array
    {
        return Cache::remember('database_performance_stats', 300, function () {
            try {
                // Get table sizes
                $tableSizes = DB::select("
                    SELECT 
                        table_name,
                        ROUND(((data_length + index_length) / 1024 / 1024), 2) AS 'size_mb'
                    FROM information_schema.tables 
                    WHERE table_schema = DATABASE()
                    ORDER BY (data_length + index_length) DESC
                ");

                // Get index usage statistics
                $indexStats = DB::select("
                    SELECT 
                        table_name,
                        index_name,
                        cardinality
                    FROM information_schema.statistics 
                    WHERE table_schema = DATABASE()
                    AND cardinality > 0
                    ORDER BY cardinality DESC
                ");

                // Get query cache hit rate (if available)
                $cacheStats = DB::select("
                    SHOW STATUS LIKE 'Qcache%'
                ");

                return [
                    'table_sizes' => $tableSizes,
                    'index_stats' => $indexStats,
                    'cache_stats' => $cacheStats,
                    'timestamp' => now()
                ];
            } catch (\Exception $e) {
                Log::error('Failed to get database performance stats: ' . $e->getMessage());
                return [];
            }
        });
    }

    /**
     * Analyze query performance
     */
    public static function analyzeQuery(string $query): array
    {
        try {
            $explain = DB::select("EXPLAIN " . $query);
            
            $analysis = [
                'query' => $query,
                'explain' => $explain,
                'recommendations' => []
            ];

            // Analyze EXPLAIN results and provide recommendations
            foreach ($explain as $row) {
                if ($row->type === 'ALL') {
                    $analysis['recommendations'][] = 'Full table scan detected - consider adding an index';
                }
                if ($row->Extra && strpos($row->Extra, 'Using filesort') !== false) {
                    $analysis['recommendations'][] = 'Filesort detected - consider optimizing ORDER BY clause';
                }
                if ($row->Extra && strpos($row->Extra, 'Using temporary') !== false) {
                    $analysis['recommendations'][] = 'Temporary table detected - consider optimizing GROUP BY or ORDER BY';
                }
            }

            return $analysis;
        } catch (\Exception $e) {
            Log::error('Failed to analyze query: ' . $e->getMessage());
            return ['error' => $e->getMessage()];
        }
    }

    /**
     * Get table statistics
     */
    public static function getTableStats(string $table): array
    {
        return Cache::remember("table_stats_{$table}", 600, function () use ($table) {
            try {
                $stats = DB::select("
                    SELECT 
                        COUNT(*) as total_rows,
                        COUNT(DISTINCT id) as unique_ids
                    FROM {$table}
                ")[0];

                $indexes = DB::select("
                    SHOW INDEX FROM {$table}
                ");

                return [
                    'table' => $table,
                    'total_rows' => $stats->total_rows,
                    'unique_ids' => $stats->unique_ids,
                    'indexes' => $indexes,
                    'timestamp' => now()
                ];
            } catch (\Exception $e) {
                Log::error("Failed to get table stats for {$table}: " . $e->getMessage());
                return ['error' => $e->getMessage()];
            }
        });
    }

    /**
     * Monitor connection pool
     */
    public static function getConnectionStats(): array
    {
        try {
            $connections = DB::select("SHOW STATUS LIKE 'Connections'");
            $maxConnections = DB::select("SHOW VARIABLES LIKE 'max_connections'");
            $threadsConnected = DB::select("SHOW STATUS LIKE 'Threads_connected'");
            $threadsRunning = DB::select("SHOW STATUS LIKE 'Threads_running'");

            return [
                'total_connections' => $connections[0]->Value ?? 0,
                'max_connections' => $maxConnections[0]->Value ?? 0,
                'threads_connected' => $threadsConnected[0]->Value ?? 0,
                'threads_running' => $threadsRunning[0]->Value ?? 0,
                'connection_usage_percent' => $maxConnections[0]->Value > 0 ? 
                    round(($threadsConnected[0]->Value / $maxConnections[0]->Value) * 100, 2) : 0
            ];
        } catch (\Exception $e) {
            Log::error('Failed to get connection stats: ' . $e->getMessage());
            return ['error' => $e->getMessage()];
        }
    }

    /**
     * Get slow query log (if enabled)
     */
    public static function getSlowQueries(int $limit = 10): array
    {
        try {
            // This requires slow query log to be enabled
            $slowQueries = DB::select("
                SELECT 
                    sql_text,
                    exec_count,
                    avg_timer_wait/1000000000 as avg_time_seconds,
                    sum_timer_wait/1000000000 as total_time_seconds
                FROM performance_schema.events_statements_summary_by_digest 
                WHERE avg_timer_wait > 1000000000 
                ORDER BY avg_timer_wait DESC 
                LIMIT ?
            ", [$limit]);

            return $slowQueries;
        } catch (\Exception $e) {
            Log::error('Failed to get slow queries: ' . $e->getMessage());
            return ['error' => $e->getMessage()];
        }
    }

    /**
     * Optimize tables
     */
    public static function optimizeTables(array $tables = []): array
    {
        if (empty($tables)) {
            $tables = ['articles', 'exploitants', 'users', 'essences', 'forets', 'localisations', 'situation_administratives', 'nature_de_coupes'];
        }

        $results = [];
        foreach ($tables as $table) {
            try {
                DB::statement("OPTIMIZE TABLE {$table}");
                $results[$table] = 'Optimized successfully';
            } catch (\Exception $e) {
                $results[$table] = 'Error: ' . $e->getMessage();
            }
        }

        return $results;
    }

    /**
     * Analyze tables
     */
    public static function analyzeTables(array $tables = []): array
    {
        if (empty($tables)) {
            $tables = ['articles', 'exploitants', 'users', 'essences', 'forets', 'localisations', 'situation_administratives', 'nature_de_coupes'];
        }

        $results = [];
        foreach ($tables as $table) {
            try {
                DB::statement("ANALYZE TABLE {$table}");
                $results[$table] = 'Analyzed successfully';
            } catch (\Exception $e) {
                $results[$table] = 'Error: ' . $e->getMessage();
            }
        }

        return $results;
    }
}
