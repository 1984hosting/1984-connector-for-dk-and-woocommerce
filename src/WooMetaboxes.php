<?php

declare(strict_types = 1);

namespace NineteenEightyFour\NineteenEightyWoo;

/**
 * The WooMetaboxes class
 *
 * The WooMetaboxes class adds fields to the WooCommerce editor metaboxes that
 * handle properties facilitating price and inventory sync with DK.
 */
class WooMetaboxes {
	/**
	 * The constructor for the WooMetaboxes class
	 */
	public function __construct() {
		add_action(
			'woocommerce_product_options_pricing',
			array( $this, 'render_product_options_pricing_partial' )
		);

		add_action(
			'woocommerce_product_options_sku',
			array( __CLASS__, 'render_product_options_sku_partial' )
		);

		add_action(
			'save_post_product',
			array( __CLASS__, 'save_product_meta' ),
			10
		);
	}

	/**
	 * Render the pricing metabox partial
	 */
	public static function render_product_options_pricing_partial(): void {
		require __DIR__ . '/../views/product_options_pricing_partial.php';
	}

	/**
	 * Render the SKU metabox partial
	 */
	public static function render_product_options_sku_partial(): void {
		global $product_object;
		$product_object->set_manage_stock( true );

		require __DIR__ . '/../views/product_options_sku_partial.php';
	}

	public static function save_product_meta( $id ): void {
		self::save_price_sync_meta( $id );
		self::save_stock_sync_meta( $id );
	}

	public static function save_price_sync_meta( $id ) {
		if ( false === isset( $_POST['set_1984_woo_dk_price_sync_nonce'] ) ) {
			return;
		}
		if (
			false === wp_verify_nonce(
				sanitize_text_field(
					wp_unslash( $_POST['set_1984_woo_dk_price_sync_nonce'] )
				),
				'set_1984_woo_dk_price_sync'
			)
		) {
			return;
		}

		if ( isset( $_POST['1984_woo_dk_price_sync'] ) ) {
			update_post_meta( $id, '1984_woo_dk_price_sync', true );
		} else {
			update_post_meta( $id, '1984_woo_dk_price_sync', false );
		}
	}

	public static function save_stock_sync_meta( $id ) {
		if ( false === isset( $_POST['set_1984_woo_dk_stock_sync_nonce'] ) ) {
			return;
		}
		if (
			false === wp_verify_nonce(
				sanitize_text_field(
					wp_unslash( $_POST['set_1984_woo_dk_stock_sync_nonce'] )
				),
				'set_1984_woo_dk_stock_sync'
			)
		) {
			return;
		}

		if ( isset( $_POST['1984_woo_dk_stock_sync'] ) ) {
			update_post_meta( $id, '1984_woo_dk_stock_sync', true );
		} else {
			update_post_meta( $id, '1984_woo_dk_stock_sync', false );
		}
	}
}
