<?php

declare(strict_types = 1);

namespace NineteenEightyFour\NineteenEightyWoo\Export;

use WC_Product;

/**
 * The Product Export class
 *
 * Provides functions for exporting products to a valid HTTP body for the DK API
 * service.
 *
 * @see https://api.dkplus.is/swagger/ui/index#/Product
 **/
class Product {
	/**
	 * Export a WooCommerce product to a DK API POST body
	 *
	 * @param WC_Product $product The WooCommerce product.
	 */
	public static function to_dk_product_body( WC_Product $product ): array {
		$product_props = array(
			'ItemCode'          => $product->get_sku(),
			'Description'       => $product->get_title(),
			'ShowItemInWebShop' => true,
		);

		if ( 'publish' === $product->get_status() ) {
			$product_props['inactive']          = false;
			$product_props['ShowItemInWebShop'] = true;
		} else {
			$product_props['inactive']          = true;
			$product_props['ShowItemInWebShop'] = false;
		}

		if ( 'no' === get_option( 'woocommerce_prices_include_tax' ) ) {
			$product_props['UnitPrice1'] = $product->get_price();
		} else {
			$product_props['UnitPrice1WithTax'] = $product->get_price();
		}

		return $product_props;
	}

	/**
	 * Export a WooCommerce product to a DK API POST body based on ID
	 *
	 * @param int $product_id The product ID.
	 */
	public static function id_to_dk_product_body( int $product_id ): array {
		$product = wc_get_product( $product_id );
		return self::to_dk_product_body( $product );
	}
}
