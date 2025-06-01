<?php

declare(strict_types = 1);

namespace NineteenEightyFour\NineteenEightyWoo\Helpers;

use NineteenEightyFour\NineteenEightyWoo\Import\ProductVariations as ImportProductVariations;
use NineteenEightyFour\NineteenEightyWoo\Config;
use NineteenEightyFour\NineteenEightyWoo\Brick\Math\BigDecimal;
use NineteenEightyFour\NineteenEightyWoo\Brick\Math\RoundingMode;
use WC_Product;
use WC_Product_Variation;
use WC_Tax;
use WC_DateTime;
use WC_Product_Variable;

/**
 * The Product Helper Class
 *
 * Contains helper functions for interpeting WooCommerce products.
 */
class Product {
	/**
	 * Check if name sync is enabled for a product
	 *
	 * Checks for the `1984_woo_dk_name_sync` meta is set for the product and
	 * uses that. If not, it uses the global setting for name sync.
	 *
	 * @param WC_Product $wc_product The WooCommerce product.
	 *
	 * @return bool True if name sync is enabled, false if not.
	 */
	public static function name_sync_enabled( WC_Product $wc_product ): bool {
		if ( $wc_product instanceof WC_Product_Variation ) {
			$wc_product = wc_get_product( $wc_product->get_parent_id() );
		}

		$meta_value = $wc_product->get_meta(
			'1984_woo_dk_name_sync',
			true,
			'edit'
		);

		switch ( $meta_value ) {
			case 'true':
				return true;
			case 'false':
				return false;
		}

		return Config::get_product_name_sync();
	}

	/**
	 * Check if price sync is enabled for a product
	 *
	 * Checks for the `1984_woo_dk_price_sync` meta is set for the product and
	 * uses that. If not, it uses the global setting for price sync.
	 *
	 * @param WC_Product $wc_product The WooCommerce product.
	 *
	 * @return bool True if price sync is enabled, false if not.
	 */
	public static function price_sync_enabled( WC_Product $wc_product ): bool {
		if ( $wc_product instanceof WC_Product_Variation ) {
			if ( self::variation_price_override( $wc_product ) ) {
				return false;
			}
			$parent = wc_get_product( $wc_product->get_parent_id() );
			if ( $parent ) {
				$wc_product = $parent;
			}
		}

		$product_dk_currency = $wc_product->get_meta(
			'1984_woo_dk_dk_currency',
			true,
			'edit'
		);

		if (
			( ! empty( $product_dk_currency ) ) &&
			( get_woocommerce_currency() !== $product_dk_currency )
		) {
			return false;
		}

		$meta_value = $wc_product->get_meta(
			'1984_woo_dk_price_sync',
			true,
			'edit'
		);

		switch ( $meta_value ) {
			case 'true':
				return true;
			case 'false':
				return false;
		}

		return Config::get_product_price_sync();
	}

	/**
	 * Check if quantity sync is enabled for a product
	 *
	 * Checks for the `1984_woo_dk_stock_sync` meta is set for the product and
	 * uses that. If not, it uses the global setting for price sync.
	 *
	 * @param WC_Product $wc_product The WooCommerce product.
	 *
	 * @return bool True if quantity sync is enabled, false if not.
	 */
	public static function quantity_sync_enabled(
		WC_Product $wc_product
	): bool {
		if ( $wc_product instanceof WC_Product_Variation ) {
			if ( self::variation_inventory_override( $wc_product ) ) {
				return false;
			}

			$parent = wc_get_product( $wc_product->get_parent_id() );
			if ( $parent ) {
				$wc_product = $parent;
			}
		}

		$meta_value = $wc_product->get_meta(
			'1984_woo_dk_stock_sync',
			true,
			'edit'
		);

		switch ( $meta_value ) {
			case 'true':
				return true;
			case 'false':
				return false;
		}

		return Config::get_product_quantity_sync();
	}

	/**
	 * Get the tax rate for a product
	 *
	 * @param WC_Product $wc_product The WooCommerce product.
	 *
	 * @return float A floating point representation of the tax rate percentage.
	 */
	public static function tax_rate( WC_Product $wc_product ): float {
		if ( is_null( WC()->countries ) ) {
			return 0;
		}

		$wc_taxes = new WC_Tax();

		$tax_class = $wc_product->get_tax_class();
		$tax_rates = $wc_taxes->get_rates( $tax_class );

		return array_pop( $tax_rates )['rate'];
	}

	/**
	 * Get a string representation for a sale from/to date that the DK API
	 * understands
	 *
	 * @param string     $which Valid values are 'from' and 'to'.
	 * @param WC_Product $wc_product The WooCommerce product.
	 *
	 * @return string A formated date-time string or an empty string.
	 */
	public static function format_date_on_sale_for_dk(
		string $which,
		WC_Product $wc_product
	): string {
		switch ( $which ) {
			case 'from':
				$date = $wc_product->get_date_on_sale_from();
				break;
			case 'to':
				$date = $wc_product->get_date_on_sale_to();
				break;
			default:
				return '';
		}

		if ( $date instanceof WC_DateTime ) {
			return $date->format( 'c' );
		}

		return '';
	}

	/**
	 * Format a sale price that the DK API understands
	 *
	 * Calculates the price without tax if prices include tax in the WooCommerce
	 * shop.
	 *
	 * @param WC_Product $wc_product The WooCommerce product.
	 *
	 * @return float A floating-point representation of the sale price before
	 *               tax.
	 */
	public static function format_sale_price_for_dk(
		WC_Product $wc_product
	): float {
		if ( wc_prices_include_tax() ) {
			if ( ! empty( $wc_product->get_sale_price() ) ) {
				$price    = BigDecimal::of( $wc_product->get_sale_price() );
				$tax_rate = BigDecimal::of( self::tax_rate( $wc_product ) );

				$tax_fraction = $tax_rate->dividedBy(
					100,
					4,
					roundingMode: RoundingMode::HALF_UP
				);

				return $price->dividedBy(
					$tax_fraction->plus( 1 ),
					roundingMode: RoundingMode::HALF_UP
				)->toFloat();
			}
		} else {
			if ( ! empty( $wc_product->get_sale_price() ) ) {
				return (float) $wc_product->get_sale_price();
			}
		}

		return 0;
	}

	/**
	 * Check if the product should sync with DK
	 *
	 * @param WC_Product $wc_product The WooCommrece product.
	 *
	 * @return bool True if it should sync, false if not.
	 */
	public static function should_sync( WC_Product $wc_product ): bool {
		if ( ! (bool) $wc_product->get_sku() ) {
			return false;
		}

		$product_origin = $wc_product->get_meta(
			'1984_dk_woo_origin',
			true,
			'edit'
		);

		if ( $product_origin === 'product_variation' ) {
			return false;
		}

		$parent_id = $wc_product->get_parent_id();

		if ( $parent_id !== 0 ) {
			$parent = wc_get_product( $parent_id );

			if ( ! $parent ) {
				return false;
			}

			$parent_origin = $parent->get_meta(
				'1984_dk_woo_origin',
				true,
				'edit'
			);

			if ( $parent_origin === 'product_variation' ) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Get the original (DK) currency for a product
	 *
	 * @param WC_Product $wc_product The WooCommrece product.
	 *
	 * @return string The 3-digit ISO currency code.
	 */
	public static function get_currency( WC_Product $wc_product ): string {
		$product_currency = $wc_product->get_meta(
			'1984_woo_dk_dk_currency',
			true,
			'edit'
		);

		if ( empty( $product_currency ) ) {
			return get_woocommerce_currency();
		}

		return $product_currency;
	}

	/**
	 * Get DK ledger codes for a WooCommerce product
	 *
	 * @param WC_Product $wc_product The WooCommrece product.
	 *
	 * @return false|object{
	 *     sales: string,
	 *     purchase: string
	 * }
	 */
	public static function get_ledger_codes( WC_Product $wc_product ): false|object {
		switch ( $wc_product->get_tax_class() ) {
			case 'reduced-rate':
				return (object) array(
					'sales'    => Config::get_ledger_code( 'reduced' ),
					'purchase' => Config::get_ledger_code( 'reduced_purchase' ),
				);
			case '':
				return (object) array(
					'sales'    => Config::get_ledger_code( 'standard' ),
					'purchase' => Config::get_ledger_code( 'standard_purchase' ),
				);
		}
		return false;
	}

	/**
	 * Check if a product variation has price override enabled
	 *
	 * @param WC_Product_Variation $wc_product_variation The variation.
	 *
	 * @return bool True if price override is set, false if not.
	 */
	public static function variation_price_override(
		WC_Product_Variation $wc_product_variation
	): bool {
		if (
			$wc_product_variation->get_meta(
				'1984_dk_woo_variable_price_override',
				true,
				'edit'
			)
		) {
			return true;
		}
		return false;
	}

	/**
	 * Check if a product variation has inventory override enabled
	 *
	 * @param WC_Product_Variation $wc_product_variation The variation.
	 *
	 * @return bool True if override is set. False if not.
	 */
	public static function variation_inventory_override(
		WC_Product_Variation $wc_product_variation
	): bool {
		if (
			$wc_product_variation->get_meta(
				'1984_dk_woo_variable_inventory_override',
				true,
				'edit'
			)
		) {
			return true;
		}
		return false;
	}

	/**
	 * Check if a product variation has inventory tracking in WooCommerce enabled
	 *
	 * @param WC_Product_Variation $wc_product_variation The product variation.
	 *
	 * @return bool True if override is set. False if not.
	 */
	public static function variation_inventory_track_in_wc(
		WC_Product_Variation $wc_product_variation
	): bool {
		if (
			$wc_product_variation->get_meta(
				'1984_dk_woo_variable_quantity_track_in_wc',
				true,
				'edit'
			)
		) {
			return true;
		}
		return false;
	}

	/**
	 * Get the descriptions for a product's variation attribute codes
	 *
	 * This is essentially the "human readable" version of the variations'
	 * attribute names.
	 *
	 * @param WC_Product_Variation|WC_Product_Variable $wc_product The WooCommerce product.
	 *
	 * @return array An array containing the attribute codes as keys and descriptions as values.
	 */
	public static function attribute_descriptions(
		WC_Product_Variation|WC_Product_Variable $wc_product
	): array {
		$variations = ImportProductVariations::get_variations();
		$parent     = wc_get_product( $wc_product->get_parent_id() );

		if ( $parent ) {
			$variant_code = $parent->get_meta( '1984_dk_woo_variant_code' );
			$attributes   = $parent->get_attributes( 'edit' );
		} else {
			$variant_code = $wc_product->get_meta( '1984_dk_woo_variant_code' );
			$attributes   = $wc_product->get_attributes( 'edit' );
		}

		$descriptions = array();

		$attributes = $variations[ $variant_code ]->attributes;

		if (
			empty( $variant_code ) ||
			! array_key_exists( $variant_code, $variations )
		) {
			foreach ( array_keys( $attributes ) as $attribute ) {
				$descriptions[ $attribute ] = $attribute;
			}
		} else {
			foreach ( array_keys( $attributes ) as $attribute ) {
				$descriptions[ $attribute ] = $variations[ $variant_code ]->attributes[ $attribute ]->description;
			}
		}

		return $descriptions;
	}

	/**
	 * Get attribute label description
	 *
	 * Gets the human-readable description for a product's specific attribute
	 * code as set in DK.
	 *
	 * @param WC_Product_Variation|WC_Product_Variable $wc_product The variable product or variation to check.
	 * @param string                                   $attribute_code The attribute code to check.
	 *
	 * @return string The attribute label description.
	 */
	public static function attribute_label_description(
		WC_Product_Variation|WC_Product_Variable $wc_product,
		string $attribute_code
	): string {
		$variations = ImportProductVariations::get_variations();
		$parent     = wc_get_product( $wc_product->get_parent_id() );
		$value      = $wc_product->get_attribute( $attribute_code );

		if ( $parent ) {
			$variant_code = $parent->get_meta( '1984_dk_woo_variant_code' );
		} else {
			$variant_code = $wc_product->get_meta( '1984_dk_woo_variant_code' );
		}

		if ( empty( $variant_code ) ) {
			return $value;
		}

		return $variations[ $variant_code ]->attributes[ $attribute_code ]->description;
	}

	/**
	 * Get a single attribute value description as set in DK
	 *
	 * Gets the human-readable description for a product's specific attribute
	 * code's value code as set in DK.
	 *
	 * @param WC_Product_Variation|WC_Product_Variable $wc_product The variable product or variation to check.
	 * @param string                                   $attribute_code The attribute code to check.
	 * @param string                                   $value_code The value code to check.
	 *
	 * @return string The attribute value description.
	 */
	public static function attribute_value_description(
		WC_Product_Variation|WC_Product_Variable $wc_product,
		string $attribute_code,
		string $value_code
	): string {
		$variations = ImportProductVariations::get_variations();
		$parent     = wc_get_product( $wc_product->get_parent_id() );

		if ( $parent ) {
			$variant_code = $parent->get_meta( '1984_dk_woo_variant_code' );
		} else {
			$variant_code = $wc_product->get_meta( '1984_dk_woo_variant_code' );
		}

		$values = $variations[ $variant_code ]->attributes[ $attribute_code ]->values;

		if (
			empty( $variant_code ) ||
			! array_key_exists( $value_code, $values ) ||
			! property_exists( $values[ $value_code ], 'name' )
		) {
			return $value_code;
		}

		return $values[ $value_code ]->name;
	}

	/**
	 * Get attribute value description for a product variation
	 *
	 * @param WC_Product_Variation $wc_product The product variation to check.
	 * @param string               $attribute_code The attribute code to get the description for.
	 *
	 * @return string              The attribute value description.
	 */
	public static function variation_attribute_value_description(
		WC_Product_Variation $wc_product,
		string $attribute_code,
	): string {
		$variations = ImportProductVariations::get_variations();
		$parent     = wc_get_product( $wc_product->get_parent_id() );
		$value      = $wc_product->get_attribute( $attribute_code );

		if ( $parent ) {
			$variant_code = $parent->get_meta( '1984_dk_woo_variant_code' );
		} else {
			$variant_code = $wc_product->get_meta( '1984_dk_woo_variant_code' );
		}

		if ( empty( $variant_code ) ) {
			return $value;
		}

		return $variations[ $variant_code ]->attributes[ $attribute_code ]->values[ $value ]->name;
	}

	/**
	 * Get all attributes and descriptions for a product variation
	 *
	 * @param WC_Product_Variation $variation The product variation.
	 *
	 * @return array An array containing the label descriptions as the keys and
	 *               the value descriptions as values.
	 */
	public static function attributes_with_descriptions(
		WC_Product_Variation $variation,
	): array {
		$summary_array = array();

		foreach ( $variation->get_attributes() as $label => $value ) {
			if ( Config::get_use_attribute_description() ) {
				$label_description = self::attribute_label_description(
					$variation,
					$label
				);
			} else {
				$label_description = $label;
			}

			if ( Config::get_use_attribute_value_description() ) {
				$value_description = self::attribute_value_description(
					$variation,
					$label,
					$value
				);
			} else {
				$value_description = $label;
			}

			$summary_array[ $label_description ] = $value_description;
		}

		return $summary_array;
	}

	/**
	 * Get a string containing a summary of a variation's attributes
	 *
	 * @param WC_Product_Variation $variation The product variation.
	 */
	public static function attribute_summary_with_descriptions(
		WC_Product_Variation $variation,
	): string {
		$pairs = array();

		$attributes = self::attributes_with_descriptions( $variation );

		foreach ( $attributes as $label => $value ) {
			$pairs[] = "$label: $value";
		}

		return implode( ', ', $pairs );
	}
}
