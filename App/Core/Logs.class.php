<?php

namespace woo_bookkeeping\App\Core;


class Logs
{
    public static function writeLog(string $file_name, array $log)
    {
        $content = !empty($log) ? serialize($log) : '';

        $full_path = PLUGIN_TEMP . $file_name . '.log';

        // Make sure that the containing directory exists.
        if (!is_dir(dirname($full_path))) {
            mkdir(dirname($full_path), 0755, true);
        }
        return file_put_contents($full_path, $content);
    }

    public static function readLog(string $file_name)
    {
        $log_path = PLUGIN_TEMP . $file_name . '.log';

        if (file_exists($log_path)) {
            return unserialize(file_get_contents($log_path));
        }

        return false;
    }

    public static function removeLogs(): bool
    {
        if (file_exists(PLUGIN_TEMP)) {
            self::delTree(PLUGIN_TEMP);
        }

        mkdir(PLUGIN_TEMP);
        mkdir(PLUGIN_TEMP . '/dkPlus/');

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