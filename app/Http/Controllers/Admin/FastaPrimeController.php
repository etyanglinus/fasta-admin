<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FastaPrimePlan;
use App\Models\FastaPrimeSubscription;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;

class FastaPrimeController extends Controller
{
    public function index(Request $request)
    {
        $plans = FastaPrimePlan::withCount(['subscriptions', 'activeSubscriptions'])
            ->latest()
            ->paginate(config('default_pagination'));
        $editingPlan = $request->edit ? FastaPrimePlan::find($request->edit) : null;

        return view('admin-views.fasta-prime.index', compact('plans', 'editingPlan'));
    }

    public function store(Request $request)
    {
        $data = $this->validatedData($request);
        FastaPrimePlan::create($data);

        Toastr::success(translate('messages.Fasta_Prime_plan_saved_successfully'));
        return back();
    }

    public function update(Request $request, FastaPrimePlan $plan)
    {
        $plan->update($this->validatedData($request));

        Toastr::success(translate('messages.Fasta_Prime_plan_updated_successfully'));
        return redirect()->route('admin.business-settings.fasta-prime.index');
    }

    public function status(FastaPrimePlan $plan)
    {
        $plan->update(['status' => !$plan->status]);

        Toastr::success(translate('messages.Status_updated_successfully'));
        return back();
    }

    public function subscribers(Request $request)
    {
        $key = explode(' ', $request['search']);
        $subscriptions = FastaPrimeSubscription::with(['user:id,f_name,l_name,phone,email', 'plan:id,name'])
            ->when($request->status === 'active', fn ($query) => $query->active())
            ->when($request->status === 'expired', fn ($query) => $query->where('end_date', '<', now()))
            ->when($request['search'], function ($query) use ($key) {
                $query->whereHas('user', function ($query) use ($key) {
                    foreach ($key as $value) {
                        $query->where(function ($query) use ($value) {
                            $query->orWhere('f_name', 'like', "%{$value}%")
                                ->orWhere('l_name', 'like', "%{$value}%")
                                ->orWhere('phone', 'like', "%{$value}%")
                                ->orWhere('email', 'like', "%{$value}%");
                        });
                    }
                });
            })
            ->latest()
            ->paginate(config('default_pagination'));

        return view('admin-views.fasta-prime.subscribers', compact('subscriptions'));
    }

    public function cancel(FastaPrimeSubscription $subscription)
    {
        $subscription->update([
            'status' => 0,
            'is_canceled' => 1,
            'canceled_at' => now(),
        ]);

        Toastr::success(translate('messages.Subscription_canceled'));
        return back();
    }

    private function validatedData(Request $request): array
    {
        $data = $request->validate([
            'name' => 'required|max:191',
            'description' => 'nullable|max:1000',
            'price' => 'required|numeric|min:0|max:999999999',
            'billing_period' => 'required|in:weekly,monthly,yearly',
            'validity_days' => 'required|integer|min:1|max:36160',
            'free_delivery_limit' => 'nullable|numeric|min:0|max:999999999',
            'max_free_deliveries' => 'nullable|integer|min:1|max:999999999',
            'features' => 'nullable|string|max:2000',
        ]);

        $data['free_delivery'] = $request->has('free_delivery');
        $data['status'] = $request->has('status');
        $data['features'] = collect(preg_split('/\r\n|\r|\n/', $request->features ?? ''))
            ->map(fn ($feature) => trim($feature))
            ->filter()
            ->values()
            ->all();

        return $data;
    }
}
