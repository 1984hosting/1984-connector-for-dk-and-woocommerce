<?php

namespace woocoo\App\Modules\dkPlus;

use woocoo\App\Core\CronSchedule;

class Events extends CronSchedule
{
    public function __construct()
    {
        $this->registerActions();
    }

    /**
     * Register required actions
     */
    protected function registerActions()
    {
        /** Updating and preparing products for synchronization */
        add_action('woocoo_update_dkPlus', [Product::class, 'productSyncAllSchedule']);

        /** Performing synchronization */
        add_action('woocoo_regular_events', [Product::class, 'productProlongSync']);
    }
}
