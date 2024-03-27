<?php

declare(strict_types = 1);

namespace NineteenEightyFour\NineteenEightyWoo\Export;

use NineteenEightyFour\NineteenEightyWoo\Service\DKApiRequest;
use NineteenEightyFour\NineteenEightyWoo\Config;
use WC_Product;
use WP_Error;
use WC_Tax;
use WC_Product_Variation;

/**
 * The Product Export class
 *
 * Provides functions for exporting products to a valid HTTP body for the DK API
 * service.
 *
 * @see https://api.dkplus.is/swagger/ui/index#/Product
 **/
class Product {
	public static function create_in_dk( WC_Product $product ): bool|WP_Error {
		$api_request  = new DKApiRequest();
		$request_body = self::to_dk_product_body( $product );

		$result = $api_request->request_result(
			'/Product/',
			wp_json_encode( $request_body ),
		);

		if ( $result instanceof WP_Error ) {
			return $result;
		}

		if ( 200 !== $result->response_code ) {
			return false;
		}

		return true;
	}

	public static function is_in_dk( WC_Product $product ): bool|WP_Error {
		$api_request = new DKApiRequest();

		$result = $api_request->get_result(
			'/Product/' . $product->get_sku()
		);

		if ( $result instanceof WP_Error ) {
			return $result;
		}

		if ( 200 !== $result->response_code ) {
			return false;
		}

		return true;
	}

	/**
	 * Export a WooCommerce product to a DK API POST body
	 *
	 * @param WC_Product $product The WooCommerce product.
	 */
	public static function to_dk_product_body( WC_Product $product ): array {
		$tax = new WC_Tax();

		$product_props = array(
			'ItemCode'          => $product->get_sku(),
			'Description'       => $product->get_title(),
			'ShowItemInWebShop' => true,
			'CurrencyCode'      => get_woocommerce_currency(),
		);

		if ( $product instanceof WC_Product_Variation ) {
			$product_props['Description2'] = $product->get_attribute_summary();
		}

		if ( 'publish' === $product->get_status() ) {
			$product_props['Inactive']          = false;
			$product_props['ShowItemInWebShop'] = true;
		} else {
			$product_props['Inactive']          = true;
			$product_props['ShowItemInWebShop'] = false;
		}

		$tax_class  = $product->get_tax_class();
		$tax_rate_a = $tax->get_rates( $tax_class );
		$tax_rate_p = array_pop( $tax_rate_a )['rate'];

		$product_props['TaxPercent'] = $tax_rate_p;

		if ( 'no' === get_option( 'woocommerce_prices_include_tax' ) ) {
			$product_props['UnitPrice1'] = wc_get_price_excluding_tax(
				$product
			);
		} else {
			$product_props['UnitPrice1WithTax'] = wc_get_price_including_tax(
				$product
			);
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
