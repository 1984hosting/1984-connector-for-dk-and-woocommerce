<?php

declare(strict_types = 1);

use NineteenEightyFour\NineteenEightyWoo\Config;

global $post;

$wc_product = new WC_Product( $post );

?>

<div class="options_group">
	<?php
	$stock_sync_meta = $wc_product->get_meta( '1984_woo_dk_stock_sync', true, 'edit' );
	wp_nonce_field( 'set_1984_woo_dk_stock_sync', 'set_1984_woo_dk_stock_sync_nonce' );
	woocommerce_wp_radio(
		array(
			'id'      => '1984_woo_dk_stock_sync',
			'name'    => '1984_woo_dk_stock_sync',
			'label'   => __( 'Sync Inventory with DK', '1984-dk-woo' ),
			'value'   => $stock_sync_meta,
			'options' => array(
				''      => sprintf(
					// Translators: %1$s is the current yes/no value.
					__( 'Use Default (Currently ‘%1$s’)', '1984-dk-woo' ),
					( Config::get_product_quantity_sync() ? __( 'Yes', '1984-dk-woo' ) : __( 'No', '1984-dk-woo' ) )
				),
				'true'  => __( 'Yes', '1984-dk-woo' ),
				'false' => __( 'No', '1984-dk-woo' ),
			),
		),
	);
	?>
	<p class="form-field">
		<?php
		echo sprintf(
			esc_html(
				// Translators: %1$s stands for a opening and %2$s for a closing <abbr> tag. %3$s stands for a opening and %4$s for a closing <strong> tag.
				__(
					'The %1$sSKU%2$s needs to be set to a unique value and must equal the intended %3$sItem Code%4$s in DK for any 1984 DK Sync functionality to work.%5$sAs it only works downstream, if this feature is enabled, setting product stock quantity and status in WooCommerce will not result in it being reflected in DK and it will be overwritten next time information is fetched from DK.',
					'1984-dk-woo'
				)
			),
			'<abbr title="' . esc_attr( __( 'stock keeping unit', '1984-dk-woo' ) ) . '">',
			'</abbr>',
			'<strong>',
			'</strong>',
			'<br />'
		);
		?>
	</p>
</div>
