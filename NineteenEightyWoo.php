<?php

/**
 * Run the PSR-4 autoloader
 *
 * This will replace any other loading mechanism in the codebase once it has
 * been refactored.
 */
require plugin_dir_path( __FILE__ ) . './vendor/autoload.php';

$new_admin = new NineteenEightyFour\NineteenEightyWoo\Admin();

$new_woo_metaboxes = new NineteenEightyFour\NineteenEightyWoo\RegisterPostMeta();
$new_rest_settings = new NineteenEightyFour\NineteenEightyWoo\Rest\Settings();
