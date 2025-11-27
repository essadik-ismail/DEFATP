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
        // Create contact_essence table
        if (!Schema::hasTable('contact_essence')) {
            Schema::create('contact_essence', function (Blueprint $table) {
                $table->id();
                $table->foreignId('contact_id')->constrained('contacts')->onDelete('cascade');
                $table->foreignId('essence_id')->constrained('essences')->onDelete('cascade');
                $table->timestamps();
                
                // Ensure unique combination of contact and essence
                $table->unique(['contact_id', 'essence_id'], 'contact_essence_unique');
                
                // Indexes for better performance
                $table->index('contact_id');
                $table->index('essence_id');
            });
        }
        
        // Migrate data from contact_espece to contact_essence if contact_espece exists
        if (Schema::hasTable('contact_espece')) {
            // Get all especes and their corresponding essences by name
            $especes = DB::table('especes')->get();
            $essences = DB::table('essences')->get();
            
            // Create a mapping of espece name to essence id
            $mapping = [];
            foreach ($especes as $espece) {
                $essence = $essences->firstWhere('essence', $espece->name);
                if ($essence) {
                    $mapping[$espece->id] = $essence->id;
                }
            }
            
            // Migrate contact_espece data to contact_essence
            $contactEspeces = DB::table('contact_espece')->get();
            foreach ($contactEspeces as $contactEspece) {
                if (isset($mapping[$contactEspece->espece_id])) {
                    DB::table('contact_essence')->insertOrIgnore([
                        'contact_id' => $contactEspece->contact_id,
                        'essence_id' => $mapping[$contactEspece->espece_id],
                        'created_at' => $contactEspece->created_at,
                        'updated_at' => $contactEspece->updated_at,
                    ]);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop contact_essence table
        Schema::dropIfExists('contact_essence');
    }
};
