<?php

namespace App\Http\Controllers\Vendor;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Models\StoreVisitLog;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StoreAnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $storeId = Helpers::get_store_id();
        $from = $request->from ? Carbon::parse($request->from)->startOfDay() : now()->subDays(13)->startOfDay();
        $to = $request->to ? Carbon::parse($request->to)->endOfDay() : now()->endOfDay();

        $logs = StoreVisitLog::where('store_id', $storeId)
            ->whereBetween('visit_date', [$from->toDateString(), $to->toDateString()])
            ->orderBy('visit_date')
            ->get();

        $totalVisits = $logs->sum('visit_count');
        $todayVisits = $logs->where('visit_date', now()->toDateString())->sum('visit_count');
        $sourceTotals = $logs->groupBy('source')->map->sum('visit_count');
        $daily = $logs->groupBy(fn ($log) => $log->visit_date->format('Y-m-d'));

        return view('vendor-views.analytics.store-visits', compact('logs', 'daily', 'totalVisits', 'todayVisits', 'sourceTotals', 'from', 'to'));
    }
}
