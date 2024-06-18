<?php

declare(strict_types = 1);

namespace NineteenEightyFour\NineteenEightyWoo\Export;

use NineteenEightyFour\NineteenEightyWoo\Service\DKApiRequest;
use NineteenEightyFour\NineteenEightyWoo\Config;
use NineteenEightyFour\NineteenEightyWoo\Helpers\Product as ProductHelper;
use WC_Product;
use WP_Error;
use WC_Product_Variation;
use stdClass;

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
	 * Activate a product in DK
	 *
	 * Sets the `ShowItemInWebShop` paramter for the DK product record in DK to true.
	 *
	 * @param WC_Product $wc_product The WooCommerce product.
	 *
	 * @return bool|WP_Error True on success, false on error from the DK API,
	 *                       WP_Error on connection error.
	 */
	public static function activate_in_dk(
		WC_Product $wc_product
	): bool|WP_Error {
		if ( false === (bool) $wc_product->get_sku() ) {
			return false;
		}

		$api_request = new DKApiRequest();

		$result = $api_request->request_result(
			self::API_PATH . $wc_product->get_sku(),
			wp_json_encode( array( 'Inactive' => false ) ),
			'PUT'
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
	 * Deactivate a product in DK
	 *
	 * Sets the `Inactive` paramter for the DK product record in DK to `true`.
	 *
	 * @param WC_Product $wc_product The WooCommerce product.
	 *
	 * @return bool|WP_Error True on success, false on error from the DK API,
	 *                       WP_Error on connection error.
	 */
	public static function deactivate_in_dk(
		WC_Product $wc_product
	): bool|WP_Error {
		if ( false === (bool) $wc_product->get_sku() ) {
			return false;
		}

		$api_request = new DKApiRequest();

		$result = $api_request->request_result(
			self::API_PATH . $wc_product->get_sku(),
			wp_json_encode( array( 'Inactive' => true ) ),
			'PUT'
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
	 * Hide product from webshop via the DK API
	 *
	 * Sends a PUT request to change the `ShowItemInWebShop` to false in DK.
	 *
	 * @param WC_Product $wc_product The WooCommerce product.
	 *
	 * @return bool|WP_Error True on success, false on error from the DK API,
	 *                       WP_Error on connection error.
	 */
	public static function hide_from_webshop_in_dk(
		WC_Product $wc_product
	): bool|WP_Error {
		if ( false === (bool) $wc_product->get_sku() ) {
			return false;
		}

		if ( true !== self::is_in_dk( $wc_product ) ) {
			return false;
		}

		$api_request = new DKApiRequest();

		$result = $api_request->request_result(
			self::API_PATH . $wc_product->get_sku(),
			wp_json_encode( array( 'ShowItemInWebShop' => false ) ),
			'PUT'
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
	 * Show product in webshop via the DK API
	 *
	 * Sends a PUT request to change the `ShowItemInWebShop` to true in DK.
	 *
	 * @param WC_Product $wc_product The WooCommerce product.
	 *
	 * @return bool|WP_Error True on success, false on error from the DK API,
	 *                       WP_Error on connection error.
	 */
	public static function show_in_webshop_in_dk(
		WC_Product $wc_product
	): bool|WP_Error {
		if ( false === (bool) $wc_product->get_sku() ) {
			return false;
		}

		if ( true !== self::is_in_dk( $wc_product ) ) {
			return false;
		}

		$api_request = new DKApiRequest();

		$result = $api_request->request_result(
			self::API_PATH . $wc_product->get_sku(),
			wp_json_encode( array( 'ShowItemInWebShop' => true ) ),
			'PUT'
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
	 * Create a product record in DK representing a WooCommerce product
	 *
	 * Sends a POST request to the /Product endpoint with product information.
	 * If a product with the same SKU exsists in DK, it will be updated instead.
	 *
	 * @param WC_Product $wc_product The WooCommerce product.
	 *
	 * @return bool|WP_Error True on success, false if connection was
	 *                       established but the request was rejected, WC_Error
	 *                       if there was a connection error.
	 */
	public static function create_in_dk( WC_Product $wc_product ): bool|WP_Error {
		if ( false === (bool) $wc_product->get_sku() ) {
			return false;
		}

		if ( self::is_in_dk( $wc_product ) ) {
			return self::update_in_dk( $wc_product );
		}

		$api_request  = new DKApiRequest();
		$request_body = self::to_new_product_body( $wc_product );

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
	 * @param WC_Product $wc_product The WooCommerce product.
	 *
	 * @return bool|WP_Error True on success, false if connection was
	 *                       established but the request was rejected, WC_Error
	 *                       if there was a connection error.
	 */
	public static function update_in_dk( WC_Product $wc_product ): bool|WP_Error {
		if ( false === (bool) $wc_product->get_sku() ) {
			return false;
		}

		$api_request  = new DKApiRequest();
		$request_body = self::to_updated_product_body( $wc_product );

		$item_code = $wc_product->get_sku();

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
	 * @param WC_Product $wc_product The WooCommerce product.
	 *
	 * @return bool|WP_Error True on success, false if connection was
	 *                       established but the request was rejected, WC_Error
	 *                       if there was a connection error.
	 */
	public static function is_in_dk( WC_Product $wc_product ): bool|WP_Error {
		if ( false === (bool) $wc_product->get_sku() ) {
			return false;
		}

		$api_request = new DKApiRequest();

		$result = $api_request->get_result(
			self::API_PATH . $wc_product->get_sku()
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
	 * Reflect WooCommerce product deletion in DK
	 *
	 * Reflects the product being deleted from WooCommerce by setting the
	 * `ShowItemInWebShop` attribute for the product in DK to `false`.
	 *
	 * This means that the product will not be deleted from or deactivated in DK
	 * as that would lead to unintended consequences.
	 *
	 * @param WC_Product $wc_product The WooCommerce product.
	 *
	 * @return bool|WP_Error WP_Error on connection error, true on success and
	 *                       false on failure.
	 */
	public static function hide_in_dk( WC_Product $wc_product ): bool|WP_Error {
		if ( false === (bool) $wc_product->get_sku() ) {
			return false;
		}

		$api_request  = new DKApiRequest();
		$request_body = self::to_updated_product_body( $wc_product );

		$request_body['ShowItemInWebShop'] = false;

		$result = $api_request->request_result(
			self::API_PATH . $wc_product->get_sku(),
			wp_json_encode( $request_body ),
			'PUT'
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
	 * @param WC_Product $wc_product The WooCommerce product.
	 */
	public static function to_dk_product_body(
		WC_Product $wc_product,
	): stdClass {
		if ( ! self::is_in_dk( $wc_product ) ) {
			return self::to_new_product_body( $wc_product );
		}

		return self::to_updated_product_body( $wc_product );
	}

	/**
	 * Generate the JSON body used for POSTing a new product into DK
	 *
	 * This is only used when it is known that the product does not exsist in DK
	 * already. It sends all relevant product information upstream, including
	 * the SKU and sets a price in the shop's currency.
	 *
	 * @param WC_Product $wc_product The WooCommerce product.
	 *
	 * @return stdClass A standard PHP object that can be cast into a JSON string.
	 */
	public static function to_new_product_body(
		WC_Product $wc_product
	): stdClass {
		$product_props = array(
			'ItemCode'               => $wc_product->get_sku(),
			'Description'            => $wc_product->get_title(),
			'CurrencyCode'           => get_woocommerce_currency(),
			'AllowNegativeInventiry' => true,
			'AllowDiscount'          => true,
			'ShowItemInWebShop'      => true,
			'NetWeight'              => $wc_product->get_weight(),
			'TaxPercent'             => ProductHelper::tax_rate( $wc_product ),
			'PropositionPrice'       => ProductHelper::format_sale_price_for_dk( $wc_product ),
			'PropositionDateFrom'    => ProductHelper::format_date_on_sale_for_dk( 'from', $wc_product ),
			'PropositionDateTo'      => ProductHelper::format_date_on_sale_for_dk( 'to', $wc_product ),
		);

		if ( 'reduced-rate' === $wc_product->get_tax_class( 'edit' ) ) {
			$product_props['SalesLedgerCode'] = Config::get_ledger_code( 'reduced' );
		} else {
			$product_props['SalesLedgerCode'] = Config::get_ledger_code( 'standard' );
		}

		if ( true === wc_prices_include_tax() ) {
			$product_props['UnitPrice1WithTax'] = $wc_product->get_regular_price();
		} else {
			$product_props['UnitPrice1'] = $wc_product->get_regular_price();
		}

		if ( 'publish' === $wc_product->get_status() ) {
			$product_props['ShowItemInWebShop'] = true;
		} else {
			$product_props['ShowItemInWebShop'] = false;
		}

		if ( $wc_product instanceof WC_Product_Variation ) {
			$product_props['Description2'] = $wc_product->get_attribute_summary();
		}

		return (object) $product_props;
	}

	/**
	 * Generate the JSON body for PUTing updated information about a WooCommerce
	 * product into the DK API
	 *
	 * Filters out the attributes that are not to be included on update for each
	 * product. This may return an empty object if nothing is to be updated.
	 *
	 * @param WC_Product $wc_product The WooCommerce product.
	 *
	 * @return stdClass A standard PHP object that can be cast into a JSON string.
	 */
	public static function to_updated_product_body(
		WC_Product $wc_product
	): stdClass {
		$product_props = array();

		if ( ProductHelper::name_sync_enabled( $wc_product ) ) {
			$name_props = array(
				'Description' => $wc_product->get_title(),
			);

			if ( $wc_product instanceof WC_Product_Variation ) {
				$name_props['Description2'] = $wc_product->get_attribute_summary();
			}

			$product_props = array_merge( $product_props, $name_props );
		}

		$product_dk_currency = $wc_product->get_meta(
			'1984_woo_dk_dk_currency',
			true,
			'edit'
		);

		if (
			( get_woocommerce_currency() === $product_dk_currency ) &&
			ProductHelper::price_sync_enabled( $wc_product )
		) {
			$price_props = array(
				'CurrencyCode'        => get_woocommerce_currency(),
				'TaxPercent'          => ProductHelper::tax_rate( $wc_product ),
				'PropositionPrice'    => ProductHelper::format_sale_price_for_dk( $wc_product ),
				'PropositionDateFrom' => ProductHelper::format_date_on_sale_for_dk( 'from', $wc_product ),
				'PropositionDateTo'   => ProductHelper::format_date_on_sale_for_dk( 'to', $wc_product ),
			);

			if ( true === wc_prices_include_tax() ) {
				$price_props['UnitPrice1WithTax'] = $wc_product->get_regular_price();
			} else {
				$price_props['UnitPrice1'] = $wc_product->get_regular_price();
			}

			$product_props = array_merge( $product_props, $price_props );
		}

		if ( ProductHelper::quantity_sync_enabled( $wc_product ) ) {
			$inventory_props = array(
				'NetWeight' => $wc_product->get_weight(),
			);

			$product_props = array_merge( $product_props, $inventory_props );
		}

		return (object) $product_props;
	}
}
