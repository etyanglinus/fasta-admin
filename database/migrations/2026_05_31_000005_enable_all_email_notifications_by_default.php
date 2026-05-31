<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('business_settings')) {
            DB::table('business_settings')
                ->where('key', 'like', '%\_mail\_status\_%')
                ->orWhere('key', 'like', '%\_mail\_status')
                ->update(['value' => '1']);
        }

        if (Schema::hasTable('notification_settings') && Schema::hasColumn('notification_settings', 'mail_status')) {
            DB::table('notification_settings')->update(['mail_status' => 'active']);
        }

        if (Schema::hasTable('store_notification_settings') && Schema::hasColumn('store_notification_settings', 'mail_status')) {
            DB::table('store_notification_settings')->update(['mail_status' => 'active']);
        }
    }

    public function down(): void
    {
        // Intentionally left blank. Restoring each store/admin notification preference
        // would require knowing the previous per-row state.
    }
};
