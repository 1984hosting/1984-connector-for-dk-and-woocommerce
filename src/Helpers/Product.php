<?php

declare(strict_types = 1);

namespace NineteenEightyFour\NineteenEightyWoo\Helpers;

use NineteenEightyFour\NineteenEightyWoo\Config;
use NineteenEightyFour\NineteenEightyWoo\Brick\Math\BigDecimal;
use NineteenEightyFour\NineteenEightyWoo\Brick\Math\RoundingMode;
use NineteenEightyFour\NineteenEightyWoo\Hooks\WooUpdateProduct as WooUpdateProductHooks;
use WC_Product;
use WC_Product_Variation;
use WC_Tax;
use WC_DateTime;
use WP_Post;

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
		if ( true === is_null( WC()->countries ) ) {
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

				return $price->dividedBy( $tax_fraction->plus( 1 ) )->toFloat();
			}
		} else {
			if ( false === empty( $wc_product->get_sale_price() ) ) {
				return $wc_product->get_sale_price();
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
		if ( false === (bool) $wc_product->get_sku() ) {
			return false;
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
	 * Convert a product to variant
	 *
	 * @param int $product_id The product ID.
	 * @param int $parent_id The ID of the new variant's parent.
	 *
	 * @return bool True on success, false on failure.
	 */
	public static function convert_to_variant(
		int $product_id,
		int $parent_id
	): bool {
		if ( 'product' !== get_post_type( $product_id ) ) {
			return false;
		}

		$post       = get_post( $product_id );
		$post_title = $post->post_title;

		set_post_type( $product_id, 'product_variation' );

		$wc_product = wc_get_product( $product_id );

		if ( 'draft' === $wc_product->get_status( 'edit' ) ) {
			$wc_product->set_status( 'private' );
		}

		$wc_product->set_parent_id( $parent_id );
		$wc_product->set_name( $post_title );

		// We indicate it here that this variant was originally a product.
		$wc_product->update_meta_data( '1984_dk_woo_origin', 'product' );

		if ( 0 !== $wc_product->save() ) {
			return $wc_product->get_id();
		}

		return false;
	}
}
