<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'date_of_birth')) {
                $table->date('date_of_birth')->nullable()->after('email');
            }
            if (! Schema::hasColumn('users', 'date_of_birth_updated_at')) {
                $table->timestamp('date_of_birth_updated_at')->nullable()->after('date_of_birth');
            }
        });

        Schema::create('birthday_bonus_settings', function (Blueprint $table) {
            $table->id();
            $table->boolean('status')->default(false);
            $table->enum('reward_type', ['wallet', 'coupon', 'free_delivery'])->default('wallet');
            $table->double('bonus_amount', 24, 3)->default(0);
            $table->unsignedInteger('validity_days')->default(7);
            $table->double('minimum_order_value', 24, 3)->default(0);
            $table->unsignedBigInteger('module_id')->nullable();
            $table->timestamps();
        });

        Schema::create('birthday_bonus_awards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->unsignedSmallInteger('award_year');
            $table->enum('reward_type', ['wallet', 'coupon', 'free_delivery']);
            $table->double('amount', 24, 3)->default(0);
            $table->foreignId('coupon_id')->nullable()->constrained('coupons')->nullOnDelete();
            $table->string('reference')->nullable();
            $table->timestamp('awarded_at')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'award_year']);
        });

        DB::table('birthday_bonus_settings')->insert([
            'status' => 0,
            'reward_type' => 'wallet',
            'bonus_amount' => 0,
            'validity_days' => 7,
            'minimum_order_value' => 0,
            'module_id' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('birthday_bonus_awards');
        Schema::dropIfExists('birthday_bonus_settings');

        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'date_of_birth_updated_at')) {
                $table->dropColumn('date_of_birth_updated_at');
            }
            if (Schema::hasColumn('users', 'date_of_birth')) {
                $table->dropColumn('date_of_birth');
            }
        });
    }
};
