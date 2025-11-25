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
        Schema::table('contacts', function (Blueprint $table) {
            // Drop old columns
            $table->dropColumn(['gardiennage', 'prevention_contre_les_incendies']);
            
            // Add new gardiennage columns
            $table->integer('gardiennage_nbjour')->nullable()->after('superficie');
            $table->integer('gardiennage_superficie')->nullable()->after('gardiennage_nbjour');
            $table->string('gardiennage_parcelle')->nullable()->after('gardiennage_superficie');
            
            // Add new prevention_incendies columns
            $table->integer('prevention_incendies_nbjour')->nullable()->after('gardiennage_parcelle');
            $table->integer('prevention_incendies_superficie')->nullable()->after('prevention_incendies_nbjour');
            $table->string('prevention_incendies_parcelle')->nullable()->after('prevention_incendies_superficie');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contacts', function (Blueprint $table) {
            // Drop new columns
            $table->dropColumn([
                'gardiennage_nbjour',
                'gardiennage_superficie',
                'gardiennage_parcelle',
                'prevention_incendies_nbjour',
                'prevention_incendies_superficie',
                'prevention_incendies_parcelle'
            ]);
            
            // Restore old columns
            $table->string('gardiennage')->nullable()->after('superficie');
            $table->string('prevention_contre_les_incendies')->nullable()->after('gardiennage');
        });
    }
};
