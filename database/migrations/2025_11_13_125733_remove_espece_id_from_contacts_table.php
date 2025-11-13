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
    public function up(): void
    {
        // First, migrate existing espece_id data to the pivot table if it exists
        if (Schema::hasColumn('contacts', 'espece_id') && Schema::hasTable('contact_espece')) {
            // Migrate existing espece_id relationships to the pivot table
            $contacts = DB::table('contacts')
                ->whereNotNull('espece_id')
                ->get();
            
            foreach ($contacts as $contact) {
                // Insert into pivot table, ignoring duplicates
                DB::table('contact_espece')->insertOrIgnore([
                    'contact_id' => $contact->id,
                    'espece_id' => $contact->espece_id,
                    'created_at' => $contact->created_at ?? now(),
                    'updated_at' => $contact->updated_at ?? now(),
                ]);
            }
        }
        
        // Now remove the foreign key constraint and column
        Schema::table('contacts', function (Blueprint $table) {
            if (Schema::hasColumn('contacts', 'espece_id')) {
                $table->dropForeign(['espece_id']);
                $table->dropColumn('espece_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contacts', function (Blueprint $table) {
            // Add back the espece_id column
            if (!Schema::hasColumn('contacts', 'espece_id')) {
                $table->foreignId('espece_id')->nullable()->constrained('especes')->onDelete('cascade')->after('situation_administrative_id');
            }
        });
        
        // Migrate data back from pivot table (taking the first espece for each contact)
        if (Schema::hasTable('contact_espece')) {
            $pivotData = DB::table('contact_espece')
                ->select('contact_id', 'espece_id')
                ->groupBy('contact_id')
                ->get();
            
            foreach ($pivotData as $pivot) {
                DB::table('contacts')
                    ->where('id', $pivot->contact_id)
                    ->update(['espece_id' => $pivot->espece_id]);
            }
        }
    }
};
