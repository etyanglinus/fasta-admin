<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoginActivityLog;
use App\Models\SystemBackup;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;

class SystemMaintenanceController extends Controller
{
    public function index()
    {
        $logPath = storage_path('logs/laravel.log');
        $log = File::exists($logPath) ? collect(array_slice(file($logPath), -250))->implode('') : '';
        $backups = SystemBackup::latest()->paginate(config('default_pagination'));
        $loginLogs = LoginActivityLog::latest('logged_in_at')->limit(50)->get();

        return view('admin-views.system-maintenance.index', compact('log', 'backups', 'loginLogs'));
    }

    public function backup()
    {
        $result = $this->createDatabaseBackup();
        Toastr::{data_get($result, 'status') === 'completed' ? 'success' : 'error'}(data_get($result, 'message'));

        return back();
    }

    public function download(SystemBackup $backup)
    {
        $path = storage_path('app/backups/' . $backup->file_name);
        abort_unless(File::exists($path), 404);

        return response()->download($path);
    }

    public function clearLog()
    {
        File::put(storage_path('logs/laravel.log'), '');
        Toastr::success(translate('Log cleared successfully'));

        return back();
    }

    public function createDatabaseBackup(): array
    {
        $dir = storage_path('app/backups');
        File::ensureDirectoryExists($dir);

        $fileName = 'database-backup-' . now()->format('Y-m-d-His') . '.sql';
        $path = $dir . DIRECTORY_SEPARATOR . $fileName;
        $mysqlDump = File::exists('C:\xampp\mysql\bin\mysqldump.exe') ? 'C:\xampp\mysql\bin\mysqldump.exe' : 'mysqldump';
        $password = config('database.connections.mysql.password');
        $command = [
            $mysqlDump,
            '-h' . config('database.connections.mysql.host'),
            '-P' . config('database.connections.mysql.port'),
            '-u' . config('database.connections.mysql.username'),
        ];

        if ($password !== null && $password !== '') {
            $command[] = '-p' . $password;
        }

        $command[] = config('database.connections.mysql.database');

        try {
            $process = new Process($command, base_path());
            $process->setTimeout(300);
            $process->run(function ($type, $output) use ($path) {
                File::append($path, $output);
            });

            $status = $process->isSuccessful() && File::exists($path) ? 'completed' : 'failed';
            $message = $status === 'completed' ? translate('Backup created successfully') : ($process->errorOutput() ?: translate('Backup failed'));
        } catch (\Throwable $exception) {
            $status = 'failed';
            $message = $exception->getMessage();
        }

        SystemBackup::create([
            'file_name' => $fileName,
            'size' => File::exists($path) ? File::size($path) : 0,
            'status' => $status,
            'message' => $message,
        ]);

        return compact('status', 'message');
    }
}
