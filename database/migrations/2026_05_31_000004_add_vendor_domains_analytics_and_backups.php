<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            if (!Schema::hasColumn('stores', 'custom_domain')) {
                $table->string('custom_domain')->nullable()->unique()->after('slug');
            }
        });

        if (!Schema::hasTable('store_visit_logs')) {
            Schema::create('store_visit_logs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('store_id')->constrained('stores')->cascadeOnDelete();
                $table->date('visit_date');
                $table->string('source', 30)->default('web');
                $table->unsignedBigInteger('visit_count')->default(0);
                $table->timestamps();
                $table->unique(['store_id', 'visit_date', 'source']);
                $table->index(['visit_date', 'source']);
            });
        }

        if (!Schema::hasTable('system_backups')) {
            Schema::create('system_backups', function (Blueprint $table) {
                $table->id();
                $table->string('file_name');
                $table->string('disk')->default('local');
                $table->unsignedBigInteger('size')->default(0);
                $table->string('status')->default('completed');
                $table->text('message')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('system_backups');
        Schema::dropIfExists('store_visit_logs');

        Schema::table('stores', function (Blueprint $table) {
            if (Schema::hasColumn('stores', 'custom_domain')) {
                $table->dropUnique(['custom_domain']);
                $table->dropColumn('custom_domain');
            }
        });
    }
};
