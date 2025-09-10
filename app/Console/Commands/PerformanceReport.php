<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Models\Article;
use App\Models\User;
use App\Models\Exploitant;

class PerformanceReport extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'performance:report {--detailed : Show detailed performance metrics}';

    /**
     * The console command description.
     */
    protected $description = 'Generate a performance report for the application';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('🚀 Generating Performance Report...');
        $this->newLine();

        // Database Performance
        $this->checkDatabasePerformance();
        
        // Cache Performance
        $this->checkCachePerformance();
        
        // Model Performance
        $this->checkModelPerformance();
        
        // Memory Usage
        $this->checkMemoryUsage();
        
        // Query Analysis
        if ($this->option('detailed')) {
            $this->analyzeQueries();
        }

        $this->newLine();
        $this->info('✅ Performance report completed!');
        
        return Command::SUCCESS;
    }

    /**
     * Check database performance.
     */
    private function checkDatabasePerformance(): void
    {
        $this->info('📊 Database Performance:');
        
        $start = microtime(true);
        $articleCount = Article::count();
        $dbTime = round((microtime(true) - $start) * 1000, 2);
        
        $this->line("  • Articles count query: {$dbTime}ms");
        
        $start = microtime(true);
        $articles = Article::with(['foret', 'essence'])->limit(100)->get();
        $relationTime = round((microtime(true) - $start) * 1000, 2);
        
        $this->line("  • Articles with relations: {$relationTime}ms");
        
        // Check for slow queries
        if ($dbTime > 100) {
            $this->warn("  ⚠️  Slow database query detected!");
        } else {
            $this->line("  ✅ Database performance is good");
        }
        
        $this->newLine();
    }

    /**
     * Check cache performance.
     */
    private function checkCachePerformance(): void
    {
        $this->info('💾 Cache Performance:');
        
        $testKey = 'performance_test_' . time();
        $testData = ['test' => 'data', 'timestamp' => now()];
        
        $start = microtime(true);
        Cache::put($testKey, $testData, 60);
        $putTime = round((microtime(true) - $start) * 1000, 2);
        
        $start = microtime(true);
        $retrieved = Cache::get($testKey);
        $getTime = round((microtime(true) - $start) * 1000, 2);
        
        Cache::forget($testKey);
        
        $this->line("  • Cache put operation: {$putTime}ms");
        $this->line("  • Cache get operation: {$getTime}ms");
        
        if ($putTime > 10 || $getTime > 5) {
            $this->warn("  ⚠️  Slow cache operations detected!");
        } else {
            $this->line("  ✅ Cache performance is good");
        }
        
        $this->newLine();
    }

    /**
     * Check model performance.
     */
    private function checkModelPerformance(): void
    {
        $this->info('🏗️  Model Performance:');
        
        $models = [
            'Articles' => Article::class,
            'Users' => User::class,
            'Exploitants' => Exploitant::class,
        ];
        
        foreach ($models as $name => $model) {
            $start = microtime(true);
            $count = $model::count();
            $time = round((microtime(true) - $start) * 1000, 2);
            
            $this->line("  • {$name}: {$count} records, {$time}ms");
        }
        
        $this->newLine();
    }

    /**
     * Check memory usage.
     */
    private function checkMemoryUsage(): void
    {
        $this->info('🧠 Memory Usage:');
        
        $memoryUsage = memory_get_usage(true);
        $memoryPeak = memory_get_peak_usage(true);
        $memoryLimit = $this->parseMemoryLimit(ini_get('memory_limit'));
        
        $usagePercent = round(($memoryUsage / $memoryLimit) * 100, 2);
        $peakPercent = round(($memoryPeak / $memoryLimit) * 100, 2);
        
        $this->line("  • Current usage: " . $this->formatBytes($memoryUsage) . " ({$usagePercent}%)");
        $this->line("  • Peak usage: " . $this->formatBytes($memoryPeak) . " ({$peakPercent}%)");
        $this->line("  • Memory limit: " . $this->formatBytes($memoryLimit));
        
        if ($usagePercent > 80) {
            $this->warn("  ⚠️  High memory usage detected!");
        } else {
            $this->line("  ✅ Memory usage is acceptable");
        }
        
        $this->newLine();
    }

    /**
     * Analyze database queries.
     */
    private function analyzeQueries(): void
    {
        $this->info('🔍 Query Analysis:');
        
        DB::enableQueryLog();
        
        // Simulate some common operations
        Article::with(['foret', 'essence', 'exploitant'])->paginate(20);
        User::with('roles')->get();
        Exploitant::where('is_deleted', false)->count();
        
        $queries = DB::getQueryLog();
        $totalTime = collect($queries)->sum('time');
        $slowQueries = collect($queries)->filter(fn($q) => $q['time'] > 50);
        
        $this->line("  • Total queries executed: " . count($queries));
        $this->line("  • Total query time: " . round($totalTime, 2) . "ms");
        $this->line("  • Average query time: " . round($totalTime / count($queries), 2) . "ms");
        
        if ($slowQueries->isNotEmpty()) {
            $this->warn("  ⚠️  " . $slowQueries->count() . " slow queries detected!");
            
            foreach ($slowQueries as $query) {
                $this->line("    - " . round($query['time'], 2) . "ms: " . substr($query['query'], 0, 100) . "...");
            }
        } else {
            $this->line("  ✅ All queries are performing well");
        }
        
        $this->newLine();
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
