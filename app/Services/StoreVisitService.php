<?php

namespace App\Services;

use App\Models\StoreVisitLog;

class StoreVisitService
{
    public static function record(int $storeId, string $source = 'web'): void
    {
        $source = in_array($source, ['app', 'web', 'custom_domain'], true) ? $source : 'web';

        StoreVisitLog::firstOrCreate(
            ['store_id' => $storeId, 'visit_date' => now()->toDateString(), 'source' => $source],
            ['visit_count' => 0]
        )->increment('visit_count');
    }
}
