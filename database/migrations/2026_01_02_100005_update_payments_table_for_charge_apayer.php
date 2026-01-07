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
        Schema::table('payments', function (Blueprint $table) {
            // Add chargeapayer_id foreign key
            $table->foreignId('chargeapayer_id')->nullable()->after('contract_vente_id')->constrained('charge_apayer')->onDelete('cascade');
            
            // Index
            $table->index('chargeapayer_id');
        });
        
        // Rename fichier_join to fichier_joint if it exists
        // Note: renameColumn requires doctrine/dbal package
        if (Schema::hasColumn('payments', 'fichier_join') && !Schema::hasColumn('payments', 'fichier_joint')) {
            Schema::table('payments', function (Blueprint $table) {
                $table->renameColumn('fichier_join', 'fichier_joint');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropIndex(['chargeapayer_id']);
            $table->dropForeign(['chargeapayer_id']);
            $table->dropColumn('chargeapayer_id');
        });
        
        // Rename back if needed
        if (Schema::hasColumn('payments', 'fichier_joint') && !Schema::hasColumn('payments', 'fichier_join')) {
            Schema::table('payments', function (Blueprint $table) {
                $table->renameColumn('fichier_joint', 'fichier_join');
            });
        }
    }
};

