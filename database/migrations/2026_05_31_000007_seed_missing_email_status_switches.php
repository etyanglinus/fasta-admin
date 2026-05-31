<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('business_settings')) {
            $hasCreatedAt = Schema::hasColumn('business_settings', 'created_at');
            $hasUpdatedAt = Schema::hasColumn('business_settings', 'updated_at');
            $tabsByType = [
                'user' => [
                    'unsuspend',
                    'suspend',
                    'order-verification',
                    'registration-otp',
                    'registration',
                    'forgot-password',
                    'offline-payment-deny',
                    'refund-request-deny',
                    'add-fund',
                    'refund-order',
                    'offline-payment-approve',
                    'pos-registration',
                    'place-order',
                    'login-otp',
                ],
                'store' => [
                    'approve',
                    'advertisement-resume',
                    'advertisement-approved',
                    'advertisement-pause',
                    'advertisement-deny',
                    'advertisement-create',
                    'deny',
                    'campaign-request',
                    'campaign-deny',
                    'campaign-approve',
                    'product-deny',
                    'product-approved',
                    'registration',
                    'subscription-cancel',
                    'subscription-deadline',
                    'subscription-plan_upadte',
                    'subscription-renew',
                    'subscription-successful',
                    'subscription-shift',
                    'unsuspend',
                    'suspend',
                    'withdraw-deny',
                    'withdraw-approve',
                ],
                'dm' => [
                    'withdraw-deny',
                    'forgot-password',
                    'deny',
                    'cash-collect',
                    'suspend',
                    'approve',
                    'registration',
                    'unsuspend',
                    'withdraw-approve',
                ],
                'admin' => [
                    'campaign-request',
                    'dm-registration',
                    'dm-withdraw-request',
                    'forgot-password',
                    'login',
                    'new-advertisement',
                    'refund-request',
                    'withdraw-request',
                    'store-registration',
                    'update-advertisement',
                ],
            ];

            foreach ($tabsByType as $type => $tabs) {
                foreach ($tabs as $tab) {
                    $key = $this->mailStatusKey($tab, $type);
                    $values = ['value' => '1'];

                    if ($hasCreatedAt) {
                        $values['created_at'] = now();
                    }

                    if ($hasUpdatedAt) {
                        $values['updated_at'] = now();
                    }

                    DB::table('business_settings')->updateOrInsert(
                        ['key' => $key],
                        $values
                    );
                }
            }

            $updateValues = ['value' => '1'];

            if ($hasUpdatedAt) {
                $updateValues['updated_at'] = now();
            }

            DB::table('business_settings')
                ->where('key', 'like', '%\_mail\_status\_%')
                ->orWhere('key', 'like', '%\_mail\_status')
                ->update($updateValues);
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
        // These defaults are intentionally not rolled back because they represent
        // user-visible notification preferences that may be changed after deploy.
    }

    private function mailStatusKey(string $tab, string $type): string
    {
        $specialCases = [
            'forgot-password' => 'forget_password',
        ];

        return ($specialCases[$tab] ?? str_replace('-', '_', $tab)) . '_mail_status_' . $type;
    }
};
