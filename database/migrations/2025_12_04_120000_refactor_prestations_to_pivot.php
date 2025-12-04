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
        // Ensure prestations table exists
        if (!Schema::hasTable('prestations')) {
            Schema::create('prestations', function (Blueprint $table) {
                $table->id();
                $table->string('name')->unique();
                $table->timestamps();
            });
        }

        // Create pivot table for Contract <-> Prestations
        if (!Schema::hasTable('contract_prestation')) {
            Schema::create('contract_prestation', function (Blueprint $table) {
                $table->id();
                $table->foreignId('contract_id')
                    ->constrained('contacts')
                    ->onDelete('cascade');
                $table->foreignId('prestation_id')
                    ->constrained('prestations')
                    ->onDelete('cascade');
                $table->decimal('quantity', 10, 2)->default(1);
                $table->timestamps();

                $table->unique(['contract_id', 'prestation_id'], 'contract_prestation_unique');
            });
        }

        // Create pivot table for Avenant <-> Prestations
        if (!Schema::hasTable('avenant_prestation')) {
            Schema::create('avenant_prestation', function (Blueprint $table) {
                $table->id();
                $table->foreignId('avenant_id')
                    ->constrained('avenants')
                    ->onDelete('cascade');
                $table->foreignId('prestation_id')
                    ->constrained('prestations')
                    ->onDelete('cascade');
                $table->decimal('quantity', 10, 2)->default(1);
                $table->timestamps();

                $table->unique(['avenant_id', 'prestation_id'], 'avenant_prestation_unique');
            });
        }

        // Migrate existing data from the old prestations structure (if columns exist)
        if (Schema::hasTable('prestations')
            && Schema::hasColumn('prestations', 'quantity')
            && Schema::hasColumn('prestations', 'contract_id')
            && Schema::hasColumn('prestations', 'avenant_id')
        ) {
            $rows = DB::table('prestations')->select('id', 'name', 'quantity', 'contract_id', 'avenant_id')->get();

            foreach ($rows as $row) {
                // Normalise quantity
                $qty = is_null($row->quantity) ? 1 : (float) $row->quantity;
                if ($qty <= 0) {
                    $qty = 1;
                }

                // Ensure a prestation record exists (by name)
                $prestationId = DB::table('prestations')
                    ->where('name', $row->name)
                    ->value('id');

                if (!$prestationId) {
                    $prestationId = DB::table('prestations')->insertGetId([
                        'name'       => $row->name ?: ('Prestation #' . $row->id),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                if ($row->contract_id) {
                    // Avoid duplicate rows thanks to unique index
                    $exists = DB::table('contract_prestation')
                        ->where('contract_id', $row->contract_id)
                        ->where('prestation_id', $prestationId)
                        ->exists();

                    if (!$exists) {
                        DB::table('contract_prestation')->insert([
                            'contract_id'   => $row->contract_id,
                            'prestation_id' => $prestationId,
                            'quantity'      => $qty,
                            'created_at'    => now(),
                            'updated_at'    => now(),
                        ]);
                    }
                }

                if ($row->avenant_id) {
                    $exists = DB::table('avenant_prestation')
                        ->where('avenant_id', $row->avenant_id)
                        ->where('prestation_id', $prestationId)
                        ->exists();

                    if (!$exists) {
                        DB::table('avenant_prestation')->insert([
                            'avenant_id'    => $row->avenant_id,
                            'prestation_id' => $prestationId,
                            'quantity'      => $qty,
                            'created_at'    => now(),
                            'updated_at'    => now(),
                        ]);
                    }
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('contract_prestation')) {
            Schema::dropIfExists('contract_prestation');
        }

        if (Schema::hasTable('avenant_prestation')) {
            Schema::dropIfExists('avenant_prestation');
        }
    }
};


