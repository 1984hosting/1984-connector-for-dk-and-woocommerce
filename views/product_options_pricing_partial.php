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
			'label'       => 'Sync Price with DK',
			'description' => 'Enables the 1984 DK Connection plugin to sync the products\'s price between WooCommerce and dkPlus.',
			'desc_tip'    => true,
			'cbvalue'     => 'true',
		),
	);
	?>
	<p class="form-field">
		In order for price sync to work, the <abbr title="Stock Keeping Unit">SKU</abbr> needs to be set and must equal the Item Code in DK. It is set in the <strong>Inventory Panel</strong>.
	</p>
</div>
