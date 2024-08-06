<?php

declare(strict_types = 1);

namespace NineteenEightyFour\NineteenEightyWoo\Hooks;

use WC_Product;

/**
 * The WooMetaboxes class
 *
 * The WooMetaboxes class adds fields to the WooCommerce editor metaboxes that
 * handle properties facilitating price and inventory sync with DK.
 */
class WooMetaboxes {
	const PROTECTED_META = array(
		'1984_woo_dk_price_sync',
		'1984_woo_dk_stock_sync',
		'1984_woo_dk_name_sync',
		'1984_woo_dk_dk_currency',
		'1984_woo_dk_credit_invoice_number',
		'1984_woo_dk_invoice_number',
		'last_downstream_sync',
	);

	/**
	 * The constructor for the WooMetaboxes class
	 */
	public function __construct() {
		add_action(
			'woocommerce_product_options_pricing',
			array( $this, 'render_product_options_pricing_partial' )
		);

		add_action(
			'woocommerce_product_options_advanced',
			array( $this, 'render_product_options_advanced_partial' )
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
		require dirname( __DIR__, 2 ) . '/views/product_options_pricing_partial.php';
	}

	/**
	 * Render the advanced partial
	 */
	public static function render_product_options_advanced_partial(): void {
		require dirname( __DIR__, 2 ) .
			'/views/product_options_advanced_partial.php';
	}

	/**
	 * Render the SKU metabox partial
	 */
	public static function render_product_options_sku_partial(): void {
		require dirname( __DIR__, 2 ) . '/views/product_options_sku_partial.php';
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
		if ( in_array( $meta_key, self::PROTECTED_META, true ) ) {
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
	 * @param WC_Product $wc_product The post object (unused).
	 */
	public function save_product_meta(
		int $id,
		WC_Product $wc_product
	): void {
		// As the hook seems to be run twice for some reason, this should
		// prevent the function to run twice during the same HTTP request.
		if ( defined( '1984_DK_WOO_META_UPDATE_HAS_RUN' ) ) {
			return;
		}

		define( '1984_DK_WOO_META_UPDATE_HAS_RUN', true );

		self::set_product_sync_meta_from_post( $wc_product, 'price' );
		self::set_product_sync_meta_from_post( $wc_product, 'stock' );
		self::set_product_sync_meta_from_post( $wc_product, 'name' );
	}

	/**
	 * Set a product sync meta value from a POST superglobal
	 *
	 * Checks the relevant nonces and sets the `1984_woo_dk_` product meta from
	 * a $_POST superglobal.
	 *
	 * @param WC_Product $wc_product The WooCommerce product.
	 * @param string     $meta_key The relevant meta key such as price, stock or name.
	 */
	public static function set_product_sync_meta_from_post(
		WC_Product $wc_product,
		string $meta_key
	): void {
		$nonce_superglobal = "set_1984_woo_dk_{$meta_key}_sync_nonce";
		$wc_meta_key       = "1984_woo_dk_{$meta_key}_sync";

		if (
			! empty( $_POST[ $nonce_superglobal ] ) &&
			wp_verify_nonce(
				sanitize_text_field(
					wp_unslash( $_POST[ $nonce_superglobal ] )
				),
				"set_1984_woo_dk_{$meta_key}_sync"
			)
		) {
			if ( isset( $_POST[ $wc_meta_key ] ) ) {
				switch ( $_POST[ $wc_meta_key ] ) {
					case 'true':
						$wc_product->update_meta_data( $wc_meta_key, 'true' );
						break;
					case 'false':
						$wc_product->update_meta_data( $wc_meta_key, 'false' );
						break;
					default:
						$wc_product->update_meta_data( $wc_meta_key, '' );
				}

				$wc_product->save_meta_data();
			}
		}
	}
}
