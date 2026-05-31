<?php

namespace App\Http\Controllers\Api\V1;

use App\CentralLogics\CustomerLogic;
use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Library\Payer;
use App\Library\Payment as PaymentInfo;
use App\Library\Receiver;
use App\Models\BusinessSetting;
use App\Models\FastaPrimePlan;
use App\Models\FastaPrimeSubscription;
use App\Models\User;
use App\Traits\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class FastaPrimeController extends Controller
{
    public function plans()
    {
        $plans = FastaPrimePlan::where('status', 1)->latest()->get();

        return response()->json(['plans' => $plans], 200);
    }

    public function current(Request $request)
    {
        $subscription = FastaPrimeSubscription::with('plan')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->first();

        return response()->json([
            'is_prime' => (bool) $subscription?->status && $subscription?->payment_status === 'paid' && $subscription?->end_date >= now() && !$subscription?->is_canceled,
            'subscription' => $subscription,
        ], 200);
    }

    public function subscribe(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'plan_id' => 'required|exists:fasta_prime_plans,id',
            'payment_method' => 'required|string',
            'payment_platform' => 'nullable|in:app,web',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $plan = FastaPrimePlan::where('status', 1)->find($request->plan_id);
        if (!$plan) {
            return response()->json(['errors' => [['code' => 'plan', 'message' => translate('messages.Plan_not_available')]]], 403);
        }

        $user = User::find($request->user()->id);
        $subscription = $this->createPendingSubscription($user, $plan, $request->payment_method);

        if ($plan->price <= 0) {
            $this->activateSubscription($subscription, 'free');
            return response()->json(['message' => translate('messages.Subscription_successful'), 'subscription' => $subscription->fresh('plan')], 200);
        }

        if ($request->payment_method === 'wallet') {
            if ((float) $user->wallet_balance < (float) $plan->price) {
                return response()->json(['errors' => [['code' => 'wallet', 'message' => translate('messages.Insufficient_balance_in_wallet')]]], 403);
            }

            DB::transaction(function () use ($subscription, $user, $plan) {
                $walletTransaction = CustomerLogic::create_wallet_transaction($user->id, $plan->price, 'fasta_prime_subscription', 'fasta_prime_' . $subscription->id);
                if (!$walletTransaction) {
                    abort(403, translate('messages.Wallet_payment_failed'));
                }
                $this->activateSubscription($subscription, 'wallet');
            });

            return response()->json(['message' => translate('messages.Subscription_successful'), 'subscription' => $subscription->fresh('plan')], 200);
        }

        $digitalPayment = Helpers::get_business_settings('digital_payment');
        if (data_get($digitalPayment, 'status') == 0) {
            return response()->json(['errors' => [['code' => 'payment', 'message' => translate('messages.digital_payment_is_disable')]]], 403);
        }

        $payer = new Payer($user->full_name, $user->email, $user->phone, '');
        $storeLogo = BusinessSetting::where(['key' => 'logo'])->first();
        $currency = BusinessSetting::where(['key' => 'currency'])->first()?->value;
        $paymentInfo = new PaymentInfo(
            success_hook: 'fasta_prime_success',
            failure_hook: 'fasta_prime_failed',
            currency_code: $currency,
            payment_method: $request->payment_method,
            payment_platform: $request->payment_platform ?? 'app',
            payer_id: $user->id,
            receiver_id: '100',
            additional_data: [
                'business_name' => BusinessSetting::where(['key' => 'business_name'])->first()?->value,
                'business_logo' => Helpers::get_full_url('business', $storeLogo?->value, $storeLogo?->storage[0]?->value ?? 'public'),
                'plan_name' => $plan->name,
            ],
            payment_amount: $plan->price,
            external_redirect_link: $request->has('callback') ? $request['callback'] : session('callback'),
            attribute: 'fasta_prime_subscriptions',
            attribute_id: $subscription->id
        );

        return response()->json([
            'redirect_link' => Payment::generate_link($payer, $paymentInfo, new Receiver('Fasta Deliveries', $storeLogo?->value ?? '')),
            'subscription_id' => $subscription->id,
        ], 200);
    }

    public function cancel(Request $request)
    {
        $subscription = FastaPrimeSubscription::where('user_id', $request->user()->id)
            ->active()
            ->latest()
            ->first();

        if (!$subscription) {
            return response()->json(['errors' => [['code' => 'subscription', 'message' => translate('messages.No_active_subscription_found')]]], 404);
        }

        $subscription->update([
            'is_canceled' => 1,
            'canceled_at' => now(),
        ]);

        return response()->json(['message' => translate('messages.Subscription_canceled')], 200);
    }

    private function createPendingSubscription(User $user, FastaPrimePlan $plan, string $paymentMethod): FastaPrimeSubscription
    {
        return FastaPrimeSubscription::create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'plan_snapshot' => $plan->toArray(),
            'paid_amount' => $plan->price,
            'payment_method' => $paymentMethod,
            'payment_status' => 'unpaid',
            'status' => 0,
        ]);
    }

    private function activateSubscription(FastaPrimeSubscription $subscription, string $paymentMethod): void
    {
        FastaPrimeSubscription::where('user_id', $subscription->user_id)
            ->where('id', '!=', $subscription->id)
            ->where('status', 1)
            ->update(['status' => 0, 'is_canceled' => 1, 'canceled_at' => now()]);

        $validityDays = (int) data_get($subscription->plan_snapshot, 'validity_days', 30);
        $subscription->update([
            'payment_method' => $paymentMethod,
            'payment_status' => 'paid',
            'start_date' => now(),
            'end_date' => now()->addDays($validityDays),
            'status' => 1,
            'is_canceled' => 0,
        ]);
    }
}
