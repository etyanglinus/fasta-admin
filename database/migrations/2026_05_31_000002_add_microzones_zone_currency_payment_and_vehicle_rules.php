<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('micro_zones')) {
            Schema::create('micro_zones', function (Blueprint $table) {
                $table->id();
                $table->foreignId('zone_id')->constrained('zones')->cascadeOnDelete();
                $table->string('name');
                $table->text('description')->nullable();
                $table->boolean('status')->default(true);
                $table->timestamps();
            });
        }

        Schema::table('zones', function (Blueprint $table) {
            if (! Schema::hasColumn('zones', 'currency_code')) {
                $table->string('currency_code', 20)->nullable()->after('display_name');
            }
        });

        if (! Schema::hasTable('zone_payment_gateways')) {
            Schema::create('zone_payment_gateways', function (Blueprint $table) {
                $table->id();
                $table->foreignId('zone_id')->constrained('zones')->cascadeOnDelete();
                $table->string('gateway_key');
                $table->boolean('status')->default(true);
                $table->timestamps();
                $table->unique(['zone_id', 'gateway_key']);
            });
        }

        Schema::table('stores', function (Blueprint $table) {
            if (! Schema::hasColumn('stores', 'micro_zone_id')) {
                $table->foreignId('micro_zone_id')->nullable()->after('zone_id')->constrained('micro_zones')->nullOnDelete();
            }
        });

        Schema::table('delivery_men', function (Blueprint $table) {
            if (! Schema::hasColumn('delivery_men', 'micro_zone_id')) {
                $table->foreignId('micro_zone_id')->nullable()->after('zone_id')->constrained('micro_zones')->nullOnDelete();
            }
        });

        Schema::table('d_m_vehicles', function (Blueprint $table) {
            if (! Schema::hasColumn('d_m_vehicles', 'module_id')) {
                $table->foreignId('module_id')->nullable()->after('type')->constrained('modules')->nullOnDelete();
            }
            if (! Schema::hasColumn('d_m_vehicles', 'maximum_weight')) {
                $table->decimal('maximum_weight', 16, 3)->nullable()->after('maximum_coverage_area');
            }
        });
    }

    public function down(): void
    {
        Schema::table('d_m_vehicles', function (Blueprint $table) {
            if (Schema::hasColumn('d_m_vehicles', 'module_id')) {
                $table->dropConstrainedForeignId('module_id');
            }
            if (Schema::hasColumn('d_m_vehicles', 'maximum_weight')) {
                $table->dropColumn('maximum_weight');
            }
        });

        Schema::table('delivery_men', function (Blueprint $table) {
            if (Schema::hasColumn('delivery_men', 'micro_zone_id')) {
                $table->dropConstrainedForeignId('micro_zone_id');
            }
        });

        Schema::table('stores', function (Blueprint $table) {
            if (Schema::hasColumn('stores', 'micro_zone_id')) {
                $table->dropConstrainedForeignId('micro_zone_id');
            }
        });

        Schema::dropIfExists('zone_payment_gateways');

        Schema::table('zones', function (Blueprint $table) {
            if (Schema::hasColumn('zones', 'currency_code')) {
                $table->dropColumn('currency_code');
            }
        });

        Schema::dropIfExists('micro_zones');
    }
};
