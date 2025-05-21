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
	<?php if ( get_woocommerce_currency() !== $product_currency ) : ?>
	<p class="form-field forex-notice">
		<?php
			echo sprintf(
				// Translators: The %1$s is the product's original currency code and %2$s is the shop's currency.
				esc_html__(
					'As the product price is converted from ‘%1$s’ or is set manually to ‘%2$s’ using the ‘Foreign Prices’ feature in DK, changes to the product price in WooCommerce will not be reflected in DK due to current limitations and will be overwritten on sync. You can change the price and currency in DK.',
					'1984-dk-woo'
				),
				esc_html( $product_currency ),
				esc_html( get_woocommerce_currency() )
			);
		?>
	</p>

	<?php else : ?>

		<?php
		$price_sync_meta = $wc_product->get_meta( '1984_woo_dk_price_sync', true, 'edit' );
		wp_nonce_field( 'set_1984_woo_dk_price_sync', 'set_1984_woo_dk_price_sync_nonce' );
		woocommerce_wp_radio(
			array(
				'id'      => '1984_woo_dk_price_sync',
				'name'    => '1984_woo_dk_price_sync',
				'label'   => __( 'Sync Price with DK', '1984-dk-woo' ),
				'value'   => $price_sync_meta,
				'options' => array(
					''      => sprintf(
						// Translators: %1$s is the current yes/no value.
						__( 'Use Default (Currently ‘%1$s’)', '1984-dk-woo' ),
						( Config::get_product_price_sync() ? __( 'Yes', '1984-dk-woo' ) : __( 'No', '1984-dk-woo' ) )
					),
					'true'  => __( 'Yes', '1984-dk-woo' ),
					'false' => __( 'No', '1984-dk-woo' ),
				),
			),
		);
		?>
	<?php endif ?>

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
