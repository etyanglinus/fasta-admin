<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('login_activity_logs')) {
            Schema::create('login_activity_logs', function (Blueprint $table) {
                $table->id();
                $table->string('user_type', 40);
                $table->unsignedBigInteger('user_id');
                $table->string('name')->nullable();
                $table->string('email')->nullable();
                $table->string('ip_address', 80)->nullable();
                $table->string('device_type', 40)->nullable();
                $table->string('os', 80)->nullable();
                $table->string('browser', 80)->nullable();
                $table->text('user_agent')->nullable();
                $table->timestamp('logged_in_at')->nullable();
                $table->timestamps();
                $table->index(['user_type', 'user_id']);
                $table->index('logged_in_at');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('login_activity_logs');
    }
};
