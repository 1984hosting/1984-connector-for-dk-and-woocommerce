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
		The <abbr title="Stock Keeping Unit">SKU</abbr> needs to be set and must equal the <strong>Item Code</strong> in DK for any 1984 DK Sync functionality to work.
		<strong>Stock management</strong> needs to be enabled for Inventory Sync to work as well.
	</p>
</div>
