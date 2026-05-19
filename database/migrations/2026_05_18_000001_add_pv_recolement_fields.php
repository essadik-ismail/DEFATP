<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('recolements', function (Blueprint $table) {
            $table->date('date_recolement')->nullable()->after('contract_vente_id');
            $table->string('adjudication', 120)->nullable()->after('date_recolement');
            $table->string('num_marche', 80)->nullable()->after('adjudication');
            $table->json('commission')->nullable()->after('num_marche'); // [{nom_prenom, fonction, entite}]
            $table->string('marteau', 80)->nullable()->after('commission');
            $table->string('marque', 80)->nullable()->after('marteau');
            $table->json('souches_reserves')->nullable()->after('marque'); // [{essence, avec_empreinte, sans_empreinte, total, nombre_pv}]
            // Operations
            $table->string('la_coupe', 120)->nullable();
            $table->string('les_limites', 120)->nullable();
            $table->string('le_vidange', 120)->nullable();
            $table->string('nettoyage_coupe', 120)->nullable();
            $table->string('le_recru', 120)->nullable();
            $table->string('travaux_imposes', 120)->nullable();
            $table->string('fourniture_mise_en_charge', 120)->nullable();
            $table->string('delits_constates', 120)->nullable();
            // Produits en matière
            $table->decimal('bois_oeuvre', 12, 3)->nullable();
            $table->decimal('bois_industrie', 12, 3)->nullable();
            $table->decimal('bois_service', 12, 3)->nullable();
            $table->decimal('bois_chauffage', 12, 3)->nullable();
            $table->decimal('brins_cedre', 12, 0)->nullable();
            $table->decimal('liege_male', 12, 3)->nullable();
            $table->decimal('liege_reproduction', 12, 3)->nullable();
            $table->decimal('ecorce_tanin', 12, 3)->nullable();
            $table->decimal('bois_carboniser', 12, 3)->nullable();
            // Produits abandonnés
            $table->json('produits_abandonnes')->nullable(); // [{nature, quantite}]
        });
    }

    public function down(): void
    {
        Schema::table('recolements', function (Blueprint $table) {
            $table->dropColumn([
                'date_recolement', 'adjudication', 'num_marche', 'commission',
                'marteau', 'marque', 'souches_reserves',
                'la_coupe', 'les_limites', 'le_vidange', 'nettoyage_coupe',
                'le_recru', 'travaux_imposes', 'fourniture_mise_en_charge', 'delits_constates',
                'bois_oeuvre', 'bois_industrie', 'bois_service', 'bois_chauffage',
                'brins_cedre', 'liege_male', 'liege_reproduction', 'ecorce_tanin',
                'bois_carboniser', 'produits_abandonnes',
            ]);
        });
    }
};
