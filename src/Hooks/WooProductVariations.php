<?php

declare(strict_types = 1);

namespace NineteenEightyFour\NineteenEightyWoo\Hooks;

use WC_Product_Variation;

/**
 * The Product variation hook class
 *
 * Facilitates things such as unique SKUs for each product variation.
 */
class WooProductVariations {
	/**
	 * The class constructor
	 */
	public function __construct() {
		add_action(
			'woocommerce_new_product_variation',
			array( __CLASS__, 'generate_variation_sku' ),
			10,
			2
		);
	}

	/**
	 * Generate a SKU based on a variation product's parent SKU and the variant's ID
	 *
	 * @param int                  $id The variation ID (never used).
	 * @param WC_Product_Variation $product_variation The product variation.
	 */
	public static function generate_variation_sku(
		int $id,
		WC_Product_Variation $product_variation
	): void {
		$parent_id = $product_variation->get_parent_id();
		$parent    = wc_get_product( $parent_id );

		$product_variation->set_sku(
			$parent->get_sku() . '-' . (string) $product_variation->get_id()
		);
		$product_variation->save();
	}
}
