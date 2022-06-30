<?php

namespace woo_bookkeeping\App\Modules\dkPlus;

use woo_bookkeeping\App\Core\CronSchedule;

class Events extends CronSchedule
{

    /**
     * Running cron tasks
     */
    const Variations = [
        'disabled' => [
            'interval' => 0,
            'display' => 'Non-repeating',
        ],
        'every_minute' => [
            'interval' => 60,
            'display' => 'Every minute',
        ],
        'every_five_minutes' => [
            'interval' => 300,
            'display' => 'Every 5 minutes',
        ],
        'hourly' => [
            'interval' => 3600,
            'display' => 'Once Hourly',
        ],
        'twice_daily' => [
            'interval' => 43200,
            'display' => 'Twice Daily',
        ],
        'daily' => [
            'interval' => 86400,
            'display' => 'Once Daily',
        ],
        'weekly' => [
            'interval' => 604800,
            'display' => 'Once Weekly',
        ],
        'fifteen_days' => [
            'interval' => 1296000,
            'display' => 'Every 15 Days',
        ],
        'monthly' => [
            'interval' => 2635200,
            'display' => 'Monthly',
        ],
    ];

    public static function register_cron_events()
    {
        add_action(
            'woocoo_update_products_' . Main::$module_slug,
            [self::class, 'run']
        );
    }

    public static function run() {
        $settings = Main::getInstance();

        Product::productSyncAll($settings[Main::$module_slug]['schedule']['params']);
    }
}