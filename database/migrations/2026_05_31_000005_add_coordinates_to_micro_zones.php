<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('micro_zones', 'coordinates')) {
            DB::statement('ALTER TABLE micro_zones ADD coordinates POLYGON NULL AFTER description');
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('micro_zones', 'coordinates')) {
            DB::statement('ALTER TABLE micro_zones DROP COLUMN coordinates');
        }
    }
};
