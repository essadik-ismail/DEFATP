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
        Schema::table('etapes', function (Blueprint $table) {
            $table->enum('etat', ['en_attente', 'en_cours', 'validée', 'rejetée'])->default('en_attente')->after('content');
            $table->foreignId('validated_by')->nullable()->constrained('users')->onDelete('set null')->after('etat');
            $table->timestamp('validated_at')->nullable()->after('validated_by');
            $table->text('commentaire_validation')->nullable()->after('validated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('etapes', function (Blueprint $table) {
            $table->dropForeign(['validated_by']);
            $table->dropColumn(['etat', 'validated_by', 'validated_at', 'commentaire_validation']);
        });
    }
};
