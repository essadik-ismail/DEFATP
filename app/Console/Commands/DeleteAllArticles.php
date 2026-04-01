<?php

namespace App\Console\Commands;

use App\Models\Article;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DeleteAllArticles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'articles:delete-all {--force : Force deletion without confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete all records from the current articles module tables';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting deletion of all articles...');

        $articlesCount = Article::withTrashed()->count();

        $this->info("Found {$articlesCount} articles.");

        if ($articlesCount === 0) {
            $this->info('No records to delete.');

            return Command::SUCCESS;
        }

        if (! $this->option('force') && ! $this->confirm('Are you sure you want to delete all articles? This action cannot be undone.')) {
            $this->info('Deletion cancelled.');

            return Command::SUCCESS;
        }

        $tablesToClear = [
            'article_essence',
            'article_foret',
            'article_nature_de_coupe',
            'article_province',
            'article_commune',
            'article_parcelle',
            'depot_article',
            'locations',
            'articles',
        ];

        try {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');

            foreach ($tablesToClear as $table) {
                if (! Schema::hasTable($table)) {
                    continue;
                }

                DB::table($table)->truncate();
                $this->info("Cleared '{$table}'.");
            }

            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            $this->info('All article data has been successfully deleted.');

            return Command::SUCCESS;
        } catch (\Throwable $e) {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            $this->error('Error deleting articles: ' . $e->getMessage());

            return Command::FAILURE;
        }
    }
}
