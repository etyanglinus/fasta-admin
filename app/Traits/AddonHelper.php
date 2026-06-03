<?php

namespace App\Traits;

trait AddonHelper
{
    public function get_addons(): array
    {
        $dir = 'Modules';
        $directories = self::getDirectories($dir);
        $addons = [];
        foreach ($directories as $directory) {
            if ($directory == 'Gateways') {
                $subDirs = self::getDirectories('Modules/' . $directory);
                if (in_array('Addon', $subDirs)) {
                    $addons[] = 'Modules/' . $directory;
                }
            }
        }

        $array = [];
        foreach ($addons as $item) {
            $fullData = include($item . '/Addon/info.php');
            $array[] = [
                'addon_name' => $fullData['name'],
                'software_id' => $fullData['software_id'],
                'is_published' => 1,
            ];
        }

        return $array;
    }

    public function get_addon_admin_routes(): array
    {
        $dir = 'Modules';
        $directories = self::getDirectories($dir);
        $addons = [];
        foreach ($directories as $directory) {
            if ($directory == 'Gateways') {
                $subDirs = self::getDirectories('Modules/' . $directory);
                if (in_array('Addon', $subDirs)) {
                    $addons[] = 'Modules/' . $directory;
                }
            }
        }

        $fullData = [];
        foreach ($addons as $item) {
            $fullData[] = include($item . '/Addon/admin_routes.php');
        }

        return $fullData;
    }

    public function get_payment_publish_status(): array
    {
        $dir = 'Modules';
        $directories = self::getDirectories($dir);
        $addons = [];
        foreach ($directories as $directory) {
            $subDirs = self::getDirectories($dir . '/' . $directory);
            if ($directory == 'Gateways' && in_array('Addon', $subDirs)) {
                $addons[] = $dir . '/' . $directory;
            }
        }

        $array = [];
        foreach ($addons as $item) {
            $array[] = [
                'is_published' => 1,
            ];
        }

        return $array;
    }

    function getDirectories(string $path): array
    {
        $directories = [];
        $path = base_path($path);
        if (!is_dir($path)) {
            return [];
        }

        $items = scandir($path);
        foreach ($items as $item) {
            if ($item == '..' || $item == '.') {
                continue;
            }
            if (is_dir($path . '/' . $item)) {
                $directories[] = $item;
            }
        }
        return $directories;
    }
}