<?php

namespace App\Console\Commands;

use App\Http\Controllers\Admin\SystemMaintenanceController;
use Illuminate\Console\Command;

class DailySystemBackup extends Command
{
    protected $signature = 'system:daily-backup';
    protected $description = 'Create a database backup for Fasta Deliveries';

    public function handle(): int
    {
        $result = app(SystemMaintenanceController::class)->createDatabaseBackup();
        $this->info(data_get($result, 'message'));

        return data_get($result, 'status') === 'completed' ? self::SUCCESS : self::FAILURE;
    }
}
