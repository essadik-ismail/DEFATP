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
        Schema::table('permis', function (Blueprint $table) {
            // Add columns for Permis de colportage
            $table->string('num_permis')->nullable()->after('id');
            $table->date('date')->nullable()->after('num_permis');
            $table->string('nom_chauffeur')->nullable()->after('date');
            $table->string('cin')->nullable()->after('nom_chauffeur');
            $table->string('marque_vehicule')->nullable()->after('cin');
            $table->string('matricule_vehicule')->nullable()->after('marque_vehicule');
            $table->date('start_date')->nullable()->after('matricule_vehicule');
            $table->date('expire_date')->nullable()->after('start_date');
            $table->date('date_edition')->nullable()->after('expire_date');
            
            // Indexes
            $table->index('num_permis');
            $table->index('date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permis', function (Blueprint $table) {
            $table->dropIndex(['num_permis']);
            $table->dropIndex(['date']);
            $table->dropColumn([
                'num_permis',
                'date',
                'nom_chauffeur',
                'cin',
                'marque_vehicule',
                'matricule_vehicule',
                'start_date',
                'expire_date',
                'date_edition',
            ]);
        });
    }
};

