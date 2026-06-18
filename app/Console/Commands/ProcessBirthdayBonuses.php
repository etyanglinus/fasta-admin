<?php

namespace App\Console\Commands;

use App\CentralLogics\CustomerLogic;
use App\CentralLogics\Helpers;
use App\Models\BirthdayBonusAward;
use App\Models\BirthdayBonusSetting;
use App\Models\Coupon;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ProcessBirthdayBonuses extends Command
{
    protected $signature = 'birthday-bonus:process';

    protected $description = 'Issue configured birthday rewards once per customer per year.';

    public function handle(): int
    {
        $setting = BirthdayBonusSetting::first();
        if (! $setting?->status) {
            $this->info('Birthday bonus is disabled.');
            return self::SUCCESS;
        }

        $today = now();
        $count = 0;

        User::whereNotNull('date_of_birth')
            ->whereMonth('date_of_birth', $today->month)
            ->whereDay('date_of_birth', $today->day)
            ->whereDoesntHave('birthdayBonusAwards', fn ($query) => $query->where('award_year', $today->year))
            ->chunkById(100, function ($users) use ($setting, $today, &$count) {
                foreach ($users as $user) {
                    DB::transaction(function () use ($setting, $today, $user, &$count) {
                        $award = BirthdayBonusAward::firstOrCreate(
                            ['user_id' => $user->id, 'award_year' => $today->year],
                            [
                                'reward_type' => $setting->reward_type,
                                'amount' => $setting->bonus_amount,
                                'reference' => 'birthday_bonus_' . $today->year,
                                'awarded_at' => now(),
                            ]
                        );

                        if (! $award->wasRecentlyCreated) {
                            return;
                        }

                        if ($setting->reward_type === 'wallet') {
                            CustomerLogic::create_wallet_transaction($user->id, (float) $setting->bonus_amount, 'birthday_bonus', $award->reference);
                        } else {
                            $coupon = $this->createCoupon($user, $setting, $today);
                            $award->coupon_id = $coupon->id;
                            $award->reference = $coupon->code;
                            $award->save();
                        }

                        $this->notifyCustomer($user, $setting, $award);
                        $count++;
                    });
                }
            });

        $this->info("Birthday bonuses issued: {$count}");
        return self::SUCCESS;
    }

    private function createCoupon(User $user, BirthdayBonusSetting $setting, $today): Coupon
    {
        $code = strtoupper('BDAY-' . $user->id . '-' . $today->year . '-' . Str::random(5));

        return Coupon::create([
            'title' => translate('messages.birthday_bonus'),
            'code' => $code,
            'start_date' => $today->toDateString(),
            'expire_date' => $today->copy()->addDays($setting->validity_days)->toDateString(),
            'min_purchase' => $setting->minimum_order_value,
            'max_discount' => $setting->reward_type === 'free_delivery' ? 0 : $setting->bonus_amount,
            'discount' => $setting->reward_type === 'free_delivery' ? 0 : $setting->bonus_amount,
            'discount_type' => 'amount',
            'coupon_type' => $setting->reward_type === 'free_delivery' ? 'free_delivery' : 'default',
            'limit' => 1,
            'status' => 1,
            'data' => json_encode([]),
            'total_uses' => 0,
            'module_id' => $setting->module_id ?? 0,
            'created_by' => 'admin',
            'customer_id' => (string) $user->id,
            'slug' => Str::slug($code),
            'store_id' => null,
        ]);
    }

    private function notifyCustomer(User $user, BirthdayBonusSetting $setting, BirthdayBonusAward $award): void
    {
        $description = $setting->reward_type === 'wallet'
            ? translate('messages.birthday_wallet_bonus_added')
            : translate('messages.birthday_coupon_bonus_added');

        $data = [
            'title' => translate('messages.happy_birthday'),
            'description' => $description,
            'order_id' => '',
            'image' => '',
            'type' => 'birthday_bonus',
        ];

        if ($user->cm_firebase_token) {
            Helpers::send_push_notif_to_device($user->cm_firebase_token, $data);
        }

        DB::table('user_notifications')->insert([
            'data' => json_encode($data),
            'user_id' => $user->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        try {
            if (config('mail.status') && $user->email) {
                Mail::raw($description . ($award->reference ? "\n\nCode/reference: {$award->reference}" : ''), function ($message) use ($user) {
                    $message->to($user->getRawOriginal('email'))->subject(translate('messages.happy_birthday'));
                });
            }
        } catch (\Throwable $exception) {
            info('birthday bonus mail failed', [$exception->getMessage()]);
        }
    }
}
