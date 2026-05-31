<?php

use App\Models\BusinessSetting;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('order_route_logs')) {
            Schema::create('order_route_logs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
                $table->foreignId('delivery_man_id')->nullable()->constrained('delivery_men')->nullOnDelete();
                $table->string('event_type')->nullable()->index();
                $table->decimal('latitude', 11, 8)->nullable();
                $table->decimal('longitude', 11, 8)->nullable();
                $table->decimal('accuracy', 10, 2)->nullable();
                $table->decimal('heading', 10, 2)->nullable();
                $table->decimal('speed', 10, 2)->nullable();
                $table->timestamp('recorded_at')->nullable()->index();
                $table->json('metadata')->nullable();
                $table->timestamps();

                $table->index(['order_id', 'recorded_at']);
                $table->index(['delivery_man_id', 'recorded_at']);
            });
        }

        BusinessSetting::updateOrCreate(
            ['key' => 'platform_maintenance'],
            ['value' => json_encode($this->defaultMaintenance())]
        );

        BusinessSetting::updateOrCreate(
            ['key' => 'instant_settlement_after_delivery'],
            ['value' => '1']
        );
    }

    public function down(): void
    {
        Schema::dropIfExists('order_route_logs');
        BusinessSetting::whereIn('key', ['platform_maintenance', 'instant_settlement_after_delivery'])->delete();
    }

    private function defaultMaintenance(): array
    {
        $item = ['status' => false, 'until' => null, 'message' => 'Fasta Deliveries is under maintenance. Please try again shortly.'];

        return [
            'all' => $item,
            'user_app' => $item,
            'user_react_web' => $item,
            'vendor_web' => $item,
            'vendor_app' => $item,
            'deliveryman_app' => $item,
        ];
    }
};
