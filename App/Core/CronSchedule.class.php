<?php
/**
 * The file that defines the CronSchedule class
 *
 * A class definition that includes attributes and functions of the CronSchedule class
 *
 * @since      0.1
 *
 * @package    WooCoo
 * @subpackage WooCoo/App/Core
 */

namespace woocoo\App\Core;

/**
 * Class CronSchedule
 */
class CronSchedule
{

    public function __construct()
    {
        $this->registerActions();
    }

    /**
     * Custom cron intervals
     *
     * @param array $schedules
     * @return array
     */
    public function WooCooIntervals(array $schedules = [])
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

    /**
     * Register cron interval
     *
     * @return void
     */
    private function registerActions()
    {
        add_filter('cron_schedules', [$this, 'WooCooIntervals']);
    }
}