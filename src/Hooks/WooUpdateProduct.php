<?php

declare(strict_types = 1);

namespace NineteenEightyFour\NineteenEightyWoo\Hooks;

use NineteenEightyFour\NineteenEightyWoo\Export\Product as ExportProduct;
use NineteenEightyFour\NineteenEightyWoo\Export\Inventory as ExportInventory;

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
			'woocommerce_variation_set_stock',
			array( __CLASS__, 'on_variation_set_stock' ),
			10,
			2
		);

		add_action(
			'woocommerce_product_set_stock',
			array( __CLASS__, 'on_product_set_stock' ),
			10,
			2
		);

		add_action(
			'woocommerce_update_product',
			array( __CLASS__, 'on_product_update' ),
			10,
			2
		);
	}

	/**
	 * Update inventory count in DK when a variation stock count is changed manually
	 *
	 * @param WC_Product $product The WooCommerce product.
	 */
	public static function on_variation_set_stock(
		WC_Product $product
	): void {
		ExportInventory::add_or_update_count_in_dk( $product );
	}

	/**
	 * Update inventory count in DK when a product stock count is changed manually
	 *
	 * @param WC_Product $product The WooCommerce product.
	 */
	public static function on_product_set_stock(
		WC_Product $product
	): void {
		ExportInventory::add_or_update_count_in_dk( $product );
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
