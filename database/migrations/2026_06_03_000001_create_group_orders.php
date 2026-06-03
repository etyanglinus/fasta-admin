<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('group_orders', function (Blueprint $table) {
            $table->id();
            $table->string('code', 24)->unique();
            $table->foreignId('host_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->unsignedBigInteger('host_guest_id')->nullable();
            $table->foreignId('store_id')->nullable()->constrained('stores')->nullOnDelete();
            $table->foreignId('module_id')->nullable()->constrained('modules')->nullOnDelete();
            $table->enum('status', ['open', 'locked', 'placed', 'cancelled'])->default('open');
            $table->enum('payment_mode', ['single', 'split'])->default('single');
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('placed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('group_order_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_order_id')->constrained('group_orders')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->unsignedBigInteger('guest_id')->nullable();
            $table->string('name')->nullable();
            $table->boolean('is_host')->default(false);
            $table->timestamps();
            $table->unique(['group_order_id', 'user_id']);
            $table->index(['group_order_id', 'guest_id']);
        });

        Schema::table('carts', function (Blueprint $table) {
            $table->foreignId('group_order_id')->nullable()->after('module_id')->constrained('group_orders')->nullOnDelete();
            $table->foreignId('group_order_participant_id')->nullable()->after('group_order_id')->constrained('group_order_participants')->nullOnDelete();
            $table->string('participant_name')->nullable()->after('group_order_participant_id');
            $table->index(['group_order_id', 'module_id']);
        });
    }

    public function down(): void
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->dropConstrainedForeignId('group_order_id');
            $table->dropConstrainedForeignId('group_order_participant_id');
            $table->dropColumn('participant_name');
        });

        Schema::dropIfExists('group_order_participants');
        Schema::dropIfExists('group_orders');
    }
};
