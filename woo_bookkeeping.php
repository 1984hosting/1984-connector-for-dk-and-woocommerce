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
define('PLUGIN_SLUG', 'woo_bookkeeping');
define('PLUGIN_NAME', __('Woo Bookkeeping', PLUGIN_SLUG));
define('PLUGIN_URL', plugin_dir_url(__FILE__));

if (!defined('PLUGIN_TPL_DIR')) {
    define('PLUGIN_TPL_DIR', WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . PLUGIN_SLUG . '/templates');
}

/**
 * Load plugin
 */
require_once 'loader.class.php';

function woo_bookkeeping_load()
{
    /** Autoload */
    new woo_bookkeeping\loader(dirname(__FILE__), 'woo_bookkeeping\\');

    /**
     * Check is activated woocommerce plugin
     */
    if (!class_exists('woocommerce')) {
        return new woo_bookkeeping\App\Core\WP_Notice('error', 'Woo Bookkeeping is enabled but has no effect. Requires WooCommerce to work.');
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
//register_activation_hook(__FILE__, 'woocoo_activation');

/**
 * Plugin deactivation
 */
register_deactivation_hook(__FILE__, 'woocoo_deactivation');

/*function woocoo_activation()
{
    //delete just in case
    wp_clear_scheduled_hook('woocoo_event');

    //create cron event
    wp_schedule_event(time(), 'hourly', 'woocoo_event');
}*/


/*add_action('woocoo_event', 'do_this_hourly');
function do_this_hourly()
{
}*/

function woocoo_deactivation()
{
    wp_clear_scheduled_hook('woocoo_event');
}


add_filter('cron_schedules', 'woocoo_intervals');

function woocoo_intervals ($schedules) {
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
        'display' => __('Once Hourly', PLUGIN_SLUG),
    ];
    $schedules['twice_daily'] = [
        'interval' => 43200,
        'display' => __('Twice Daily', PLUGIN_SLUG),
    ];
    $schedules['daily'] = [
        'interval' => 86400,
        'display' => __('Once Daily', PLUGIN_SLUG),
    ];
    $schedules['weekly'] = [
        'interval' => 604800,
        'display' => __('Once Weekly', PLUGIN_SLUG),
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