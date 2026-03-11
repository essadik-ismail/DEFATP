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
        // Articles table - additional comprehensive indexes
        if (Schema::hasTable('articles')) {
            Schema::table('articles', function (Blueprint $table) {
                // Composite indexes for common query patterns
                if (!$this->indexExists('articles', 'articles_year_status_idx') && Schema::hasColumn('articles', 'is_validated')) {
                    $table->index(['annee', 'is_validated'], 'articles_year_status_idx');
                }
                // Removed foret_id and essence_id indexes - these are many-to-many relationships via pivot tables
                // Removed articles_price_status_idx - prix_vente and invendu columns were removed
                if (!$this->indexExists('articles', 'articles_type_year_idx') && Schema::hasColumn('articles', 'type')) {
                    $table->index(['type', 'annee'], 'articles_type_year_idx');
                }
                if (!$this->indexExists('articles', 'articles_exploitant_created_idx') && Schema::hasColumn('articles', 'exploitant_id')) {
                    $table->index(['exploitant_id', 'created_at'], 'articles_exploitant_created_idx');
                }
                // Date-based indexes for reporting
                if (!$this->indexExists('articles', 'articles_created_updated_idx')) {
                    $table->index(['created_at', 'updated_at'], 'articles_created_updated_idx');
                }
            });
        }

        // Exploitants table - additional comprehensive indexes
        Schema::table('exploitants', function (Blueprint $table) {
            // Composite indexes for filtering and reporting
            if (!$this->indexExists('exploitants', 'exploitants_categorie_activite_idx')) {
                $table->index(['categorie', 'activite'], 'exploitants_categorie_activite_idx');
            }
            if (!$this->indexExists('exploitants', 'exploitants_exclusion_created_idx')) {
                $table->index(['exclusion', 'created_at'], 'exploitants_exclusion_created_idx');
            }
            // Removed adjudicataire index as column doesn't exist
            if (!$this->indexExists('exploitants', 'exploitants_qualification_idx')) {
                $table->index(['qualification_rc'], 'exploitants_qualification_idx');
            }
            if (!$this->indexExists('exploitants', 'exploitants_date_obtention_idx')) {
                $table->index(['date_obtention'], 'exploitants_date_obtention_idx');
            }
            if (!$this->indexExists('exploitants', 'exploitants_duree_validite_idx')) {
                $table->index(['duree_validite'], 'exploitants_duree_validite_idx');
            }
        });

        // Users table - additional indexes
        Schema::table('users', function (Blueprint $table) {
            if (!$this->indexExists('users', 'users_created_updated_idx')) {
                $table->index(['created_at', 'updated_at'], 'users_created_updated_idx');
            }
            if (!$this->indexExists('users', 'users_deleted_ppr_idx')) {
                $table->index(['is_deleted', 'ppr'], 'users_deleted_ppr_idx');
            }
        });

        // Essences table - additional indexes
        Schema::table('essences', function (Blueprint $table) {
            if (!$this->indexExists('essences', 'essences_deleted_created_idx')) {
                $table->index(['is_deleted', 'created_at'], 'essences_deleted_created_idx');
            }
            if (!$this->indexExists('essences', 'essences_essence_deleted_idx')) {
                $table->index(['essence', 'is_deleted'], 'essences_essence_deleted_idx');
            }
        });

        // Forets table - additional indexes
        Schema::table('forets', function (Blueprint $table) {
            if (!$this->indexExists('forets', 'forets_deleted_created_idx')) {
                $table->index(['is_deleted', 'created_at'], 'forets_deleted_created_idx');
            }
            if (!$this->indexExists('forets', 'forets_foret_deleted_idx')) {
                $table->index(['foret', 'is_deleted'], 'forets_foret_deleted_idx');
            }
        });

        // Situation Administratives table - additional indexes
        Schema::table('situation_administratives', function (Blueprint $table) {
            if (!$this->indexExists('situation_administratives', 'situation_admin_commune_idx')) {
                $table->index(['commune'], 'situation_admin_commune_idx');
            }
            if (!$this->indexExists('situation_administratives', 'situation_admin_province_idx')) {
                $table->index(['province'], 'situation_admin_province_idx');
            }
            if (!$this->indexExists('situation_administratives', 'situation_admin_deleted_created_idx')) {
                $table->index(['is_deleted', 'created_at'], 'situation_admin_deleted_created_idx');
            }
        });

        // Nature de Coupes table - additional indexes
        Schema::table('nature_de_coupes', function (Blueprint $table) {
            if (!$this->indexExists('nature_de_coupes', 'nature_coupes_nature_idx')) {
                $table->index(['nature_de_coupe'], 'nature_coupes_nature_idx');
            }
            if (!$this->indexExists('nature_de_coupes', 'nature_coupes_deleted_created_idx')) {
                $table->index(['is_deleted', 'created_at'], 'nature_coupes_deleted_created_idx');
            }
        });

        // Activity logs table - additional performance indexes
        Schema::table('activity_logs', function (Blueprint $table) {
            if (!$this->indexExists('activity_logs', 'activity_logs_model_created_idx')) {
                $table->index(['model_type', 'model_id', 'created_at'], 'activity_logs_model_created_idx');
            }
            if (!$this->indexExists('activity_logs', 'activity_logs_url_method_idx')) {
                $table->index(['url', 'method'], 'activity_logs_url_method_idx');
            }
            if (!$this->indexExists('activity_logs', 'activity_logs_ip_created_idx')) {
                $table->index(['ip_address', 'created_at'], 'activity_logs_ip_created_idx');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Articles table indexes
        Schema::table('articles', function (Blueprint $table) {
            $table->dropIndex('articles_year_status_idx');
            // Removed foret_id and essence_id indexes - these are many-to-many relationships via pivot tables
            // Removed articles_price_status_idx - prix_vente and invendu columns were removed
            $table->dropIndex('articles_type_year_idx');
            $table->dropIndex('articles_exploitant_created_idx');
            // Removed localisation_id index - this is a many-to-many relationship via pivot table
            // Removed situation_administrative_id index - this is a many-to-many relationship via pivot table
            // Removed nature_de_coupe_id index - this is a many-to-many relationship via pivot table
            $table->dropIndex('articles_created_updated_idx');
        });

        // Exploitants table indexes
        Schema::table('exploitants', function (Blueprint $table) {
            $table->dropIndex('exploitants_categorie_activite_idx');
            $table->dropIndex('exploitants_exclusion_created_idx');
            // Removed adjudicataire index as column doesn't exist
            $table->dropIndex('exploitants_qualification_idx');
            $table->dropIndex('exploitants_date_obtention_idx');
            $table->dropIndex('exploitants_duree_validite_idx');
        });

        // Users table indexes
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('users_created_updated_idx');
            $table->dropIndex('users_deleted_ppr_idx');
        });

        // Essences table indexes
        Schema::table('essences', function (Blueprint $table) {
            $table->dropIndex('essences_deleted_created_idx');
            $table->dropIndex('essences_essence_deleted_idx');
        });

        // Forets table indexes
        Schema::table('forets', function (Blueprint $table) {
            $table->dropIndex('forets_deleted_created_idx');
            $table->dropIndex('forets_foret_deleted_idx');
        });

        // Situation Administratives table indexes
        Schema::table('situation_administratives', function (Blueprint $table) {
            $table->dropIndex('situation_admin_commune_idx');
            $table->dropIndex('situation_admin_province_idx');
            $table->dropIndex('situation_admin_deleted_created_idx');
        });

        // Nature de Coupes table indexes
        Schema::table('nature_de_coupes', function (Blueprint $table) {
            $table->dropIndex('nature_coupes_nature_idx');
            $table->dropIndex('nature_coupes_deleted_created_idx');
        });

        // Activity logs table indexes
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->dropIndex('activity_logs_model_created_idx');
            $table->dropIndex('activity_logs_url_method_idx');
            $table->dropIndex('activity_logs_ip_created_idx');
        });
    }
};
