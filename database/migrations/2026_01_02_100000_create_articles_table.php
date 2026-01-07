<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('articles')) {
            Schema::create('articles', function (Blueprint $table) {
                $table->id();
                $table->string('numero')->nullable();
                $table->integer('annee')->nullable();
                $table->string('lot')->nullable();
                $table->string('parcelle')->nullable();
                $table->decimal('superficie', 15, 2)->nullable();
                $table->decimal('fourniture_mise_charge', 15, 2)->nullable();
                $table->decimal('taxe_refection_chemins', 15, 2)->nullable();
                $table->decimal('service_rendu_anef', 15, 2)->nullable();
                $table->decimal('bois_chauffage_volume', 15, 2)->nullable();
                $table->string('bois_chauffage_destination')->nullable();
                $table->date('date_payement_service_anef')->nullable();
                $table->date('date_livaison_mise_en_charge_bf')->nullable();
                $table->boolean('invandu')->default(false);
                $table->timestamps();
                $table->softDeletes();
                
                // Indexes
                $table->index('numero');
                $table->index('annee');
                $table->index(['numero', 'annee']);
            });
        } else {
            // Table exists, add missing columns if they don't exist
            Schema::table('articles', function (Blueprint $table) {
                if (!Schema::hasColumn('articles', 'lot')) {
                    $table->string('lot')->nullable()->after('annee');
                }
                if (!Schema::hasColumn('articles', 'parcelle')) {
                    $table->string('parcelle')->nullable()->after('lot');
                }
                if (!Schema::hasColumn('articles', 'superficie')) {
                    $table->decimal('superficie', 15, 2)->nullable()->after('parcelle');
                }
                if (!Schema::hasColumn('articles', 'fourniture_mise_charge')) {
                    $table->decimal('fourniture_mise_charge', 15, 2)->nullable()->after('superficie');
                }
                if (!Schema::hasColumn('articles', 'taxe_refection_chemins')) {
                    $table->decimal('taxe_refection_chemins', 15, 2)->nullable()->after('fourniture_mise_charge');
                }
                if (!Schema::hasColumn('articles', 'service_rendu_anef')) {
                    $table->decimal('service_rendu_anef', 15, 2)->nullable()->after('taxe_refection_chemins');
                }
                if (!Schema::hasColumn('articles', 'bois_chauffage_volume')) {
                    $table->decimal('bois_chauffage_volume', 15, 2)->nullable()->after('service_rendu_anef');
                }
                if (!Schema::hasColumn('articles', 'bois_chauffage_destination')) {
                    $table->string('bois_chauffage_destination')->nullable()->after('bois_chauffage_volume');
                }
                if (!Schema::hasColumn('articles', 'date_payement_service_anef')) {
                    $table->date('date_payement_service_anef')->nullable()->after('bois_chauffage_destination');
                }
                if (!Schema::hasColumn('articles', 'date_livaison_mise_en_charge_bf')) {
                    $table->date('date_livaison_mise_en_charge_bf')->nullable()->after('date_payement_service_anef');
                }
                if (!Schema::hasColumn('articles', 'invandu')) {
                    $table->boolean('invandu')->default(false)->after('date_livaison_mise_en_charge_bf');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};

