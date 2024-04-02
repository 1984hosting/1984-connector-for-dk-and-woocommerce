<?php

declare(strict_types = 1);

namespace NineteenEightyFour\NineteenEightyWoo\Export;

use NineteenEightyFour\NineteenEightyWoo\Service\DKApiRequest;
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
	const API_PATH = '/Product/';

	/**
	 * Create a product record in DK representing a WooCommerce product
	 *
	 * Sends a POST request to the /Product endpoint with product information.
	 *
	 * @param WC_Product $product The WooCommerce product.
	 *
	 * @return bool|WP_Error True on success, false if connection was
	 *                       established but the request was rejected, WC_Error
	 *                       if there was a connection error.
	 */
	public static function create_in_dk( WC_Product $product ): bool|WP_Error {
		if ( false === (bool) $product->get_sku() ) {
			return false;
		}

		$api_request  = new DKApiRequest();
		$request_body = self::to_dk_product_body( $product );

		$result = $api_request->request_result(
			self::API_PATH,
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

	/**
	 * Update a WooCommerce product in DK
	 *
	 * Sends a PUT request (why it isn't a PATHCH request is beyond me) to DK
	 * for updating a product record.
	 *
	 * @param WC_Product $product The WooCommerce product.
	 *
	 * @return bool|WP_Error True on success, false if connection was
	 *                       established but the request was rejected, WC_Error
	 *                       if there was a connection error.
	 */
	public static function update_in_dk( WC_Product $product ): bool|WP_Error {
		$api_request  = new DKApiRequest();
		$request_body = self::to_dk_product_body( $product );

		$item_code = $request_body['ItemCode'];
		unset( $request_body['ItemCode'] );

		$result = $api_request->request_result(
			self::API_PATH . $item_code,
			wp_json_encode( $request_body ),
			'PUT',
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
	 * Check if a WooCommerce product is in DK
	 *
	 * Checks if a product record exsists in DK with a ProductCode attribute
	 * that equals a WooCommerce product's SKU.
	 *
	 * @param WC_Product $product The WooCommerce product.
	 *
	 * @return bool|WP_Error True on success, false if connection was
	 *                       established but the request was rejected, WC_Error
	 *                       if there was a connection error.
	 */
	public static function is_in_dk( WC_Product $product ): bool|WP_Error {
		$api_request = new DKApiRequest();

		$result = $api_request->get_result(
			self::API_PATH . $product->get_sku()
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

		$taxes      = new WC_Tax();
		$tax_class  = $product->get_tax_class();
		$tax_rate_a = $taxes->get_rates( $tax_class );
		$tax_rate_p = array_pop( $tax_rate_a )['rate'];

		$product_props['TaxPercent'] = $tax_rate_p;

		if ( true === wc_prices_include_tax() ) {
			$product_props['UnitPrice1WithTax'] = $product->get_regular_price();
			if ( false === empty( $product->get_sale_price() ) ) {
				$product_props['PropositionPrice'] = (
					$product->get_sale_price() / ( 1 + ( $tax_rate_p / 100 ) )
				);
			} else {
				$product_props['PropositionPrice'] = 0;
			}
		} else {
			$product_props['UnitPrice1'] = $product->get_regular_price();
			if ( false === empty( $product->get_sale_price() ) ) {
				$product_props['PropositionPrice'] = $product->get_sale_price();
			} else {
				$product_props['PropositionPrice'] = 0;
			}
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
