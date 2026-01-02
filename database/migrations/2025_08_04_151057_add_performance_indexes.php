<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Check if an index exists on a table
     */
    private function indexExists(string $table, string $index): bool
    {
        try {
            $connection = DB::connection();
            $driver = $connection->getDriverName();
            
            if ($driver === 'sqlite') {
                // SQLite syntax
                $indexes = DB::select("PRAGMA index_list({$table})");
                foreach ($indexes as $idx) {
                    if ($idx->name === $index) {
                        return true;
                    }
                }
                return false;
            } else {
                // MySQL syntax
                $indexes = DB::select("SHOW INDEX FROM {$table} WHERE Key_name = ?", [$index]);
                return count($indexes) > 0;
            }
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Articles table indexes
        if (Schema::hasTable('articles')) {
            Schema::table('articles', function (Blueprint $table) {
                // Composite indexes for common queries
                if (!$this->indexExists('articles', 'articles_deleted_created_idx')) {
                    $table->index(['is_deleted', 'created_at'], 'articles_deleted_created_idx');
                }
                if (!$this->indexExists('articles', 'articles_deleted_validated_idx')) {
                    $table->index(['is_deleted', 'is_validated'], 'articles_deleted_validated_idx');
                }
                if (!$this->indexExists('articles', 'articles_annee_deleted_idx')) {
                    $table->index(['annee', 'is_deleted'], 'articles_annee_deleted_idx');
                }
                // Removed foret_id and essence_id indexes as these columns don't exist in articles table
                // The relationships are handled through pivot tables: article_foret and article_essence
                if (!$this->indexExists('articles', 'articles_exploitant_deleted_idx')) {
                    $table->index(['exploitant_id', 'is_deleted'], 'articles_exploitant_deleted_idx');
                }
                // Removed articles_invendu_deleted_idx - invendu column was removed
                if (!$this->indexExists('articles', 'articles_numero_annee_idx')) {
                    $table->index(['numero', 'annee'], 'articles_numero_annee_idx');
                }
                if (!$this->indexExists('articles', 'articles_date_adjudication_idx')) {
                    $table->index(['date_adjudication'], 'articles_date_adjudication_idx');
                }
                // Removed articles_prix_vente_idx - prix_vente column was removed
            });
        }

        // Exploitants table indexes
        Schema::table('exploitants', function (Blueprint $table) {
            if (!$this->indexExists('exploitants', 'exploitants_deleted_created_idx')) {
                $table->index(['is_deleted', 'created_at'], 'exploitants_deleted_created_idx');
            }
            if (!$this->indexExists('exploitants', 'exploitants_categorie_deleted_idx')) {
                $table->index(['categorie', 'is_deleted'], 'exploitants_categorie_deleted_idx');
            }
            if (!$this->indexExists('exploitants', 'exploitants_activite_deleted_idx')) {
                $table->index(['activite', 'is_deleted'], 'exploitants_activite_deleted_idx');
            }
            if (!$this->indexExists('exploitants', 'exploitants_exclusion_deleted_idx')) {
                $table->index(['exclusion', 'is_deleted'], 'exploitants_exclusion_deleted_idx');
            }
            if (!$this->indexExists('exploitants', 'exploitants_n_cin_idx')) {
                $table->index(['n_cin'], 'exploitants_n_cin_idx');
            }
            if (!$this->indexExists('exploitants', 'exploitants_numero_idx')) {
                $table->index(['numero'], 'exploitants_numero_idx');
            }
            if (!$this->indexExists('exploitants', 'exploitants_nom_complet_idx')) {
                $table->index(['nom_complet'], 'exploitants_nom_complet_idx');
            }
            if (!$this->indexExists('exploitants', 'exploitants_raison_sociale_idx')) {
                $table->index(['raison_sociale'], 'exploitants_raison_sociale_idx');
            }
            // Removed adjudicataire index as column doesn't exist
        });

        // Users table indexes
        Schema::table('users', function (Blueprint $table) {
            if (!$this->indexExists('users', 'users_ppr_idx')) {
                $table->index(['ppr'], 'users_ppr_idx');
            }
            if (!$this->indexExists('users', 'users_name_idx')) {
                $table->index(['name'], 'users_name_idx');
            }
            if (!$this->indexExists('users', 'users_deleted_created_idx')) {
                $table->index(['is_deleted', 'created_at'], 'users_deleted_created_idx');
            }
        });

        // Essences table indexes
        Schema::table('essences', function (Blueprint $table) {
            if (!$this->indexExists('essences', 'essences_deleted_essence_idx')) {
                $table->index(['is_deleted', 'essence'], 'essences_deleted_essence_idx');
            }
            if (!$this->indexExists('essences', 'essences_essence_idx')) {
                $table->index(['essence'], 'essences_essence_idx');
            }
        });

        // Forets table indexes
        Schema::table('forets', function (Blueprint $table) {
            if (!$this->indexExists('forets', 'forets_deleted_foret_idx')) {
                $table->index(['is_deleted', 'foret'], 'forets_deleted_foret_idx');
            }
            if (!$this->indexExists('forets', 'forets_foret_idx')) {
                $table->index(['foret'], 'forets_foret_idx');
            }
        });

        // Activity logs table indexes (will be added after table creation)
        // Note: These indexes will be added in a separate migration after activity_logs table is created
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Articles table indexes
        if (Schema::hasTable('articles')) {
            Schema::table('articles', function (Blueprint $table) {
                try {
                    $table->dropIndex('articles_deleted_created_idx');
                } catch (\Exception $e) {}
                try {
                    $table->dropIndex('articles_deleted_validated_idx');
                } catch (\Exception $e) {}
                try {
                    $table->dropIndex('articles_annee_deleted_idx');
                } catch (\Exception $e) {}
                // Removed foret_id and essence_id indexes as these columns don't exist in articles table
                try {
                    $table->dropIndex('articles_exploitant_deleted_idx');
                } catch (\Exception $e) {}
                // Removed articles_invendu_deleted_idx - invendu column was removed
                try {
                    $table->dropIndex('articles_numero_annee_idx');
                } catch (\Exception $e) {}
                try {
                    $table->dropIndex('articles_date_adjudication_idx');
                } catch (\Exception $e) {}
                // Removed articles_prix_vente_idx - prix_vente column was removed
            });
        }

        // Exploitants table indexes
        Schema::table('exploitants', function (Blueprint $table) {
            $table->dropIndex('exploitants_deleted_created_idx');
            $table->dropIndex('exploitants_categorie_deleted_idx');
            $table->dropIndex('exploitants_activite_deleted_idx');
            $table->dropIndex('exploitants_exclusion_deleted_idx');
            $table->dropIndex('exploitants_n_cin_idx');
            $table->dropIndex('exploitants_numero_idx');
            $table->dropIndex('exploitants_nom_complet_idx');
            $table->dropIndex('exploitants_raison_sociale_idx');
            // Removed adjudicataire index as column doesn't exist
        });

        // Users table indexes
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('users_ppr_idx');
            $table->dropIndex('users_name_idx');
            $table->dropIndex('users_deleted_created_idx');
        });

        // Essences table indexes
        Schema::table('essences', function (Blueprint $table) {
            $table->dropIndex('essences_deleted_essence_idx');
            $table->dropIndex('essences_essence_idx');
        });

        // Forets table indexes
        Schema::table('forets', function (Blueprint $table) {
            $table->dropIndex('forets_deleted_foret_idx');
            $table->dropIndex('forets_foret_idx');
        });

        // Activity logs table indexes (removed - will be handled separately)
    }
};
