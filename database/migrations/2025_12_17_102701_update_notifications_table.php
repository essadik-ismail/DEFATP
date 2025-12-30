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
        Schema::table('notifications', function (Blueprint $table) {
            if (!Schema::hasColumn('notifications', 'title')) {
                $table->string('title')->after('type');
            }
            
            if (!Schema::hasColumn('notifications', 'message')) {
                $table->text('message')->after('title');
            }
            
            if (!Schema::hasColumn('notifications', 'user_id')) {
                $table->foreignId('user_id')->after('read_at')->nullable()->constrained()->onDelete('cascade');
            }
            
            if (!Schema::hasColumn('notifications', 'action_url')) {
                $table->string('action_url')->nullable()->after('notifiable_id');
            }
            
            if (!Schema::hasColumn('notifications', 'icon')) {
                $table->string('icon')->nullable()->after('action_url');
            }
            
            if (!Schema::hasColumn('notifications', 'color')) {
                $table->string('color')->nullable()->after('icon');
            }
            
            if (!Schema::hasColumn('notifications', 'priority')) {
                $table->string('priority')->default('medium')->after('color');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropColumn([
                'title',
                'message',
                'user_id',
                'action_url',
                'icon',
                'color',
                'priority'
            ]);
        });
    }
};
