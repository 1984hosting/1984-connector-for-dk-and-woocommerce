<?php

declare(strict_types = 1);

?>

<div class="options_group">
	<?php
	global $post;
	wp_nonce_field( 'set_1984_woo_dk_stock_sync', 'set_1984_woo_dk_stock_sync_nonce' );
	woocommerce_wp_checkbox(
		array(
			'id'          => '1984_woo_dk_stock_sync',
			'value'       => (bool) get_post_meta( $post->ID, '1984_woo_dk_stock_sync', true ) ? 'true' : '',
			'label'       => 'DK handles inventory',
			'description' => 'Lets the 1984 DK Connection plugin to sync inventory status and stock quanity between WooCommerce and DK',
			'cbvalue'     => 'true',
		),
	);
	?>
	<p class="form-field">
		<?php
		echo sprintf(
			esc_html(
				// Translators: %1$s stands for a opening and %2$s for a closing <abbr> tag. %3$s stands for a opening and %4$s for a closing <strong> tag.
				__(
					'The %1$sSKU%2$s needs to be set to a unique value and must equal the intended %3$sItem Code%4$s in DK for any 1984 DK Sync functionality to work.%5$sEnabling this feature means that the setting below (quantity, backorders etc.) are %3$soverridden%4$s every time your WooCommerce shop syncs with DK.%5$sAs DK does not support initiating a product with a stock count other than zero, a stock count needs to be performed in DK for the quantity to work in WooCommerce.',
					'NineteenEightyWoo'
				)
			),
			'<abbr title="' . esc_attr( __( 'stock keeping unit', 'NineteenEightyWoo' ) ) . '">',
			'</abbr>',
			'<strong>',
			'</strong>',
			'<br />'
		);
		?>
	</p>
</div>
