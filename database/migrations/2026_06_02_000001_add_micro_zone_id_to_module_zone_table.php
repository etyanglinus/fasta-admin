<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('module_zone', 'micro_zone_id')) {
            Schema::table('module_zone', function (Blueprint $table) {
                $table->foreignId('micro_zone_id')->nullable()->after('zone_id')->constrained('micro_zones')->nullOnDelete();
            });
        }

        if (! $this->indexExists('module_zone', 'module_zone_micro_zone_lookup')) {
            Schema::table('module_zone', function (Blueprint $table) {
                $table->index(['zone_id', 'micro_zone_id', 'module_id'], 'module_zone_micro_zone_lookup');
            });
        }
    }

    public function down(): void
    {
        if ($this->indexExists('module_zone', 'module_zone_micro_zone_lookup')) {
            Schema::table('module_zone', function (Blueprint $table) {
                $table->dropIndex('module_zone_micro_zone_lookup');
            });
        }

        if (Schema::hasColumn('module_zone', 'micro_zone_id')) {
            Schema::table('module_zone', function (Blueprint $table) {
                $table->dropConstrainedForeignId('micro_zone_id');
            });
        }
    }

    private function indexExists(string $table, string $index): bool
    {
        return collect(DB::select("SHOW INDEXES FROM {$table}"))->contains(fn ($row) => $row->Key_name === $index);
    }
};
