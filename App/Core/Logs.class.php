<?php

namespace woocoo\App\Core;


class Logs
{
    public static function writeLog(string $file_name, array $content)
    {
        $log_path = PLUGIN_TEMP . $file_name . '.log';
        $content = !empty($content) ? serialize($content) : '';

        self::directoryExists($log_path);

        return file_put_contents($log_path, $content);
    }

    /**
     * Logging of import and synchronization processes
     * @param string $file_name
     * @param string $content
     * @return bool
     */
    public static function appendLog(string $file_name, string $content): bool
    {
        $log_path = PLUGIN_TEMP . $file_name . '.log';
        $content = date('d.m.Y H:i:s') . ' - ' . __($content, PLUGIN_SLUG);

        self::directoryExists($log_path);

        if (file_exists($log_path)) {
            $content .= PHP_EOL . file_get_contents($log_path);
        }

        return file_put_contents($log_path, $content) !== false;
    }

    /**
     * Reading a special array with a log
     * @param string $file_name
     * @return array
     */
    public static function readLog(string $file_name): array
    {
        $log_path = PLUGIN_TEMP . $file_name . '.log';

        self::directoryExists($log_path);

        if (file_exists($log_path)) {
            $file = file_get_contents($log_path);
            return !empty($file) ? unserialize($file) : [];
        }

        return [];
    }

    /**
     * Read file to return as text
     * @param string $file_name
     * @return string
     */
    public static function readLogs(string $file_name): string
    {
        $log_path = PLUGIN_TEMP . $file_name . '.log';

        self::directoryExists($log_path);

        if (file_exists($log_path)) {
            return htmlspecialchars(file_get_contents($log_path));
        }

        return __('no have logs', PLUGIN_SLUG);
    }

    /**
     * Delete one log file
     * @param string $file_name
     * @return bool
     */
    public static function removeLog(string $file_name): bool
    {
        $log_path = PLUGIN_TEMP . $file_name . '.log';

        self::directoryExists($log_path);

        if (file_exists($log_path)) {
            unlink($log_path);
        }

        return true;
    }

    /**
     * Removing all temporary logs
     * @return bool
     */
    public static function removeLogs(): bool
    {
        if (file_exists(PLUGIN_TEMP)) {
            self::delTree(PLUGIN_TEMP);
        }

        mkdir(PLUGIN_TEMP);
        foreach (WOOCOO_MODULES as $dir_name) {
            mkdir(PLUGIN_TEMP . '/' . $dir_name . '/', 0755, true);
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

    /**
     * Recursive deletion catalogs and files
     * @param $dir
     * @return bool
     */
    private static function delTree($dir)
    {
        $files = array_diff(scandir($dir), ['.', '..']);

        foreach ($files as $file) {
            $file_path = $dir . $file;
            is_dir($file_path) ? self::delTree($file_path . '/') : unlink($file_path);
        }

        return rmdir($dir);
    }

    /**
     * Checking if a directory exists
     * @param $full_path
     */
    private static function directoryExists($full_path)
    {
        if (!is_dir(dirname($full_path))) {
            mkdir(dirname($full_path), 0755, true);
        }
    }
}
