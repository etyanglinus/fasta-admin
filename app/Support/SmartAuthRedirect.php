<?php

namespace App\Support;

use App\Models\BusinessSetting;
use Illuminate\Http\Request;

class SmartAuthRedirect
{
    private const PRIVATE_PREFIXES = [
        '/account',
        '/address',
        '/checkout',
        '/customer',
        '/loyalty',
        '/my-orders',
        '/notification',
        '/notifications',
        '/order',
        '/orders',
        '/payment',
        '/profile',
        '/refund',
        '/saved-files',
        '/wallet',
        '/wish-list',
        '/wishlist',
    ];

    public static function resolveLoginRedirect(?string $url, Request $request): ?string
    {
        return self::safeUrl($url, $request);
    }

    public static function resolveLogoutRedirect(?string $url, Request $request): string
    {
        $safeUrl = self::safeUrl($url, $request);

        if (! $safeUrl) {
            return self::homeUrl();
        }

        return self::isPrivatePath(self::pathFromUrl($safeUrl)) ? self::homeUrl() : $safeUrl;
    }

    public static function homeUrl(): string
    {
        $settings = json_decode(BusinessSetting::where('key', 'Feature section description')->first()?->value ?? '', true);
        $webAppUrl = data_get($settings, 'web_app_url');

        return $webAppUrl ? rtrim($webAppUrl, '/') : route('home');
    }

    private static function safeUrl(?string $url, Request $request): ?string
    {
        if (! $url) {
            return null;
        }

        $url = trim($url);

        if ($url === '' || str_starts_with($url, '//')) {
            return null;
        }

        if (str_starts_with($url, '/')) {
            return url($url);
        }

        $parts = parse_url($url);

        if (! $parts || ! in_array(strtolower($parts['scheme'] ?? ''), ['http', 'https'], true) || empty($parts['host'])) {
            return null;
        }

        $host = self::normalizeHost($parts['host']);

        if (! in_array($host, self::allowedHosts($request), true)) {
            return null;
        }

        return $url;
    }

    private static function allowedHosts(Request $request): array
    {
        $hosts = [self::normalizeHost($request->getHost())];

        foreach ([config('app.url'), self::homeUrl()] as $url) {
            $host = parse_url((string) $url, PHP_URL_HOST);
            if ($host) {
                $hosts[] = self::normalizeHost($host);
            }
        }

        return array_values(array_unique($hosts));
    }

    private static function isPrivatePath(string $path): bool
    {
        $path = '/' . ltrim(strtolower($path ?: '/'), '/');

        foreach (self::PRIVATE_PREFIXES as $prefix) {
            if ($path === $prefix || str_starts_with($path, $prefix . '/')) {
                return true;
            }
        }

        return false;
    }

    private static function pathFromUrl(string $url): string
    {
        return parse_url($url, PHP_URL_PATH) ?: '/';
    }

    private static function normalizeHost(string $host): string
    {
        return preg_replace('/^www\./i', '', strtolower($host));
    }
}
