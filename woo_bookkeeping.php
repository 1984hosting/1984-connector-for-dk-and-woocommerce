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

if (!defined('PLUGIN_TPL_DIR')) {
    define('PLUGIN_TPL_DIR', PLUGIN_PATH . 'templates');
}

/**
 * Load plugin
 */
require_once 'loader.class.php';

/** Autoload */
new woo_bookkeeping\loader(dirname(__FILE__), 'woo_bookkeeping\\');

function woo_bookkeeping_activate()
{
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
add_action('init', 'woo_bookkeeping_activate');


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
function calc_percent($total, $number): float
{
    return $number === 0 ? 100 : abs(round((($number - $total) * 100) / $total, 2));
}
