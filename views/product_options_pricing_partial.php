<?php

declare(strict_types = 1);

?>

<div class="options_group">
	<?php
	global $post;
	wp_nonce_field( 'set_1984_woo_dk_price_sync', 'set_1984_woo_dk_price_sync_nonce' );
	woocommerce_wp_checkbox(
		array(
			'id'          => '1984_woo_dk_price_sync',
			'value'       => get_post_meta( $post->ID, '1984_woo_dk_price_sync', true ) ? 'true' : 'false',
			'label'       => __( 'Sync prices with DK', '1984-dk-woo' ),
			'description' => __( 'Enables the 1984 DK Connection plugin to sync the products\'s price between WooCommerce and DK.', '1984-dk-woo' ),
			'cbvalue'     => 'true',
		),
	);
	?>
	<p class="form-field">
		<?php
		echo sprintf(
			esc_html(
				// Translators: %1$s stands for a opening and %2$s for a closing <abbr> tag. %3$s and %4$s stand for the opening and closing <strong> tags.
				__(
					'In order for price sync to work, the %1$sSKU%2$s needs to be set and must equal the Item Code in DK. It is set in the %3$sInventory Panel%4$s.',
					'1984-dk-woo'
				)
			),
			'<abbr title="' . esc_attr( __( 'stock keeping unit', '1984-dk-woo' ) ) . '">',
			'</abbr>',
			'<strong>',
			'</strong>'
		);
		?>
	</p>
</div>
