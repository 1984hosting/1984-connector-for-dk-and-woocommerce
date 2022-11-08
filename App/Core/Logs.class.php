<?php

namespace woo_bookkeeping\App\Core;


class Logs
{
    public static function writeLog(string $file_name, array $log)
    {
        $content = !empty($log) ? serialize($log) : '';
        return file_put_contents(PLUGIN_TEMP . $file_name . '.log', $content);
    }

    public static function appendLog(string $file_name, string $content)
    {
        $log_name = PLUGIN_TEMP . $file_name . '.log';
        $content = date('d.m.Y H:i:s') . ' - ' . __($content, PLUGIN_SLUG) . PHP_EOL . file_get_contents($log_name);

        return file_put_contents($log_name, $content);
    }

    public static function readLog(string $file_name)
    {
        $log_path = PLUGIN_TEMP . $file_name . '.log';

        if (file_exists($log_path)) {
            return unserialize(file_get_contents($log_path));
        }

        return false;
    }

    public static function readLogs(string $file_name)
    {
        $log_path = PLUGIN_TEMP . $file_name . '.log';

        if (file_exists($log_path)) {
            return file_get_contents($log_path);
        }

        return 'no have logs';
    }

    public static function removeLog($file_path): bool
    {
        if (file_exists(PLUGIN_TEMP . $file_path . '.log')) {
            unlink(PLUGIN_TEMP . $file_path . '.log');
        }

        return true;
    }

    public static function removeLogs(): bool
    {
        if (file_exists(PLUGIN_TEMP)) {
            self::delTree(PLUGIN_TEMP);
        }

        mkdir(PLUGIN_TEMP);
        foreach (WOOCOO_MODULES as $dir_name) {
            mkdir(PLUGIN_TEMP . '/' . $dir_name . '/');
        }

        // Generate .htaccess file`
        $htaccess_file = path_join(PLUGIN_TEMP, '.htaccess');
        if (!file_exists($htaccess_file)) {
            if ($handle = fopen($htaccess_file, 'w')) {
                fwrite($handle, "Options -Indexes \n <Files *.php> \n deny from all \n </Files>");
                fclose($handle);
            }
        }

        return true;
    }


    private static function delTree($dir)
    {
        $files = array_diff(scandir($dir), ['.', '..']);
        foreach ($files as $file) {
            is_dir($dir . $file) ? self::delTree($dir . $file . '/') : unlink($dir . $file);
        }
        return rmdir($dir);
    }
}