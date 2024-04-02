<?php

/**
 * Plugin Name: 1984 DK Connection for WooCommerce
 * Plugin URI: https://dkplugin.1984.hosting
 * Description: Sync your WooCommerce store with DK, including prices, inventory status and generate invoices for customers on checkout.
 * Version: 0.1.0
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

require plugin_dir_path( __FILE__ ) . './vendor/autoload.php';

$new_admin = new NineteenEightyFour\NineteenEightyWoo\Admin();

$new_woo_metaboxes          = new NineteenEightyFour\NineteenEightyWoo\Hooks\RegisterPostMeta();
$new_rest_settings          = new NineteenEightyFour\NineteenEightyWoo\Rest\Settings();
$new_woo_metaboxes          = new NineteenEightyFour\NineteenEightyWoo\Hooks\WooMetaboxes();
$new_kennitala_field        = new NineteenEightyFour\NineteenEightyWoo\Hooks\KennitalaField();
$new_woo_product_variations = new NineteenEightyFour\NineteenEightyWoo\Hooks\WooProductVariations();
$new_woo_update_product     = new NineteenEightyFour\NineteenEightyWoo\Hooks\WooUpdateProduct();
