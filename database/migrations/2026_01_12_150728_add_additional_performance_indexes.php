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
                $indexes = DB::select("PRAGMA index_list({$table})");
                foreach ($indexes as $idx) {
                    if ($idx->name === $index) {
                        return true;
                    }
                }
                return false;
            } else {
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
        // Articles table - invendu and current_step indexes
        // Note: invendu column will be created by fix_invandu_column_name_typo migration
        Schema::table('articles', function (Blueprint $table) {
            // Only add index if column exists (typo fix migration runs first)
            if (Schema::hasColumn('articles', 'invendu')) {
                if (!$this->indexExists('articles', 'articles_invendu_idx')) {
                    $table->index(['invendu'], 'articles_invendu_idx');
                }
                if (!$this->indexExists('articles', 'articles_invendu_year_idx')) {
                    $table->index(['invendu', 'annee'], 'articles_invendu_year_idx');
                }
            }
            
            if (Schema::hasColumn('articles', 'current_step')) {
                if (!$this->indexExists('articles', 'articles_current_step_idx')) {
                    $table->index(['current_step'], 'articles_current_step_idx');
                }
            }
        });

        // Archives table - search and filter optimization
        Schema::table('archives', function (Blueprint $table) {
            if (!$this->indexExists('archives', 'archives_numero_idx')) {
                $table->index(['numero'], 'archives_numero_idx');
            }
            if (!$this->indexExists('archives', 'archives_expediteur_idx')) {
                $table->index(['expediteur'], 'archives_expediteur_idx');
            }
            if (!$this->indexExists('archives', 'archives_departement_service_idx')) {
                $table->index(['departement', 'service'], 'archives_departement_service_idx');
            }
            if (!$this->indexExists('archives', 'archives_date_idx')) {
                $table->index(['date'], 'archives_date_idx');
            }
        });

        // Contract Ventes table - type and date indexes
        Schema::table('contract_ventes', function (Blueprint $table) {
            if (!$this->indexExists('contract_ventes', 'contract_ventes_type_idx')) {
                $table->index(['type'], 'contract_ventes_type_idx');
            }
            if (!$this->indexExists('contract_ventes', 'contract_ventes_date_adjudication_idx')) {
                $table->index(['date_adjudication'], 'contract_ventes_date_adjudication_idx');
            }
            if (!$this->indexExists('contract_ventes', 'contract_ventes_article_exploitant_idx')) {
                $table->index(['article_id', 'exploitant_id'], 'contract_ventes_article_exploitant_idx');
            }
        });

        // Charge Apayer table - optimize payment queries
        Schema::table('charge_apayer', function (Blueprint $table) {
            if (!$this->indexExists('charge_apayer', 'charge_apayer_contrat_nom_idx')) {
                $table->index(['contrat_vente_id', 'nom'], 'charge_apayer_contrat_nom_idx');
            }
            if (!$this->indexExists('charge_apayer', 'charge_apayer_date_echeance_idx')) {
                $table->index(['date_echeance'], 'charge_apayer_date_echeance_idx');
            }
        });

        // Payments table - already has necessary indexes in create migration

        // Exploitants table - additional search indexes
        if (Schema::hasColumn('exploitants', 'numero')) {
            Schema::table('exploitants', function (Blueprint $table) {
                if (!$this->indexExists('exploitants', 'exploitants_numero_idx')) {
                    $table->index(['numero'], 'exploitants_numero_idx');
                }
            });
        }
        
        if (Schema::hasColumn('exploitants', 'n_cin')) {
            Schema::table('exploitants', function (Blueprint $table) {
                if (!$this->indexExists('exploitants', 'exploitants_n_cin_idx')) {
                    $table->index(['n_cin'], 'exploitants_n_cin_idx');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Articles table
        Schema::table('articles', function (Blueprint $table) {
            $table->dropIndex('articles_invendu_idx');
            $table->dropIndex('articles_current_step_idx');
            $table->dropIndex('articles_invendu_year_idx');
        });

        // Archives table
        Schema::table('archives', function (Blueprint $table) {
            $table->dropIndex('archives_numero_idx');
            $table->dropIndex('archives_expediteur_idx');
            $table->dropIndex('archives_departement_service_idx');
            $table->dropIndex('archives_date_idx');
        });

        // Contract Ventes table
        Schema::table('contract_ventes', function (Blueprint $table) {
            $table->dropIndex('contract_ventes_type_idx');
            $table->dropIndex('contract_ventes_date_adjudication_idx');
            $table->dropIndex('contract_ventes_article_exploitant_idx');
        });

        // Charge Apayer table
        Schema::table('charge_apayer', function (Blueprint $table) {
            $table->dropIndex('charge_apayer_contrat_nom_idx');
            $table->dropIndex('charge_apayer_date_echeance_idx');
        });

        // Payments table - no custom indexes added

        // Exploitants table
        if (Schema::hasColumn('exploitants', 'numero')) {
            Schema::table('exploitants', function (Blueprint $table) {
                $table->dropIndex('exploitants_numero_idx');
            });
        }
        
        if (Schema::hasColumn('exploitants', 'n_cin')) {
            Schema::table('exploitants', function (Blueprint $table) {
                $table->dropIndex('exploitants_n_cin_idx');
            });
        }
    }
};
