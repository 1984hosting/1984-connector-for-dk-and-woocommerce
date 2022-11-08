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

    /**
     * Get all variations
     * @return array all variations
     */
    public static function getVariations(): array
    {
        return static::Variations;
    }

    /**
     * Get variation by key
     * @param $key variation key
     * @return false|mixed variation
     */
    public static function getVariation($key)
    {
        if (isset(static::Variations[$key])) {
            return static::Variations[$key];
        }

        return false;
    }

    /**
     * Register cron interval
     */
    public static function registerActions()
    {
        add_filter('cron_schedules', [self::class, 'woocoo_intervals']);
    }

    /**
     * List all cron intervals
     * @param $schedules
     * @return mixed
     */
    public static function woocoo_intervals($schedules)
    {
        $schedules['every_minute'] = [
            'interval' => 60,
            'display' => __('Every minute', PLUGIN_SLUG),
        ];
        $schedules['every_five_minutes'] = [
            'interval' => 300,
            'display' => __('Every 5 minutes', PLUGIN_SLUG),
        ];
        $schedules['hourly'] = [
            'interval' => 3600,
            'display' => __('Hourly', PLUGIN_SLUG),
        ];
        $schedules['twice_daily'] = [
            'interval' => 43200,
            'display' => __('Twice a day', PLUGIN_SLUG),
        ];
        $schedules['daily'] = [
            'interval' => 86400,
            'display' => __('Daily', PLUGIN_SLUG),
        ];
        $schedules['weekly'] = [
            'interval' => 604800,
            'display' => __('Weekly', PLUGIN_SLUG),
        ];
        $schedules['fifteen_days'] = [
            'interval' => 1296000,
            'display' => __('Every 15 Days', PLUGIN_SLUG),
        ];
        $schedules['monthly'] = [
            'interval' => 2635200,
            'display' => __('Monthly', PLUGIN_SLUG),
        ];

        return $schedules;
    }
}