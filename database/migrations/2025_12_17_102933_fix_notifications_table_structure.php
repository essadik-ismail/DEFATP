<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // First, ensure the table exists
        if (!Schema::hasTable('notifications')) {
            Schema::create('notifications', function (Blueprint $table) {
                $table->id();
                $table->string('type');
                $table->string('title');
                $table->text('message');
                $table->json('data')->nullable();
                $table->timestamp('read_at')->nullable();
                $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
                $table->string('notifiable_type');
                $table->unsignedBigInteger('notifiable_id');
                $table->string('action_url')->nullable();
                $table->string('icon')->nullable();
                $table->string('color')->nullable();
                $table->string('priority')->default('medium');
                $table->timestamps();
                
                // Indexes
                $table->index(['user_id', 'read_at']);
                $table->index('type');
                $table->index('priority');
                $table->index('created_at');
            });
        } else {
            // Table exists, add missing columns
            Schema::table('notifications', function (Blueprint $table) {
                $columns = Schema::getColumnListing('notifications');
                
                if (!in_array('title', $columns)) {
                    $table->string('title')->after('type');
                }
                
                if (!in_array('message', $columns)) {
                    $table->text('message')->after('title');
                }
                
                if (!in_array('user_id', $columns)) {
                    $table->foreignId('user_id')->after('read_at')->nullable()->constrained()->onDelete('cascade');
                }
                
                if (!in_array('action_url', $columns)) {
                    $table->string('action_url')->nullable()->after('notifiable_id');
                }
                
                if (!in_array('icon', $columns)) {
                    $table->string('icon')->nullable()->after('action_url');
                }
                
                if (!in_array('color', $columns)) {
                    $table->string('color')->nullable()->after('icon');
                }
                
                if (!in_array('priority', $columns)) {
                    $table->string('priority')->default('medium')->after('color');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        // Only drop the table if it was created by this migration
        if (Schema::hasTable('notifications') && !Schema::hasColumn('notifications', 'data')) {
            Schema::dropIfExists('notifications');
        } else {
            // Just drop the columns that were added by this migration
            Schema::table('notifications', function (Blueprint $table) {
                $columns = ['title', 'message', 'user_id', 'action_url', 'icon', 'color', 'priority'];
                
                foreach ($columns as $column) {
                    if (Schema::hasColumn('notifications', $column)) {
                        $table->dropColumn($column);
                    }
                }
                
                // Drop foreign key constraint if it exists
                $foreignKeys = Schema::getConnection()
                    ->getDoctrineSchemaManager()
                    ->listTableForeignKeys('notifications');
                
                $userFkExists = collect($foreignKeys)->contains(function ($fk) {
                    return in_array('user_id', $fk->getLocalColumns());
                });
                
                if ($userFkExists) {
                    $table->dropForeign(['user_id']);
                }
            });
        }
    }
};
