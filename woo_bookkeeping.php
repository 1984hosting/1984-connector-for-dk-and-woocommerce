<?php
/**
 * Plugin Name: WooBookkeeping
 * Plugin URI: https://1984.hosting/
 * Description: bookkeeping sync
 * Author: It-Hive
 * Version: 0.1
 */

declare(strict_types=1);

defined('ABSPATH') || exit;

/**
 * Plugin global settings
 */
define('PLUGIN_SLUG', basename(__DIR__));
define('PLUGIN_NAME', __('Woo Bookkeeping', PLUGIN_SLUG));
define('PLUGIN_URL', plugin_dir_url(__FILE__));
define('PLUGIN_PATH', plugin_dir_path(__FILE__));
define('PLUGIN_TEMP', plugin_dir_path(__FILE__) . 'tmp' . DIRECTORY_SEPARATOR);
define('WOOCOO_MODULES', get_modules());

if (!defined('PLUGIN_TPL_DIR')) {
    define('PLUGIN_TPL_DIR', PLUGIN_PATH . 'templates');
}

/**
 * Load plugin
 */
require_once 'loader.class.php';

/** Autoload */
new woo_bookkeeping\loader(dirname(__FILE__), 'woo_bookkeeping\\');

function woo_bookkeeping_load()
{
    /**
     * Check is activated woocommerce plugin
     */
    if (!class_exists('woocommerce')) {
        return new woo_bookkeeping\App\Core\WP_Notice('error', 'Woo Bookkeeping is enabled but has no effect. Requires WooCommerce to work.');
    }

    include_once WC_ABSPATH . 'packages/action-scheduler/action-scheduler.php';
    if ( false === as_next_scheduled_action( 'woocoo_worker' ) ) {
        //create event - every_minute
        as_schedule_recurring_action( time(), 60, 'woocoo_worker', array(), PLUGIN_SLUG );
    }

    /** Load plugin core */
    woo_bookkeeping\App\Core\Main::LoadCore();

    return true;
}

//add_action('plugins_loaded', 'woo_bookkeeping_load');
add_action('init', 'woo_bookkeeping_load');

/**
 * Plugin activation
 */
register_activation_hook(__FILE__, 'woocoo_activation');

function woocoo_activation()
{

}

/**
 * Plugin deactivation
 */
register_deactivation_hook(__FILE__, 'woocoo_deactivation');

function woocoo_deactivation()
{
    as_unschedule_all_actions('', [], PLUGIN_SLUG);

    $settings = woo_bookkeeping\App\Core\Main::getInstance();
    foreach ($settings as &$setting) {
        unset($setting['schedule']);
    }
    update_option(PLUGIN_SLUG, $settings, 'no');

    woo_bookkeeping\App\Core\Logs::removeLogs();
}

/**
 * Plugin uninstall
 */
register_uninstall_hook(__FILE__, 'woocoo_uninstall');

function woocoo_uninstall()
{
    delete_option(PLUGIN_SLUG);
}

add_action('woocoo_worker', 'woocoo_regular');

function woocoo_regular()
{
    new woo_bookkeeping\App\Core\CronSchedule();
    //woo_bookkeeping\App\Core\Main::LoadCore();
    do_action('woocoo_regular_events');
}

function calc_percent($total, $number): float
{
    return $number === 0 ? 100 : abs(round((($number - $total) * 100) / $total, 2));
}

function get_modules()
{
    return array_diff(scandir(PLUGIN_PATH . 'App/Modules'), ['.', '..']);
}