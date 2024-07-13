<?php

declare(strict_types = 1);

namespace NineteenEightyFour\NineteenEightyWoo\Hooks;

use NineteenEightyFour\NineteenEightyWoo\Import\ProductVariations;
use NineteenEightyFour\NineteenEightyWoo\Helpers\Product as ProductHelper;
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

		add_action(
			'woocommerce_new_product_variation',
			array( __CLASS__, 'set_price_to_same_as_parent' ),
			10,
			2
		);

		add_action(
			'woocommerce_new_product_variation',
			array( __CLASS__, 'set_origin_to_same_as_parent' ),
			10,
			2
		);

		add_filter(
			'woocommerce_variation_option_name',
			array( __CLASS__, 'filter_variation_option_name' ),
			10,
			1
		);

		add_filter(
			'woocommerce_attribute_label',
			array( __CLASS__, 'filter_variation_label' ),
			10,
			3
		);

		add_filter(
			'woocommerce_order_item_display_meta_value',
			array( __CLASS__, 'filter_woocommerce_order_meta_value' ),
			10,
			1
		);
	}

	/**
	 * Filter order meta values
	 *
	 * Filters displayed order meta, replacing attribute codes with their
	 * names/descriptions.
	 *
	 * @param string $meta_value The meta value to filter.
	 */
	public static function filter_woocommerce_order_meta_value( string $meta_value ): string {
		return ProductVariations::get_attribute_name( $meta_value );
	}

	/**
	 * Filter variation labels
	 *
	 * Filters variation labels, replacing the reference code in DK with their
	 * name/description if available.
	 *
	 * @param string $label The variation label.
	 */
	public static function filter_variation_label( string $label ): string {
		$variation_attribute = ProductVariations::get_attribute( $label );
		if ( ! $variation_attribute ) {
			return $label;
		}
		return $variation_attribute->description;
	}

	/**
	 * Filter variation option name
	 *
	 * Changes how variation attribute names appear in the drop down menu on
	 * each product page, replacing the reference code from DK with its
	 * name/description.
	 *
	 * @param string $name The variation name code.
	 */
	public static function filter_variation_option_name( string $name ): string {
		return ProductVariations::get_attribute_name( $name );
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

		if ( empty( $parent->get_sku() ) ) {
			return;
		}

		if (
			'product_variation' ===
			$product_variation->get_meta( '1984_dk_woo_origin', true, 'edit' )
		) {
			return;
		}

		if (
			'product_variation' ===
			$parent->get_meta( '1984_dk_woo_origin', true, 'edit' )
		) {
			return;
		}

		$product_variation->set_sku(
			$parent->get_sku() . '-' . (string) $product_variation->get_id()
		);
		$product_variation->save();
	}

	public static function set_price_to_same_as_parent(
		int $id,
		WC_Product_Variation $product_variation
	): void {
		$parent_id = $product_variation->get_parent_id();
		$parent    = wc_get_product( $parent_id );

		$price = $parent->get_meta( '1984_dk_woo_price', true, 'edit' );

		if ( is_object( $price ) ) {
			$product_variation->set_regular_price( $price->price );
			$product_variation->set_sale_price( $price->sale_price );
			$product_variation->set_date_on_sale_from( $price->date_on_sale_from );
			$product_variation->set_date_on_sale_to( $price->date_on_sale_to );

			$product_variation->save();
		}
	}

	public static function set_origin_to_same_as_parent(
		int $id,
		WC_Product_Variation $product_variation
	): void {
		$parent_id = $product_variation->get_parent_id();
		$parent    = wc_get_product( $parent_id );

		$product_variation->update_meta_data(
			'1984_dk_woo_origin',
			$parent->get_meta( '1984_dk_woo_origin', true, 'edit' )
		);

		$product_variation->save();
	}
}
