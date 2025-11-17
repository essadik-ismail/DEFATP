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
        Schema::table('pdfcs', function (Blueprint $table) {
            // Change etat to enum with cycle de vie states
            $table->enum('etat', ['Non élaboré', 'élaboré', 'validé', 'validé C.C'])->default('Non élaboré')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pdfcs', function (Blueprint $table) {
            $table->string('etat')->nullable()->change();
        });
    }
};
