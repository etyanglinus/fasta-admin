<?php

namespace App\Http\Controllers\Api\V1;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\GroupOrder;
use App\Models\GroupOrderParticipant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class GroupOrderController extends Controller
{
    public function start(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'guest_id' => $request->user ? 'nullable' : 'required',
            'store_id' => 'nullable|exists:stores,id',
            'participant_name' => 'nullable|string|max:120',
            'deadline_minutes' => 'nullable|integer|min:5|max:1440',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $groupOrder = GroupOrder::create([
            'code' => $this->generateCode(),
            'host_user_id' => $request->user?->id,
            'host_guest_id' => $request->user ? null : $request->guest_id,
            'store_id' => $request->store_id,
            'module_id' => getModuleId($request->header('moduleId')),
            'payment_mode' => 'single',
            'expires_at' => now()->addMinutes((int) ($request->deadline_minutes ?: 60)),
        ]);

        $participant = $this->participantFor($groupOrder, $request, true, $request->participant_name);

        return response()->json($this->formatGroupOrder($groupOrder->fresh(['participants', 'carts.item']), $participant), 201);
    }

    public function join(Request $request, string $code)
    {
        $validator = Validator::make($request->all(), [
            'guest_id' => $request->user ? 'nullable' : 'required',
            'participant_name' => 'nullable|string|max:120',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $groupOrder = $this->findOpenGroupOrder($code);
        if (! $groupOrder) {
            return response()->json(['errors' => [['code' => 'group_order', 'message' => translate('messages.not_found')]]], 404);
        }

        $participant = $this->participantFor($groupOrder, $request, false, $request->participant_name);

        return response()->json($this->formatGroupOrder($groupOrder->fresh(['participants', 'carts.item']), $participant), 200);
    }

    public function show(Request $request, string $code)
    {
        $groupOrder = GroupOrder::with(['participants', 'carts.item'])->where('code', $code)->first();
        if (! $groupOrder) {
            return response()->json(['errors' => [['code' => 'group_order', 'message' => translate('messages.not_found')]]], 404);
        }

        return response()->json($this->formatGroupOrder($groupOrder, $this->participantFor($groupOrder, $request, false)), 200);
    }

    public function lock(Request $request, string $code)
    {
        $groupOrder = GroupOrder::where('code', $code)->first();
        if (! $groupOrder || ! $groupOrder->isHost($request->user, $request->guest_id)) {
            return response()->json(['errors' => [['code' => 'group_order', 'message' => translate('messages.not_found')]]], 404);
        }

        $groupOrder->status = 'locked';
        $groupOrder->save();

        return response()->json($this->formatGroupOrder($groupOrder->fresh(['participants', 'carts.item']), $this->participantFor($groupOrder, $request, true)), 200);
    }

    public function cancel(Request $request, string $code)
    {
        $groupOrder = GroupOrder::where('code', $code)->first();
        if (! $groupOrder || ! $groupOrder->isHost($request->user, $request->guest_id)) {
            return response()->json(['errors' => [['code' => 'group_order', 'message' => translate('messages.not_found')]]], 404);
        }

        $groupOrder->status = 'cancelled';
        $groupOrder->save();
        Cart::where('group_order_id', $groupOrder->id)->delete();

        return response()->json($this->formatGroupOrder($groupOrder->fresh(['participants', 'carts.item']), $this->participantFor($groupOrder, $request, true)), 200);
    }

    private function findOpenGroupOrder(string $code): ?GroupOrder
    {
        $groupOrder = GroupOrder::where('code', $code)->first();

        if (! $groupOrder || ! in_array($groupOrder->status, ['open', 'locked'], true) || $groupOrder->isExpired()) {
            return null;
        }

        return $groupOrder;
    }

    private function participantFor(GroupOrder $groupOrder, Request $request, bool $isHost = false, ?string $name = null): GroupOrderParticipant
    {
        $identity = [
            'group_order_id' => $groupOrder->id,
            'user_id' => $request->user?->id,
            'guest_id' => $request->user ? null : $request->guest_id,
        ];

        $participant = GroupOrderParticipant::where($identity)->first();
        if ($participant) {
            if ($name && $participant->name !== $name) {
                $participant->name = $name;
                $participant->save();
            }
            return $participant;
        }

        return GroupOrderParticipant::create($identity + [
            'name' => $name ?: ($request->user ? trim(($request->user->f_name ?? '').' '.($request->user->l_name ?? '')) : null),
            'is_host' => $isHost,
        ]);
    }

    private function formatGroupOrder(GroupOrder $groupOrder, ?GroupOrderParticipant $participant = null): array
    {
        $host = $groupOrder->participants->firstWhere('is_host', true);
        $isHost = (bool) $participant?->is_host;
        $hostName = $host?->name ?: 'the group order creator';

        return [
            'id' => $groupOrder->id,
            'code' => $groupOrder->code,
            'invite_link' => url('/group-order/'.$groupOrder->code),
            'status' => $groupOrder->status,
            'payment_mode' => 'host_pays',
            'payment_responsibility' => 'creator',
            'checkout_allowed' => $isHost,
            'payment_notice' => $isHost ? null : 'Please liaise with '.$hostName.' for payment of the products you add to this group order.',
            'store_id' => $groupOrder->store_id,
            'module_id' => $groupOrder->module_id,
            'expires_at' => $groupOrder->expires_at,
            'host' => $host,
            'participant' => $participant,
            'participants' => $groupOrder->participants,
            'carts' => $groupOrder->carts->map(function ($cart) {
                $cart->add_on_ids = is_string($cart->add_on_ids) ? json_decode($cart->add_on_ids, true) : $cart->add_on_ids;
                $cart->add_on_qtys = is_string($cart->add_on_qtys) ? json_decode($cart->add_on_qtys, true) : $cart->add_on_qtys;
                $cart->variation = is_string($cart->variation) ? json_decode($cart->variation, true) : $cart->variation;
                $cart->item = Helpers::cart_product_data_formatting($cart->item, $cart->variation, $cart->add_on_ids, $cart->add_on_qtys, false, app()->getLocale());
                return $cart;
            }),
        ];
    }

    private function generateCode(): string
    {
        do {
            $code = strtoupper(Str::random(8));
        } while (GroupOrder::where('code', $code)->exists());

        return $code;
    }
}
