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
			'value'       => get_post_meta( $post->ID, '1984_woo_dk_stock_sync', true ) ? 'true' : 'false',
			'label'       => 'Sync Inventory with DK',
			'description' => 'Enables the 1984 DK Connection plugin to sync inventory status and stock quanity between WooCommerce and dkPlus.',
			'desc_tip'    => true,
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
					'The %1$sSKU%2$s needs to be set and must equal the %3$sItem Code%4$s in DK for any 1984 DK Sync functionality to work.',
					'NineteenEightyWoo'
				)
			),
			'<abbr title="' . esc_attr( __( 'stock keeping unit', 'NineteenEightyWoo' ) ) . '">',
			'</abbr>',
			'<strong>',
			'</strong>	'
		);
		?>
		<br />
		<?php
		echo sprintf(
			esc_html(
				// Translators: %1$s stands for a opening and %2$s for a closing <strong> tag.
				__(
					'%1$sStock management%2$s needs to be enabled for Inventory Sync to work.',
					'NineteenEightyWoo'
				)
			),
			'<stong>',
			'</strong>',
		);
		?>
	</p>
</div>
