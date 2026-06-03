<?php

namespace App\Http\Controllers;

use App\Models\BusinessSetting;
use App\Models\Store;
use App\Services\StoreVisitService;
use Illuminate\Http\Request;

class StorefrontRedirectController extends Controller
{
    public function home(Request $request)
    {
        return app(HomeController::class)->index();
    }

    public function shop(string $slug)
    {
        $store = Store::where('slug', $slug)->orWhere('id', $slug)->firstOrFail();

        return $this->redirectToStore($store, 'web');
    }

    private function redirectToStore(Store $store, string $source)
    {
        StoreVisitService::record($store->id, $source);

        return redirect()->away(rtrim($this->webAppUrl(), '/') . '/store/' . $store->slug);
    }

    private function webAppUrl(): string
    {
        $settings = json_decode(BusinessSetting::where('key', 'Feature section description')->first()?->value ?? '', true);
        $url = data_get($settings, 'web_app_url') ?: config('app.url');

        return $url ?: url('/');
    }
}
