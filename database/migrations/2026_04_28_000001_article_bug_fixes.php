<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            // Bug 10 – add diagonal limit fields (already added by partial run, guarded)
            if (!Schema::hasColumn('articles', 'limite_se')) {
                $table->string('limite_se')->nullable()->after('limite_ouest');
            }
            if (!Schema::hasColumn('articles', 'limite_so')) {
                $table->string('limite_so')->nullable()->after('limite_se');
            }
            if (!Schema::hasColumn('articles', 'limite_ne')) {
                $table->string('limite_ne')->nullable()->after('limite_so');
            }
            if (!Schema::hasColumn('articles', 'limite_no')) {
                $table->string('limite_no')->nullable()->after('limite_ne');
            }

            // Bug 14 – date de livraison bois de chauffage
            if (!Schema::hasColumn('articles', 'date_livraison_bois_chauffage')) {
                $table->date('date_livraison_bois_chauffage')->nullable()->after('bois_chauffage_destination');
            }

            // Bug 16 – default status to article_cree
            if (Schema::hasColumn('articles', 'current_step')) {
                $table->string('current_step', 50)->nullable()->default('article_cree')->change();
            }

            // Bug 15 – unique article number per cession (groupe_cession_id is the available FK)
            if (!$this->indexExists('articles', 'articles_numero_cession_unique')) {
                $table->unique(['numero', 'groupe_cession_id'], 'articles_numero_cession_unique');
            }
        });
    }

    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            foreach (['limite_se', 'limite_so', 'limite_ne', 'limite_no', 'date_livraison_bois_chauffage'] as $col) {
                if (Schema::hasColumn('articles', $col)) {
                    $table->dropColumn($col);
                }
            }
            if ($this->indexExists('articles', 'articles_numero_cession_unique')) {
                $table->dropUnique('articles_numero_cession_unique');
            }
        });
    }

    private function indexExists(string $table, string $index): bool
    {
        $indexes = \Illuminate\Support\Facades\DB::select(
            "SHOW INDEX FROM `{$table}` WHERE Key_name = ?",
            [$index]
        );
        return !empty($indexes);
    }
};
