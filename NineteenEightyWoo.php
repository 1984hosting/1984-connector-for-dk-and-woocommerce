<?php

/**
 * Plugin Name: 1984 DK Connection for WooCommerce
 * Plugin URI: https://github.com/1984hosting/1984-dk-woo
 * Description: Sync your WooCommerce store with DK, including prices, inventory status and generate invoices for customers on checkout.
 * Version: 0.1.2
 * Requires at least: 6.1.5
 * Requires PHP: 8.1
 * Author: 1984 Hosting
 * Author URI: https://1984.hosting
 * License: GPL-3.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: NineteenEightyWoo
 * Requires Plugins: woocommerce
 */

declare(strict_types = 1);

namespace NineteenEightyFour\NineteenEightyWoo;

require plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';

new Admin();
new Cron\Schedule();
new Rest\Settings();
new Hooks\KennitalaField();
new Hooks\RegisterPostMeta();
new Hooks\UpdateUser();
new Hooks\WooMetaboxes();
new Hooks\WooOrderStatusChanges();
new Hooks\WooProductVariations();
new Hooks\WooUpdateProduct();

register_activation_hook(
	__FILE__,
	__NAMESPACE__ . '\Cron\Schedule::activate'
);

register_deactivation_hook(
	__FILE__,
	__NAMESPACE__ . '\Cron\Schedule::deactivate'
);
