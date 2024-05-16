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
class WooUpdateProduct {
	/**
	 * The class constructor
	 */
	public function __construct() {
		add_action(
			'woocommerce_update_product',
			array( __CLASS__, 'on_product_update' ),
			10,
			2
		);

		add_action(
			'before_delete_post',
			array( __CLASS__, 'before_post_delete' ),
			10,
			2
		);
	}

	/**
	 * Update product in DK when it is is updated in WooCommerce
	 *
	 * @param int        $id The product's post ID (not used).
	 * @param WC_Product $product The WooCommrece product.
	 */
	public static function on_product_update(
		int $id,
		WC_Product $product
	): void {
		if ( defined( 'DOING_CRON' ) ) {
			return;
		}

		if ( defined( 'DOING_DK_SYNC' ) ) {
			return;
		}

		if ( false === self::should_sync( $product ) ) {
			return;
		}

		if ( true === ExportProduct::is_in_dk( $product ) ) {
			ExportProduct::update_in_dk( $product );
		} else {
			ExportProduct::create_in_dk( $product );
		}
	}

	/**
	 * If the post that's going to be deleted is a WooCommerce product, a PUT
	 * request is sent to DK on make sure that it does not appear in the store
	 * until it is enabled again in DK.
	 *
	 * @param int $post_id The Post ID to check for.
	 */
	public static function before_post_delete( int $post_id ): void {
		if ( defined( 'DOING_CRON' ) ) {
			return;
		}

		if ( defined( 'DOING_DK_SYNC' ) ) {
			return;
		}

		if ( 'product' === get_post_type( $post_id ) ) {
			$wc_product = wc_get_product( $post_id );

			if ( false === self::should_sync( $wc_product ) ) {
				return;
			}

			ExportProduct::hide_in_dk( $wc_product );
		}
	}

	/**
	 * Check if the product should sync with DK
	 *
	 * @param WC_Product $product The WooCommrece product.
	 *
	 * @return bool True if it should sync, false if not.
	 */
	public static function should_sync( WC_Product $product ): bool {
		if ( false === (bool) $product->get_sku() ) {
			return false;
		}

		return true;
	}
}
