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
        Schema::table('module_zone', function (Blueprint $table) {
            // This will add both 'created_at' and 'updated_at' columns to the table
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('module_zone', function (Blueprint $table) {
            // This safely removes them if you ever roll back the migration
            $table->dropTimestamps();
        });
    }
};