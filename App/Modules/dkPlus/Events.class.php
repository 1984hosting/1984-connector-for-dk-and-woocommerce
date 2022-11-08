<?php

namespace woo_bookkeeping\App\Modules\dkPlus;

use woo_bookkeeping\App\Core\CronSchedule;
use woo_bookkeeping\App\Core\Logs;

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
        /** Updating and preparing products for synchronization */
        add_action('woocoo_update_dkPlus', [self::class, 'sync']);

        /** Performing synchronization */
        add_action('woocoo_regular_events', [Product::class, 'productProlongSync']);
    }

    public static function sync()
    {
        $sync_products_status = Logs::readLog(Main::$module_slug . '/sync_products_status');

        if (!isset($sync_products_status['status']) || $sync_products_status['status'] === 'success') {
            Product::productSyncAll();
        }
    }
}