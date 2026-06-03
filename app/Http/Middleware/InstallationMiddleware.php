<?php

namespace App\Http\Middleware;

use Closure;

class InstallationMiddleware
{
    public function handle($request, Closure $next)
    {
        if (!session()->has('purchase_key')) {
            session()->put('purchase_key', env('PURCHASE_CODE') ?: 'activated');
        }

        return $next($request);
    }
}