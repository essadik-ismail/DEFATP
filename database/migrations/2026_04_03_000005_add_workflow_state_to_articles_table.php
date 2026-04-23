<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            // Formal workflow state replaces current_step (kept for BC)
            $table->string('workflow_state', 60)
                ->default('DRAFT_ARTICLE')
                ->after('current_step')
                ->index();
            $table->timestamp('workflow_state_updated_at')->nullable()->after('workflow_state');
            $table->unsignedBigInteger('workflow_state_updated_by')->nullable()->after('workflow_state_updated_at');
        });
    }

    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropColumn(['workflow_state', 'workflow_state_updated_at', 'workflow_state_updated_by']);
        });
    }
};
