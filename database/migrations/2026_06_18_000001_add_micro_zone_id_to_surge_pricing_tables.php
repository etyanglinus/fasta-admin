<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('surge_prices', function (Blueprint $table) {
            if (! Schema::hasColumn('surge_prices', 'micro_zone_id')) {
                $table->foreignId('micro_zone_id')->nullable()->after('zone_id')->constrained('micro_zones')->nullOnDelete();
            }
        });

        Schema::table('surge_price_dates', function (Blueprint $table) {
            if (! Schema::hasColumn('surge_price_dates', 'micro_zone_id')) {
                $table->foreignId('micro_zone_id')->nullable()->after('zone_id')->constrained('micro_zones')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('surge_price_dates', function (Blueprint $table) {
            if (Schema::hasColumn('surge_price_dates', 'micro_zone_id')) {
                $table->dropConstrainedForeignId('micro_zone_id');
            }
        });

        Schema::table('surge_prices', function (Blueprint $table) {
            if (Schema::hasColumn('surge_prices', 'micro_zone_id')) {
                $table->dropConstrainedForeignId('micro_zone_id');
            }
        });
    }
};
