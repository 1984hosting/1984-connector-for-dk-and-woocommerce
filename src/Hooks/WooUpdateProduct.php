<?php

declare(strict_types = 1);

namespace NineteenEightyFour\NineteenEightyWoo\Hooks;

use NineteenEightyFour\NineteenEightyWoo\Export\Product as ExportProduct;
use NineteenEightyFour\NineteenEightyWoo\Helpers\Product as ProductHelper;

use WP_Post;
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
			'woocommerce_update_product_variation',
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

		add_action(
			'transition_post_status',
			array( __CLASS__, 'post_status_change' ),
			10,
			3
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

		if ( ! ProductHelper::should_sync( $product ) ) {
			return;
		}

		// Create or update the product in DK.
		ExportProduct::create_in_dk( $product );
	}

	/**
	 * Post deletion hook
	 *
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

			if ( ! ProductHelper::should_sync( $wc_product ) ) {
				return;
			}

			ExportProduct::hide_in_dk( $wc_product );
		}
	}

	/**
	 * Post status change hook
	 *
	 * @param string  $new_status The new post status.
	 * @param string  $old_status The new post status (unused).
	 * @param WP_Post $post The WP post object.
	 */
	public static function post_status_change(
		string $new_status,
		string $old_status,
		WP_Post $post
	): void {

		if ( defined( 'DOING_CRON' ) ) {
			return;
		}

		if ( defined( 'DOING_DK_SYNC' ) ) {
			return;
		}

		if ( 'product' !== get_post_type( $post ) ) {
			return;
		}

		$wc_product = wc_get_product( $post );

		if ( ! ProductHelper::should_sync( $wc_product ) ) {
			return;
		}

		switch ( $new_status ) {
			case 'draft':
				ExportProduct::hide_from_webshop_in_dk( $wc_product );
				break;
			case 'publish':
				ExportProduct::show_in_webshop_in_dk( $wc_product );
		}
	}
}
