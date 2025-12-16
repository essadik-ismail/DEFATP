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
            $table->enum('departement', [
                "l’Economie Forestièr",
                "l’Animation Territoriale et du Partenariat",
            ])->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('archives', function (Blueprint $table) {
            $table->string('departement')->nullable()->change();
        });
    }
};


