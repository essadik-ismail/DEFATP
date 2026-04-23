<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('colportage_enlever_product')) {
            Schema::create('colportage_enlever_product', function (Blueprint $table) {
                $table->id();
                $table->foreignId('colportage_enlever_id')
                    ->constrained('colportage_enlever')
                    ->onDelete('cascade');
                $table->foreignId('product_id')
                    ->constrained('products')
                    ->onDelete('cascade');
                $table->foreignId('id_essence')
                    ->constrained('essences')
                    ->onDelete('cascade');
                $table->decimal('quantity', 15, 2)->nullable();
                $table->timestamps();

                $table->unique(
                    ['colportage_enlever_id', 'product_id', 'id_essence'],
                    'colportage_enlever_product_unique'
                );
            });
        }

        if (Schema::hasTable('colportage_enlever') && !Schema::hasColumn('colportage_enlever', 'volume')) {
            Schema::table('colportage_enlever', function (Blueprint $table) {
                $table->decimal('volume', 15, 2)->nullable()->after('numero_permis');
            });
        }

        $timestamp = now();

        if (Schema::hasTable('colportage_enlever') && Schema::hasTable('colportage_enlever_product')) {
            $legacyRows = DB::table('colportage_enlever')
                ->select('id', 'product_id', 'id_essence', 'quantity')
                ->whereNotNull('product_id')
                ->whereNotNull('id_essence')
                ->get();

            if ($legacyRows->isNotEmpty()) {
                DB::table('colportage_enlever_product')->upsert(
                    $legacyRows->map(function ($row) use ($timestamp) {
                        return [
                            'colportage_enlever_id' => $row->id,
                            'product_id' => $row->product_id,
                            'id_essence' => $row->id_essence,
                            'quantity' => $row->quantity,
                            'created_at' => $timestamp,
                            'updated_at' => $timestamp,
                        ];
                    })->all(),
                    ['colportage_enlever_id', 'product_id', 'id_essence'],
                    ['quantity', 'updated_at']
                );
            }

            if (Schema::hasColumn('colportage_enlever', 'volume')) {
                $colportageTotals = DB::table('colportage_enlever_product')
                    ->select('colportage_enlever_id', DB::raw('COALESCE(SUM(quantity), 0) as total_volume'))
                    ->groupBy('colportage_enlever_id')
                    ->get();

                foreach ($colportageTotals as $total) {
                    DB::table('colportage_enlever')
                        ->where('id', $total->colportage_enlever_id)
                        ->update([
                            'volume' => $total->total_volume,
                            'updated_at' => $timestamp,
                        ]);
                }
            }
        }

        if (
            Schema::hasTable('permi_enlevers')
            && Schema::hasColumn('permi_enlevers', 'volume')
            && Schema::hasTable('permisenlever_product')
        ) {
            $permisTotals = DB::table('permisenlever_product')
                ->select('permis_id', DB::raw('COALESCE(SUM(quantity), 0) as total_volume'))
                ->groupBy('permis_id')
                ->get();

            foreach ($permisTotals as $total) {
                DB::table('permi_enlevers')
                    ->where('id', $total->permis_id)
                    ->update([
                        'volume' => $total->total_volume,
                        'updated_at' => $timestamp,
                    ]);
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('colportage_enlever') && Schema::hasColumn('colportage_enlever', 'volume')) {
            Schema::table('colportage_enlever', function (Blueprint $table) {
                $table->dropColumn('volume');
            });
        }

        Schema::dropIfExists('colportage_enlever_product');
    }
};
