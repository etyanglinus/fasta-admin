<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $this->replaceBrandInTable('business_settings', 'value');
        $this->replaceBrandInTable('data_settings', 'value');
        $this->replaceBrandInTable('email_templates', 'footer_text');

        if (!Schema::hasTable('fasta_prime_plans')) {
            Schema::create('fasta_prime_plans', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->text('description')->nullable();
                $table->decimal('price', 24, 3)->default(0);
                $table->enum('billing_period', ['weekly', 'monthly', 'yearly'])->default('monthly');
                $table->unsignedInteger('validity_days')->default(30);
                $table->boolean('free_delivery')->default(true);
                $table->decimal('free_delivery_limit', 24, 3)->nullable();
                $table->unsignedInteger('max_free_deliveries')->nullable();
                $table->json('features')->nullable();
                $table->boolean('status')->default(true);
                $table->timestamps();
            });
        }

        if (Schema::hasTable('fasta_prime_plans') && DB::table('fasta_prime_plans')->count() === 0) {
            DB::table('fasta_prime_plans')->insert([
                [
                    'name' => 'Fasta Prime Weekly',
                    'description' => 'A flexible weekly membership for frequent local deliveries.',
                    'price' => 199,
                    'billing_period' => 'weekly',
                    'validity_days' => 7,
                    'free_delivery' => 1,
                    'features' => json_encode(['Free delivery on eligible orders', 'Priority support', 'Member-only offers']),
                    'status' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'Fasta Prime Monthly',
                    'description' => 'Monthly membership with free delivery and extra customer perks.',
                    'price' => 499,
                    'billing_period' => 'monthly',
                    'validity_days' => 30,
                    'free_delivery' => 1,
                    'features' => json_encode(['Free delivery on eligible orders', 'Priority support', 'Member-only offers']),
                    'status' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'Fasta Prime Yearly',
                    'description' => 'Annual membership for loyal customers who order all year.',
                    'price' => 4999,
                    'billing_period' => 'yearly',
                    'validity_days' => 365,
                    'free_delivery' => 1,
                    'features' => json_encode(['Free delivery on eligible orders', 'Priority support', 'Member-only offers']),
                    'status' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        }

        if (!Schema::hasTable('fasta_prime_subscriptions')) {
            Schema::create('fasta_prime_subscriptions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->foreignId('plan_id')->nullable()->constrained('fasta_prime_plans')->nullOnDelete();
                $table->json('plan_snapshot')->nullable();
                $table->decimal('paid_amount', 24, 3)->default(0);
                $table->string('payment_method')->nullable();
                $table->string('payment_status')->default('unpaid');
                $table->string('transaction_reference')->nullable();
                $table->timestamp('start_date')->nullable();
                $table->timestamp('end_date')->nullable();
                $table->boolean('status')->default(false);
                $table->boolean('is_canceled')->default(false);
                $table->timestamp('canceled_at')->nullable();
                $table->timestamps();
            });
        }

        if (Schema::hasTable('orders')) {
            Schema::table('orders', function (Blueprint $table) {
                if (!Schema::hasColumn('orders', 'fasta_prime_subscription_id')) {
                    $table->unsignedBigInteger('fasta_prime_subscription_id')->nullable()->after('free_delivery_by');
                }
                if (!Schema::hasColumn('orders', 'fasta_prime_delivery_discount')) {
                    $table->decimal('fasta_prime_delivery_discount', 24, 3)->default(0)->after('fasta_prime_subscription_id');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('orders')) {
            Schema::table('orders', function (Blueprint $table) {
                if (Schema::hasColumn('orders', 'fasta_prime_delivery_discount')) {
                    $table->dropColumn('fasta_prime_delivery_discount');
                }
                if (Schema::hasColumn('orders', 'fasta_prime_subscription_id')) {
                    $table->dropColumn('fasta_prime_subscription_id');
                }
            });
        }

        Schema::dropIfExists('fasta_prime_subscriptions');
        Schema::dropIfExists('fasta_prime_plans');
    }

    private function replaceBrandInTable(string $table, string $column): void
    {
        if (!Schema::hasTable($table) || !Schema::hasColumn($table, $column)) {
            return;
        }

        DB::table($table)
            ->where($column, 'like', '%6amMart%')
            ->update([$column => DB::raw("REPLACE($column, '6amMart', 'Fasta Deliveries')")]);
        DB::table($table)
            ->where($column, 'like', '%6ammart%')
            ->update([$column => DB::raw("REPLACE($column, '6ammart', 'Fasta Deliveries')")]);
    }
};
