<?php

namespace woo_bookkeeping\App\Core;

abstract class CronSchedule
{
    /**
     * Running cron tasks
     * format: associative array
     * 'name' => [
     * 'interval' => 'inseconds',
     * 'display' => 'display title',
     * ],
     */
    const Variations = [];

    static function getVariations(): array
    {
        return static::Variations;
    }

    static function getVariation($key)
    {
        if (isset(static::Variations[$key])) {
            return static::Variations[$key];
        }

        return false;
    }

}