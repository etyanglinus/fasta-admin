<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BirthdayBonusSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BirthdayBonusController extends Controller
{
    public function show()
    {
        return response()->json(BirthdayBonusSetting::firstOrCreate([]));
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|boolean',
            'reward_type' => 'required|in:wallet,coupon,free_delivery',
            'bonus_amount' => 'required_if:reward_type,wallet,coupon|numeric|min:0',
            'validity_days' => 'required_if:reward_type,coupon,free_delivery|integer|min:1',
            'minimum_order_value' => 'nullable|numeric|min:0',
            'module_id' => 'nullable|integer|exists:modules,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $setting = BirthdayBonusSetting::firstOrCreate([]);
        $setting->status = $request->boolean('status');
        $setting->reward_type = $request->reward_type;
        $setting->bonus_amount = $request->reward_type === 'free_delivery' ? 0 : $request->bonus_amount;
        $setting->validity_days = $request->validity_days ?? $setting->validity_days;
        $setting->minimum_order_value = $request->minimum_order_value ?? 0;
        $setting->module_id = $request->module_id;
        $setting->save();

        return response()->json([
            'message' => translate('messages.updated_successfully'),
            'data' => $setting,
        ]);
    }
}
