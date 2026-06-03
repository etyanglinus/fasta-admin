<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

trait ActivationClass
{
    public function is_local(): bool
    {
        return true;
    }

    public function getDomain(): string
    {
        return str_replace(["http://", "https://", "www."], "", url('/'));
    }

    public function getSystemAddonCacheKey(string|null $app = 'default'): string
    {
        $appName = env('APP_NAME').'_cache';
        return str_replace('-', '_', Str::slug($appName.'cache_system_addons_for_' . $app . '_' . $this->getDomain()));
    }

    public function getAddonsConfig(): array
    {
        if (file_exists(base_path('config/system-addons.php'))) {
            $config = include(base_path('config/system-addons.php'));
        } else {
            $apps = ['admin_panel', 'vendor_panel', 'user_app', 'vendor_app', 'deliveryman_app', 'react_web'];
            $config = [];
            foreach ($apps as $app) {
                $config[$app] = [
                    'username' => '',
                    'purchase_key' => '',
                    'software_id' => '',
                    'domain' => $this->getDomain(),
                    'software_type' => $app === 'admin_panel' ? 'product' : 'addon',
                ];
            }
        }

        foreach ($config as $app => $appConfig) {
            $config[$app] = array_merge($appConfig, [
                'active' => 1,
                'domain' => $appConfig['domain'] ?? $this->getDomain(),
            ]);
        }

        return $config;
    }

    public function getCacheTimeoutByDays(int $days = 3): int
    {
        return 60 * 60 * 24 * $days;
    }

    public function getRequestConfig(string|null $username = null, string|null $purchaseKey = null, string|null $softwareId = null, string|null $softwareType = null): array
    {
        return [
            'active' => 1,
            'username' => trim((string) $username),
            'purchase_key' => $purchaseKey,
            'software_id' => $softwareId ?? (defined('SOFTWARE_ID') ? SOFTWARE_ID : ''),
            'domain' => $this->getDomain(),
            'software_type' => $softwareType,
        ];
    }

    public function checkActivationCache(string|null $app)
    {
        if (!is_null($app)) {
            Cache::put($this->getSystemAddonCacheKey(app: $app), true, $this->getCacheTimeoutByDays(days: 30));
        }

        return true;
    }

    public function updateActivationConfig($app, $response): void
    {
        $config = $this->getAddonsConfig();
        $response['active'] = 1;
        $response['domain'] = $response['domain'] ?? $this->getDomain();
        $config[$app] = $response;
        $configContents = "<?php return " . var_export($config, true) . ";";
        file_put_contents(base_path('config/system-addons.php'), $configContents);
        Cache::forget($this->getSystemAddonCacheKey(app: $app));
    }
}