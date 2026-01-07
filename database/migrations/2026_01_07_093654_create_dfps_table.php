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
        Schema::create('dfps', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('dfp');
            $table->string('zdtf_code')->nullable();
            $table->string('dpanef_code')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dfps');
    }
};
