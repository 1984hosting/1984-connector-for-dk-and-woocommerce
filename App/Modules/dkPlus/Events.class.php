<?php
/**
 * The file that defines the Events class
 *
 * A class definition that includes attributes and functions of the Events class
 *
 * @since      0.1
 *
 * @package    WooCoo
 * @subpackage WooCoo/App/Modules/dkPlus
 */

namespace woocoo\App\Modules\dkPlus;

use woocoo\App\Core\CronSchedule;

/**
 * Class Events
 */
class Events extends CronSchedule
{
    public function __construct()
    {
        $this->registerActions();
    }

    /**
     * Register required actions
     *
     * @return void
     */
    protected function registerActions()
    {
        /** Updating and preparing products for synchronization */
        add_action('woocoo_update_dkPlus', [Product::class, 'productSyncAllSchedule']);

        /** Performing synchronization */
        add_action('woocoo_regular_events', [Product::class, 'productProlongSync']);
    }
}
