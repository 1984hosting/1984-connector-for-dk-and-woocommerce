<?php

namespace woo_bookkeeping\App\Core;


class Logs
{
    public static function writeLog(string $file_name, array $log)
    {
        $content = !empty($log) ? serialize($log) : '';
        return file_put_contents(PLUGIN_TEMP . $file_name . '.log', $content);
    }

    public static function readLog(string $file_name)
    {
        $log_path = PLUGIN_TEMP . $file_name . '.log';

        if (file_exists($log_path)) {
            return unserialize(file_get_contents($log_path));
        }

        return false;
    }
}