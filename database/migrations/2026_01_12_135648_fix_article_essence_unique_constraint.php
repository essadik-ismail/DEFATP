<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check what indexes exist
        $indexes = DB::select("SHOW INDEX FROM article_essence");
        
        // Check if ae_unique exists
        $hasAeUnique = collect($indexes)->contains(function($index) {
            return $index->Key_name === 'ae_unique';
        });
        
        // Check if article_essence_unique already exists
        $hasCorrectUnique = collect($indexes)->contains(function($index) {
            return $index->Key_name === 'article_essence_unique';
        });
        
        if ($hasAeUnique) {
            // Find and drop foreign keys that reference this index
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
                    // Foreign key might not exist or might not be related to ae_unique
                }
            }
            
            // Now drop the ae_unique index
            try {
                DB::statement('ALTER TABLE article_essence DROP INDEX ae_unique');
            } catch (\Exception $e) {
                // Index doesn't exist or can't be dropped
            }
            
            // Recreate the foreign keys
            Schema::table('article_essence', function (Blueprint $table) {
                $table->foreign('article_id')->references('id')->on('articles')->onDelete('cascade');
                $table->foreign('essence_id')->references('id')->on('essences')->onDelete('cascade');
                $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            });
        }
        
        if (!$hasCorrectUnique) {
            // Add the correct unique constraint
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
            // Drop the correct unique constraint
            try {
                $table->dropUnique('article_essence_unique');
            } catch (\Exception $e) {
                // Constraint doesn't exist, ignore
            }
        });
    }
};
