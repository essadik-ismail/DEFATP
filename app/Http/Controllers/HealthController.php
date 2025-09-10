<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class HealthController extends Controller
{
    /**
     * Basic health check endpoint.
     */
    public function index()
    {
        return response()->json([
            'status' => 'ok',
            'timestamp' => now()->toISOString(),
            'version' => config('app.version', '1.0.0'),
            'environment' => config('app.env')
        ]);
    }

    /**
     * Detailed health check with system status.
     */
    public function detailed()
    {
        $checks = [
            'database' => $this->checkDatabase(),
            'cache' => $this->checkCache(),
            'storage' => $this->checkStorage(),
            'memory' => $this->checkMemory(),
        ];

        $overallStatus = collect($checks)->every(fn($check) => $check['status'] === 'ok') ? 'ok' : 'error';

        return response()->json([
            'status' => $overallStatus,
            'timestamp' => now()->toISOString(),
            'checks' => $checks,
            'system' => [
                'php_version' => PHP_VERSION,
                'laravel_version' => app()->version(),
                'memory_usage' => memory_get_usage(true),
                'memory_peak' => memory_get_peak_usage(true),
            ]
        ], $overallStatus === 'ok' ? 200 : 503);
    }

    /**
     * Check database connectivity.
     */
    private function checkDatabase(): array
    {
        try {
            DB::connection()->getPdo();
            $queryTime = $this->measureQueryTime(function () {
                DB::select('SELECT 1');
            });
            
            return [
                'status' => 'ok',
                'response_time' => $queryTime . 'ms',
                'connection' => 'active'
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'error' => $e->getMessage(),
                'connection' => 'failed'
            ];
        }
    }

    /**
     * Check cache functionality.
     */
    private function checkCache(): array
    {
        try {
            $testKey = 'health_check_' . time();
            $testValue = 'test_value';
            
            Cache::put($testKey, $testValue, 60);
            $retrieved = Cache::get($testKey);
            Cache::forget($testKey);
            
            if ($retrieved === $testValue) {
                return [
                    'status' => 'ok',
                    'driver' => config('cache.default'),
                    'functionality' => 'working'
                ];
            }
            
            return [
                'status' => 'error',
                'driver' => config('cache.default'),
                'functionality' => 'failed'
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'driver' => config('cache.default'),
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Check storage functionality.
     */
    private function checkStorage(): array
    {
        try {
            $testFile = 'health_check_' . time() . '.txt';
            $testContent = 'health check test';
            
            Storage::put($testFile, $testContent);
            $retrieved = Storage::get($testFile);
            Storage::delete($testFile);
            
            if ($retrieved === $testContent) {
                return [
                    'status' => 'ok',
                    'driver' => config('filesystems.default'),
                    'functionality' => 'working'
                ];
            }
            
            return [
                'status' => 'error',
                'driver' => config('filesystems.default'),
                'functionality' => 'failed'
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'driver' => config('filesystems.default'),
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Check memory usage.
     */
    private function checkMemory(): array
    {
        $memoryUsage = memory_get_usage(true);
        $memoryLimit = $this->parseMemoryLimit(ini_get('memory_limit'));
        $memoryPercentage = ($memoryUsage / $memoryLimit) * 100;
        
        $status = $memoryPercentage > 90 ? 'warning' : 'ok';
        
        return [
            'status' => $status,
            'usage' => $this->formatBytes($memoryUsage),
            'limit' => $this->formatBytes($memoryLimit),
            'percentage' => round($memoryPercentage, 2) . '%'
        ];
    }

    /**
     * Measure query execution time.
     */
    private function measureQueryTime(callable $callback): float
    {
        $start = microtime(true);
        $callback();
        return round((microtime(true) - $start) * 1000, 2);
    }

    /**
     * Parse memory limit string to bytes.
     */
    private function parseMemoryLimit(string $limit): int
    {
        $limit = trim($limit);
        $last = strtolower($limit[strlen($limit) - 1]);
        $value = (int) $limit;
        
        switch ($last) {
            case 'g':
                $value *= 1024;
            case 'm':
                $value *= 1024;
            case 'k':
                $value *= 1024;
        }
        
        return $value;
    }

    /**
     * Format bytes to human readable format.
     */
    private function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        
        $bytes /= (1 << (10 * $pow));
        
        return round($bytes, 2) . ' ' . $units[$pow];
    }
}
