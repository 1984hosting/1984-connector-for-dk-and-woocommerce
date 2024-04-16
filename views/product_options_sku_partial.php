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
					'The %1$sSKU%2$s needs to be set to a unique value and must equal the intended %3$sItem Code%4$s in DK for any 1984 DK Sync functionality to work.%5$sFurthermore, DK does not support setting an initial stock quantity for products when they are created in their system, so new products will have backorders in WooCommerce and negative stock count enabled in DK until a stock count is performed in DK.%5$sEnabling this feature means that some of the setting below (stock management, quantity and backorders) are %3$soverridden%4$s every time your WooCommerce shop syncs with DK.',
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
