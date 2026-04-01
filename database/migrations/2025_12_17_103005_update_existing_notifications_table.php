<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // Add any missing columns to the notifications table
        Schema::table('notifications', function (Blueprint $table) {
            // Check and add title column if it doesn't exist
            if (!Schema::hasColumn('notifications', 'title')) {
                $table->string('title')->after('type')->default('Notification');
            }
            
            // Check and add message column if it doesn't exist
            if (!Schema::hasColumn('notifications', 'message')) {
                $table->text('message')->after('title')->nullable();
            }
            
            // Check and add user_id column if it doesn't exist
            if (!Schema::hasColumn('notifications', 'user_id')) {
                $table->unsignedBigInteger('user_id')->after('read_at')->nullable();
                // We'll add the foreign key constraint separately to avoid issues
            }
            
            // Check and add action_url column if it doesn't exist
            if (!Schema::hasColumn('notifications', 'action_url')) {
                $table->string('action_url')->after('notifiable_id')->nullable();
            }
            
            // Check and add icon column if it doesn't exist
            if (!Schema::hasColumn('notifications', 'icon')) {
                $table->string('icon')->after('action_url')->nullable();
            }
            
            // Check and add color column if it doesn't exist
            if (!Schema::hasColumn('notifications', 'color')) {
                $table->string('color')->after('icon')->nullable();
            }
            
            // Check and add priority column if it doesn't exist
            if (!Schema::hasColumn('notifications', 'priority')) {
                $table->string('priority')->after('color')->default('medium');
            }
        });
        
        // Add foreign key constraint after ensuring the column exists and the constraint doesn't already exist
        Schema::table('notifications', function (Blueprint $table) {
            if (Schema::hasColumn('notifications', 'user_id')) {
                $constraintExists = false;

                if (DB::getDriverName() !== 'sqlite') {
                    // Check if the foreign key constraint already exists on engines that expose information_schema.
                    $db = DB::getDatabaseName();
                    $constraintExists = DB::table('information_schema.TABLE_CONSTRAINTS')
                        ->where('TABLE_SCHEMA', $db)
                        ->where('TABLE_NAME', 'notifications')
                        ->where('CONSTRAINT_NAME', 'notifications_user_id_foreign')
                        ->exists();
                }
                
                if (!$constraintExists) {
                    // Only add the foreign key if it doesn't exist
                    $table->foreign('user_id')
                          ->references('id')
                          ->on('users')
                          ->onDelete('cascade');
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        // Drop foreign key constraint first
        if (Schema::hasTable('notifications') && Schema::hasColumn('notifications', 'user_id')) {
            Schema::table('notifications', function (Blueprint $table) {
                $table->dropForeign(['user_id']);
            });
        }
        
        // Then drop the columns we added
        Schema::table('notifications', function (Blueprint $table) {
            $columnsToDrop = [
                'title', 'message', 'user_id', 
                'action_url', 'icon', 'color', 'priority'
            ];
            
            foreach ($columnsToDrop as $column) {
                if (Schema::hasColumn('notifications', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
