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
        Schema::table('contacts', function (Blueprint $table) {
            // Add foret_id if it doesn't exist
            if (!Schema::hasColumn('contacts', 'foret_id')) {
                $table->foreignId('foret_id')->nullable()->after('situation_administrative_id')->constrained('forets')->onDelete('cascade');
            }
            
            // Add coperative_id if it doesn't exist
            if (!Schema::hasColumn('contacts', 'coperative_id')) {
                $table->foreignId('coperative_id')->nullable()->after('foret_id')->constrained('coperatives')->onDelete('cascade');
            }
            
            // Add missing product columns if they don't exist
            $productColumns = [
                'laurier_sauce' => 'integer',
                'myrte' => 'integer',
                'callune' => 'integer',
                'thym' => 'integer',
                'bruyetre' => 'integer',
                'lichen' => 'integer',
                'tanin' => 'integer',
                'romarin' => 'integer',
                'liege_male' => 'integer',
                'liege_de_reproduction' => 'integer',
                'sauge' => 'integer',
                'lavande' => 'integer',
                'armoise' => 'integer',
                'origan' => 'integer',
                'alfa' => 'integer',
                'lentisque' => 'integer',
                'ciste' => 'integer',
                'prevention_contre_les_incendies' => 'string',
                'autre' => 'string',
                'resiliation' => 'boolean',
                'date_resiliation' => 'date',
            ];
            
            foreach ($productColumns as $column => $type) {
                if (!Schema::hasColumn('contacts', $column)) {
                    if ($type === 'integer') {
                        $table->integer($column)->nullable();
                    } elseif ($type === 'string') {
                        $table->string($column)->nullable();
                    } elseif ($type === 'boolean') {
                        $table->boolean($column)->default(false);
                    } elseif ($type === 'date') {
                        $table->date($column)->nullable();
                    }
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contacts', function (Blueprint $table) {
            // Drop foreign keys first
            if (Schema::hasColumn('contacts', 'foret_id')) {
                $table->dropForeign(['foret_id']);
                $table->dropColumn('foret_id');
            }
            
            if (Schema::hasColumn('contacts', 'coperative_id')) {
                $table->dropForeign(['coperative_id']);
                $table->dropColumn('coperative_id');
            }
            
            // Drop product columns
            $columnsToDrop = [
                'laurier_sauce', 'myrte', 'callune', 'thym', 'bruyetre', 'lichen', 'tanin',
                'romarin', 'liege_male', 'liege_de_reproduction', 'sauge', 'lavande',
                'armoise', 'origan', 'alfa', 'lentisque', 'ciste',
                'prevention_contre_les_incendies', 'autre', 'resiliation', 'date_resiliation'
            ];
            
            foreach ($columnsToDrop as $column) {
                if (Schema::hasColumn('contacts', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
