<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private const VALUES = ['admin', 'central', 'dranef', 'dpanef', 'zdtf', 'cpf', 'zdtfdpanef', 'brigade', 'dfp'];

    public function up(): void
    {
        $list = implode("','", self::VALUES);

        // ALTER COLUMN directly — avoids the drop/recreate that loses data
        DB::statement("ALTER TABLE users MODIFY COLUMN `role` ENUM('{$list}') NULL DEFAULT NULL");
    }

    public function down(): void
    {
        // Revert to the previous two-value enum (values outside it become NULL)
        DB::statement("ALTER TABLE users MODIFY COLUMN `role` ENUM('agency','admin') NOT NULL DEFAULT 'admin'");
    }
};
