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
        Schema::table('archives', function (Blueprint $table) {
            $table->enum('service', [
                "FUP et de l''accueil du public",
                "des études et des l''inventaire forestier national",
                "organisation de l''exploitation forestiére",
                "la valorisation des produit forstiers",
                "animation territoriale et partenariat",
                "parcours forestiers et sylvopastoraux",
            ])->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('archives', function (Blueprint $table) {
            $table->string('service')->nullable()->change();
        });
    }
};


