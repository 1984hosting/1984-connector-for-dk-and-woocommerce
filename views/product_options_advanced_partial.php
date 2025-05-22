<?php

declare(strict_types = 1);

use NineteenEightyFour\NineteenEightyWoo\Helpers\Product as ProductHelper;
use NineteenEightyFour\NineteenEightyWoo\Config;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$wc_product       = wc_get_product();
$product_currency = ProductHelper::get_currency( $wc_product );

?>

<div class="options_group">
	<?php
	$name_sync_meta = $wc_product->get_meta( '1984_woo_dk_name_sync', true, 'edit' );
	wp_nonce_field( 'set_1984_woo_dk_name_sync', 'set_1984_woo_dk_name_sync_nonce' );
	woocommerce_wp_radio(
		array(
			'id'      => '1984_woo_dk_name_sync',
			'name'    => '1984_woo_dk_name_sync',
			'label'   => __( 'Sync Name with DK', '1984-dk-woo' ),
			'value'   => $name_sync_meta,
			'options' => array(
				''      => sprintf(
					// Translators: %1$s is the current yes/no value.
					__( 'Use Default (Currently ‘%1$s’)', '1984-dk-woo' ),
					( Config::get_product_name_sync() ? __( 'Yes', '1984-dk-woo' ) : __( 'No', '1984-dk-woo' ) )
				),
				'true'  => __( 'Yes', '1984-dk-woo' ),
				'false' => __( 'No', '1984-dk-woo' ),
			),
		),
	);
	?>
</div>
