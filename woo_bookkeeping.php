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
    new woo_bookkeeping\App\Core\Main();

    return true;
}

//add_action('plugins_loaded', 'woo_bookkeeping_load');
add_action('init', 'woo_bookkeeping_load');

