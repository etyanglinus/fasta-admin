<?php

namespace App\Http\Controllers\Api\V1;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\GroupOrder;
use App\Models\Item;
use App\Models\ItemCampaign;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    public function get_carts(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'guest_id' => $request->user ? 'nullable' : 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        [$userId, $isGuest, $groupContext] = $this->cartContext($request);

        return response()->json($this->formattedCarts($request, $userId, $isGuest, $groupContext), 200);
    }

    public function add_to_cart(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'guest_id' => $request->user ? 'nullable' : 'required',
            'item_id' => 'required|integer',
            'model' => 'required|string|in:Item,ItemCampaign',
            'price' => 'required|numeric',
            'quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        [$userId, $isGuest, $groupContext] = $this->cartContext($request);
        $model = $request->model === 'Item' ? 'App\Models\Item' : 'App\Models\ItemCampaign';
        $item = $request->model === 'Item' ? Item::find($request->item_id) : ItemCampaign::find($request->item_id);

        if (! $item) {
            return response()->json(['errors' => [['code' => 'item', 'message' => translate('messages.not_found')]]], 404);
        }

        if ($groupContext['group_order'] && ! $groupContext['group_order']->store_id && $item?->store_id) {
            $groupContext['group_order']->store_id = $item->store_id;
            $groupContext['group_order']->save();
        }

        if ($groupContext['group_order'] && $groupContext['group_order']->store_id && $item?->store_id && $groupContext['group_order']->store_id !== $item->store_id) {
            return response()->json(['errors' => [['code' => 'group_order_store', 'message' => translate('messages.You_can_not_add_items_from_different_stores')]]], 403);
        }

        $cart = $this->cartQuery($request, $userId, $isGuest, $groupContext)
            ->where('item_id', $request->item_id)
            ->where('item_type', $model)
            ->first();

        if ($cart && json_decode($cart->variation, true) == $request->variation) {
            return response()->json(['errors' => [['code' => 'cart_item', 'message' => translate('messages.Item_already_exists')]]], 403);
        }

        if ($item->maximum_cart_quantity && ($request->quantity > $item->maximum_cart_quantity)) {
            return response()->json(['errors' => [['code' => 'cart_item_limit', 'message' => translate('messages.maximum_cart_quantity_exceeded')]]], 403);
        }

        $cart = new Cart();
        $cart->user_id = $userId;
        $cart->module_id = getModuleId($request->header('moduleId'));
        $cart->group_order_id = $groupContext['group_order']?->id;
        $cart->group_order_participant_id = $groupContext['participant']?->id;
        $cart->participant_name = $groupContext['participant']?->name;
        $cart->item_id = $request->item_id;
        $cart->is_guest = $isGuest;
        $cart->add_on_ids = isset($request->add_on_ids) ? json_encode($request->add_on_ids) : json_encode([]);
        $cart->add_on_qtys = isset($request->add_on_qtys) ? json_encode($request->add_on_qtys) : json_encode([]);
        $cart->item_type = $model;
        $cart->price = $request->price;
        $cart->quantity = $request->quantity;
        $cart->variation = isset($request->variation) ? json_encode($request->variation) : json_encode([]);
        $cart->save();

        $item->carts()->save($cart);

        return response()->json($this->formattedCarts($request, $userId, $isGuest, $groupContext), 200);
    }

    public function update_cart(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cart_id' => 'required',
            'guest_id' => $request->user ? 'nullable' : 'required',
            'price' => 'required|numeric',
            'quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        [$userId, $isGuest, $groupContext] = $this->cartContext($request);
        $cart = $this->editableCartQuery($request, $userId, $isGuest, $groupContext)->where('id', $request->cart_id)->first();

        if (! $cart) {
            return response()->json(['errors' => [['code' => 'cart', 'message' => translate('messages.not_found')]]], 404);
        }

        $item = $cart->item_type === 'App\Models\Item' ? Item::find($cart->item_id) : ItemCampaign::find($cart->item_id);
        if ($item?->maximum_cart_quantity && ($request->quantity > $item->maximum_cart_quantity)) {
            return response()->json(['errors' => [['code' => 'cart_item_limit', 'message' => translate('messages.maximum_cart_quantity_exceeded')]]], 403);
        }

        $cart->add_on_ids = isset($request->add_on_ids) ? json_encode($request->add_on_ids) : $cart->add_on_ids;
        $cart->add_on_qtys = isset($request->add_on_qtys) ? json_encode($request->add_on_qtys) : $cart->add_on_qtys;
        $cart->price = $request->price;
        $cart->quantity = $request->quantity;
        $cart->variation = isset($request->variation) ? json_encode($request->variation) : $cart->variation;
        $cart->save();

        return response()->json($this->formattedCarts($request, $userId, $isGuest, $groupContext), 200);
    }

    public function remove_cart_item(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cart_id' => 'required',
            'guest_id' => $request->user ? 'nullable' : 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        [$userId, $isGuest, $groupContext] = $this->cartContext($request);
        $cart = $this->editableCartQuery($request, $userId, $isGuest, $groupContext)->where('id', $request->cart_id)->first();
        $cart?->delete();

        return response()->json($this->formattedCarts($request, $userId, $isGuest, $groupContext), 200);
    }

    public function remove_cart(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'guest_id' => $request->user ? 'nullable' : 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        [$userId, $isGuest, $groupContext] = $this->cartContext($request);
        $this->editableCartQuery($request, $userId, $isGuest, $groupContext)->get()->each(fn ($cart) => $cart->delete());

        return response()->json($this->formattedCarts($request, $userId, $isGuest, $groupContext), 200);
    }

    private function formattedCarts(Request $request, int|string $userId, int $isGuest, array $groupContext)
    {
        return $this->cartQuery($request, $userId, $isGuest, $groupContext)->get()
            ->map(function ($data) {
                $data->add_on_ids = is_string($data->add_on_ids) ? json_decode($data->add_on_ids, true) : $data->add_on_ids;
                $data->add_on_qtys = is_string($data->add_on_qtys) ? json_decode($data->add_on_qtys, true) : $data->add_on_qtys;
                $data->variation = is_string($data->variation) ? json_decode($data->variation, true) : $data->variation;
                $data->item = Helpers::cart_product_data_formatting($data->item, $data->variation, $data->add_on_ids, $data->add_on_qtys, false, app()->getLocale());
                return $data;
            });
    }

    private function cartContext(Request $request): array
    {
        $userId = $request->user ? $request->user->id : $request['guest_id'];
        $isGuest = $request->user ? 0 : 1;

        return [$userId, $isGuest, $this->groupContext($request)];
    }

    private function cartQuery(Request $request, int|string $userId, int $isGuest, array $groupContext)
    {
        if ($groupContext['group_order']) {
            return Cart::where('group_order_id', $groupContext['group_order']->id)
                ->where('module_id', getModuleId($request->header('moduleId')));
        }

        return Cart::where('user_id', $userId)
            ->where('is_guest', $isGuest)
            ->whereNull('group_order_id')
            ->where('module_id', getModuleId($request->header('moduleId')));
    }

    private function editableCartQuery(Request $request, int|string $userId, int $isGuest, array $groupContext)
    {
        $query = $this->cartQuery($request, $userId, $isGuest, $groupContext);

        if ($groupContext['participant'] && ! $groupContext['participant']->is_host) {
            $query->where('group_order_participant_id', $groupContext['participant']->id);
        }

        return $query;
    }

    private function groupContext(Request $request): array
    {
        $code = $request->group_order_code ?: $request->group_code;
        if (! $code) {
            return ['group_order' => null, 'participant' => null];
        }

        $groupOrder = GroupOrder::where('code', $code)->with('participants')->first();
        if (! $groupOrder || $groupOrder->status !== 'open' || $groupOrder->isExpired()) {
            abort(response()->json(['errors' => [['code' => 'group_order', 'message' => translate('messages.not_found')]]], 404));
        }

        $participant = $groupOrder->participants()
            ->where('user_id', $request->user?->id)
            ->when(! $request->user, fn ($query) => $query->where('guest_id', $request->guest_id))
            ->first();

        if (! $participant) {
            abort(response()->json(['errors' => [['code' => 'group_order_participant', 'message' => translate('messages.not_found')]]], 404));
        }

        return ['group_order' => $groupOrder, 'participant' => $participant];
    }
}
