<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\DatabaseMonitor;
use App\Services\QueryOptimizer;

class OptimizeDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:optimize 
                            {--analyze : Analyze tables for better query planning}
                            {--optimize : Optimize tables to reclaim unused space}
                            {--stats : Show database performance statistics}
                            {--monitor : Monitor slow queries}
                            {--cache-clear : Clear query cache}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Optimize database performance and monitor queries';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔧 Database Optimization Tool');
        $this->line('');

        if ($this->option('stats')) {
            $this->showPerformanceStats();
        }

        if ($this->option('analyze')) {
            $this->analyzeTables();
        }

        if ($this->option('optimize')) {
            $this->optimizeTables();
        }

        if ($this->option('monitor')) {
            $this->monitorSlowQueries();
        }

        if ($this->option('cache-clear')) {
            $this->clearCaches();
        }

        if (!$this->option('stats') && !$this->option('analyze') && !$this->option('optimize') && !$this->option('monitor') && !$this->option('cache-clear')) {
            $this->showHelp();
        }

        $this->line('');
        $this->info('✅ Database optimization completed!');
    }

    /**
     * Show performance statistics
     */
    private function showPerformanceStats()
    {
        $this->info('📊 Database Performance Statistics');
        $this->line('');

        $stats = DatabaseMonitor::getPerformanceStats();
        
        if (empty($stats)) {
            $this->error('Failed to retrieve performance statistics');
            return;
        }

        // Table sizes
        $this->line('📋 Table Sizes:');
        $headers = ['Table', 'Size (MB)'];
        $rows = [];
        
        foreach ($stats['table_sizes'] as $table) {
            $rows[] = [$table->table_name, $table->size_mb];
        }
        
        $this->table($headers, $rows);

        // Connection stats
        $this->line('');
        $this->line('🔗 Connection Statistics:');
        $connectionStats = DatabaseMonitor::getConnectionStats();
        
        if (!isset($connectionStats['error'])) {
            $this->line("Total Connections: {$connectionStats['total_connections']}");
            $this->line("Max Connections: {$connectionStats['max_connections']}");
            $this->line("Threads Connected: {$connectionStats['threads_connected']}");
            $this->line("Threads Running: {$connectionStats['threads_running']}");
            $this->line("Connection Usage: {$connectionStats['connection_usage_percent']}%");
        }

        // Index statistics
        $this->line('');
        $this->line('📈 Top Indexes by Cardinality:');
        $indexHeaders = ['Table', 'Index', 'Cardinality'];
        $indexRows = [];
        
        foreach (array_slice($stats['index_stats'], 0, 10) as $index) {
            $indexRows[] = [$index->table_name, $index->index_name, $index->cardinality];
        }
        
        $this->table($indexHeaders, $indexRows);
    }

    /**
     * Analyze tables
     */
    private function analyzeTables()
    {
        $this->info('🔍 Analyzing Tables...');
        
        $tables = ['articles', 'exploitants', 'users', 'essences', 'forets', 'localisations', 'situation_administratives', 'nature_de_coupes'];
        
        $results = DatabaseMonitor::analyzeTables($tables);
        
        foreach ($results as $table => $result) {
            if (strpos($result, 'Error') === false) {
                $this->line("✅ {$table}: {$result}");
            } else {
                $this->error("❌ {$table}: {$result}");
            }
        }
    }

    /**
     * Optimize tables
     */
    private function optimizeTables()
    {
        $this->info('⚡ Optimizing Tables...');
        
        $tables = ['articles', 'exploitants', 'users', 'essences', 'forets', 'localisations', 'situation_administratives', 'nature_de_coupes'];
        
        $results = DatabaseMonitor::optimizeTables($tables);
        
        foreach ($results as $table => $result) {
            if (strpos($result, 'Error') === false) {
                $this->line("✅ {$table}: {$result}");
            } else {
                $this->error("❌ {$table}: {$result}");
            }
        }
    }

    /**
     * Monitor slow queries
     */
    private function monitorSlowQueries()
    {
        $this->info('🐌 Monitoring Slow Queries...');
        
        $slowQueries = DatabaseMonitor::getSlowQueries(5);
        
        if (isset($slowQueries['error'])) {
            $this->error("Failed to get slow queries: {$slowQueries['error']}");
            return;
        }

        if (empty($slowQueries)) {
            $this->line('No slow queries found! 🎉');
            return;
        }

        $headers = ['Query', 'Exec Count', 'Avg Time (s)', 'Total Time (s)'];
        $rows = [];
        
        foreach ($slowQueries as $query) {
            $rows[] = [
                substr($query->sql_text, 0, 50) . '...',
                $query->exec_count,
                round($query->avg_time_seconds, 3),
                round($query->total_time_seconds, 3)
            ];
        }
        
        $this->table($headers, $rows);
    }

    /**
     * Clear caches
     */
    private function clearCaches()
    {
        $this->info('🧹 Clearing Caches...');
        
        QueryOptimizer::clearAllCaches();
        \Cache::flush();
        
        $this->line('✅ All caches cleared successfully');
    }

    /**
     * Show help information
     */
    private function showHelp()
    {
        $this->line('Available options:');
        $this->line('  --stats      Show database performance statistics');
        $this->line('  --analyze    Analyze tables for better query planning');
        $this->line('  --optimize   Optimize tables to reclaim unused space');
        $this->line('  --monitor    Monitor slow queries');
        $this->line('  --cache-clear Clear all caches');
        $this->line('');
        $this->line('Examples:');
        $this->line('  php artisan db:optimize --stats');
        $this->line('  php artisan db:optimize --analyze --optimize');
        $this->line('  php artisan db:optimize --monitor --cache-clear');
    }
}
