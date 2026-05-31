<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('business_settings')) {
            return;
        }

        $values = ['value' => '0'];

        if (Schema::hasColumn('business_settings', 'created_at')) {
            $values['created_at'] = now();
        }

        if (Schema::hasColumn('business_settings', 'updated_at')) {
            $values['updated_at'] = now();
        }

        DB::table('business_settings')->updateOrInsert(
            ['key' => 'facebook_login_status'],
            $values
        );

        $socialLogin = DB::table('business_settings')->where('key', 'social_login')->first();

        if ($socialLogin) {
            $providers = json_decode($socialLogin->value, true);

            if (is_array($providers)) {
                $providers = array_values(array_filter($providers, function ($provider) {
                    return ($provider['login_medium'] ?? null) !== 'facebook';
                }));

                DB::table('business_settings')
                    ->where('key', 'social_login')
                    ->update(array_filter([
                        'value' => json_encode($providers),
                        'updated_at' => Schema::hasColumn('business_settings', 'updated_at') ? now() : null,
                    ]));
            }
        }
    }

    public function down(): void
    {
        // Facebook login was intentionally removed and is not restored automatically.
    }
};
