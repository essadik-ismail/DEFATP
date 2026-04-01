<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('article_essence')) {
            return;
        }

        if (DB::getDriverName() === 'sqlite') {
            // Fresh SQLite schemas already create the correct unique index.
            return;
        }

        $indexes = DB::select('SHOW INDEX FROM article_essence');

        $hasAeUnique = collect($indexes)->contains(function ($index) {
            return $index->Key_name === 'ae_unique';
        });

        $hasCorrectUnique = collect($indexes)->contains(function ($index) {
            return $index->Key_name === 'article_essence_unique';
        });

        if ($hasAeUnique) {
            $foreignKeys = DB::select("
                SELECT CONSTRAINT_NAME
                FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
                WHERE TABLE_NAME = 'article_essence'
                AND TABLE_SCHEMA = DATABASE()
                AND REFERENCED_TABLE_NAME IS NOT NULL
            ");

            foreach ($foreignKeys as $fk) {
                try {
                    DB::statement("ALTER TABLE article_essence DROP FOREIGN KEY `{$fk->CONSTRAINT_NAME}`");
                } catch (\Exception $e) {
                    // Ignore missing or unrelated constraints.
                }
            }

            try {
                DB::statement('ALTER TABLE article_essence DROP INDEX ae_unique');
            } catch (\Exception $e) {
                // Ignore missing legacy index.
            }

            Schema::table('article_essence', function (Blueprint $table) {
                $table->foreign('article_id')->references('id')->on('articles')->onDelete('cascade');
                $table->foreign('essence_id')->references('id')->on('essences')->onDelete('cascade');
                $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            });
        }

        if (!$hasCorrectUnique) {
            Schema::table('article_essence', function (Blueprint $table) {
                $table->unique(['article_id', 'essence_id', 'product_id'], 'article_essence_unique');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('article_essence', function (Blueprint $table) {
            try {
                $table->dropUnique('article_essence_unique');
            } catch (\Exception $e) {
                // Ignore missing constraint.
            }
        });
    }
};
