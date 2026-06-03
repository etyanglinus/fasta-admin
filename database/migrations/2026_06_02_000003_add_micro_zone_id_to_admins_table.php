<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('admins', 'micro_zone_id')) {
            Schema::table('admins', function (Blueprint $table) {
                $table->foreignId('micro_zone_id')->nullable()->after('zone_id')->constrained('micro_zones')->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('admins', 'micro_zone_id')) {
            Schema::table('admins', function (Blueprint $table) {
                $table->dropConstrainedForeignId('micro_zone_id');
            });
        }
    }
};
