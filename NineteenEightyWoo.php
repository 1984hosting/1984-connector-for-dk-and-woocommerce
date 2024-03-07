<?php

declare(strict_types = 1);

require plugin_dir_path( __FILE__ ) . './vendor/autoload.php';

$new_admin = new NineteenEightyFour\NineteenEightyWoo\Admin();

$new_woo_metaboxes = new NineteenEightyFour\NineteenEightyWoo\RegisterPostMeta();
$new_rest_settings = new NineteenEightyFour\NineteenEightyWoo\Rest\Settings();
$new_woo_metaboxes = new NineteenEightyFour\NineteenEightyWoo\WooMetaboxes();
