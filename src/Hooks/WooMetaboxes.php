<?php

declare(strict_types = 1);

namespace NineteenEightyFour\NineteenEightyWoo\Hooks;

use WP_Post;
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
			'save_post_product',
			array( $this, 'save_product_meta' ),
			10,
			3
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
	 * Save the NineteenEightyWoo related meta tags for a product using superglobals
	 *
	 * Fired during the `save_post_product` hook.
	 *
	 * @param int     $id The post ID for the product.
	 * @param WP_Post $post The post object (unused).
	 * @param bool    $update Wether the action is an update.
	 */
	public function save_product_meta(
		int $id,
		WP_Post $post,
		bool $update
	): void {
		if ( true === $update ) {
			$this->save_price_sync_meta( $id );
			$this->save_stock_sync_meta( $id );
		}
	}

	/**
	 * Save the 1984_woo_dk_price_sync post meta from superglobals
	 *
	 * @param int $id The post ID for the product.
	 */
	public function save_price_sync_meta( int $id ): void {
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

	/**
	 * Save the 1984_woo_dk_stock_sync post meta from superglobal
	 *
	 * @param int $id The post ID for the product.
	 */
	public function save_stock_sync_meta( int $id ): void {
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
