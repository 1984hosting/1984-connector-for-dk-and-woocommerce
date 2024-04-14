<?php

declare(strict_types = 1);

namespace NineteenEightyFour\NineteenEightyWoo\Hooks;

use NineteenEightyFour\NineteenEightyWoo\Export\Product as ExportProduct;
use WC_Product;

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
			array( $this, 'render_product_options_sku_partial' )
		);

		add_action(
			'woocommerce_update_product',
			array( $this, 'save_product_meta' ),
			10,
			2
		);

		add_filter(
			'is_protected_meta',
			array( __CLASS__, 'protect_meta' ),
			10,
			2
		);
	}

	/**
	 * Render the pricing metabox partial
	 */
	public static function render_product_options_pricing_partial(): void {
		require __DIR__ . '/../../views/product_options_pricing_partial.php';
	}

	/**
	 * Render the SKU metabox partial
	 */
	public static function render_product_options_sku_partial(): void {
		require __DIR__ . '/../../views/product_options_sku_partial.php';
	}

	/**
	 * Protect the meta values so they don't appear in the Custom Fields box
	 *
	 * This prevents the kennitala value from appearing in the Custom Fields
	 * metabox, overriding the order editor.
	 *
	 * @param bool   $protected Wether the meta value is already protected.
	 * @param string $meta_key The meta key.
	 */
	public static function protect_meta(
		bool $protected,
		string $meta_key
	): bool {
		if ( '1984_woo_dk_price_sync' === $meta_key ) {
			return true;
		}

		if ( '1984_woo_dk_stock_sync' === $meta_key ) {
			return true;
		}

		return $protected;
	}

	/**
	 * Save the NineteenEightyWoo related meta tags for a product using superglobals
	 *
	 * Fired during the `save_post_product` hook.
	 *
	 * @param int        $id The post ID for the product.
	 * @param WC_Product $product The post object (unused).
	 */
	public function save_product_meta(
		int $id,
		WC_Product $product
	): void {
		// As the hook seems to be run twice for some reason, this should
		// prevent the function to run twice during the same HTTP request.
		global $nineteen_eighty_four_woo_dk_meta_update_has_run;
		if ( isset( $nineteen_eighty_four_woo_dk_meta_update_has_run ) ) {
			return;
		}

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
			$product->update_meta_data( '1984_woo_dk_price_sync', true );
			$product->save_meta_data();
		} else {
			$product->update_meta_data( '1984_woo_dk_price_sync', false );
			$product->save_meta_data();
		}

		if ( isset( $_POST['1984_woo_dk_stock_sync'] ) ) {
			$product->update_meta_data( '1984_woo_dk_stock_sync', true );
			$product->save_meta_data();
		} else {
			$product->update_meta_data( '1984_woo_dk_stock_sync', false );
			$product->save_meta_data();
		}

		$nineteen_eighty_four_woo_dk_meta_update_has_run = true;
		global $nineteen_eighty_four_woo_dk_meta_update_has_run;
	}

	/**
	 * Toggle the "DK Handles Inventory" meta as needed when a product is saved
	 *
	 * Nonce verification is handled by WooCommerce already when this is run.
	 *
	 * @param WC_Product $product The WooCommrece product.
	 */
	public static function update_dk_meta_on_update(
		WC_Product $product
	): void {
		if ( false === isset( $_POST['set_1984_woo_dk_stock_sync_nonce'] ) ) {
			return;
		}

		if (
			false ===
			wp_verify_nonce(
				sanitize_text_field(
					wp_unslash( $_POST['set_1984_woo_dk_stock_sync_nonce'] )
				),
				'set_1984_woo_dk_stock_sync'
			)
		) {
			return;
		}

		$set_status = isset( $_POST['1984_woo_dk_stock_sync'] );

		if ( true === $set_status ) {
			$product->update_meta_data( '1984_woo_dk_stock_sync', true );
			$product->set_stock_quantity( 0 );
			$product->set_stock_status( 'instock' );
			$product->set_backorders( 'yes' );
			$product->save_meta_data();
		} else {
			$product->update_meta_data( '1984_woo_dk_stock_sync', false );
			$product->save_meta_data();
		}
	}
}
