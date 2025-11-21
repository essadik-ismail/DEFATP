<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Article;
use App\Models\LegacyArticle;
use Illuminate\Support\Facades\DB;

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
    protected $description = 'Delete all records from articles and legacy_articles tables';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting deletion of all articles...');

        // Count records before deletion
        $articlesCount = Article::withTrashed()->count();
        $legacyArticlesCount = LegacyArticle::count();

        $this->info("Found {$articlesCount} articles and {$legacyArticlesCount} legacy articles.");

        if ($articlesCount === 0 && $legacyArticlesCount === 0) {
            $this->info('No records to delete.');
            return Command::SUCCESS;
        }

        // Ask for confirmation unless --force is used
        if (!$this->option('force')) {
            if (!$this->confirm('Are you sure you want to delete all articles and legacy articles? This action cannot be undone.')) {
                $this->info('Deletion cancelled.');
                return Command::SUCCESS;
            }
        }

        try {
            // Disable foreign key checks temporarily
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');

            // Delete all pivot table records first
            $this->info('Deleting pivot table records...');
            DB::table('article_essence')->truncate();
            DB::table('article_foret')->truncate();
            DB::table('article_situation_administrative')->truncate();
            DB::table('article_nature_de_coupe')->truncate();
            DB::table('article_localisation')->truncate();
            $this->info('Pivot tables cleared.');

            // Delete all articles (including soft deleted)
            DB::table('articles')->truncate();
            $this->info("Deleted all articles from 'articles' table.");

            // Delete all legacy articles
            DB::table('legacy_articles')->truncate();
            $this->info("Deleted all articles from 'legacy_articles' table.");

            // Re-enable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            $this->info('All articles have been successfully deleted!');
            return Command::SUCCESS;
        } catch (\Exception $e) {
            // Re-enable foreign key checks in case of error
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            $this->error('Error deleting articles: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
