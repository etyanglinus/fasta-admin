<?php

namespace App\Http\Middleware;

use App\CentralLogics\Helpers;
use Brian2694\Toastr\Facades\Toastr;
use Closure;

class ModulePermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next, $module)
    {
        if (auth('admin')->check()) {
            $hasAccess = ($request->isMethodSafe() && ! $this->isStateChangingSafeMethod($request))
                ? Helpers::module_permission_check($module)
                : Helpers::module_write_permission_check($module);

            if ($hasAccess) {
                return $next($request);
            }
        }
        else if (auth('vendor_employee')->check() || auth('vendor')->check()) {
            if(Helpers::employee_module_permission_check($module))
            {
                return $next($request);
            }
        }

        Toastr::error(translate('messages.access_denied'));
        return back();
    }

    private function isStateChangingSafeMethod($request): bool
    {
        if (! $request->isMethodSafe()) {
            return false;
        }

        $routeName = (string) $request->route()?->getName();
        foreach (['status', 'toggle', 'delete', 'destroy', 'approve', 'deny', 'cancel', 'payment'] as $keyword) {
            if (str_contains($routeName, $keyword)) {
                return true;
            }
        }

        $path = $request->path();
        foreach (['/status/', '/toggle-', '/delete/', '/approve/', '/deny/', '/cancel/', '/default-status/', '/digital-payment/', '/cash-on-delivery/', '/offline-payment/'] as $needle) {
            if (str_contains($path, $needle)) {
                return true;
            }
        }

        return false;
    }
}
