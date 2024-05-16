<?php

declare(strict_types = 1);

namespace NineteenEightyFour\NineteenEightyWoo\Export;

use NineteenEightyFour\NineteenEightyWoo\Service\DKApiRequest;
use NineteenEightyFour\NineteenEightyWoo\Config;
use WC_Product;
use WP_Error;
use WC_Tax;
use WC_Product_Variation;
use NineteenEightyFour\NineteenEightyWoo\Brick\Math\BigDecimal;
use NineteenEightyFour\NineteenEightyWoo\Brick\Math\RoundingMode;

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
	 * Sets the `Inactive` paramter for the DK product record in DK to false.
	 *
	 * @param WC_Product $product The WooCommerce product.
	 *
	 * @return bool|WP_Error True on success, false on error from the DK API,
	 *                       WP_Error on connection error.
	 */
	public static function activate_in_dk(
		WC_Product $product
	): bool|WP_Error {
		if ( false === (bool) $product->get_sku() ) {
			return false;
		}

		$api_request  = new DKApiRequest();
		$request_body = array( 'Inactive' => false );

		$result = $api_request->request_result(
			self::API_PATH,
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
	 * Deactivate a product in DK
	 *
	 * Sets the `Inactive` paramter for the DK product record in DK to true.
	 *
	 * @param WC_Product $product The WooCommerce product.
	 *
	 * @return bool|WP_Error True on success, false on error from the DK API,
	 *                       WP_Error on connection error.
	 */
	public static function deactivate_in_dk(
		WC_Product $product
	): bool|WP_Error {
		if ( false === (bool) $product->get_sku() ) {
			return false;
		}

		$api_request  = new DKApiRequest();
		$request_body = array( 'Inactive' => true );

		$result = $api_request->request_result(
			self::API_PATH,
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
		$request_body = self::to_dk_product_body( $product, true );

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
		$request_body = self::to_dk_product_body( $product, false );

		unset( $request_body['SalesLedgerCode'] );

		$item_code = $product->get_sku();

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
		$api_request  = new DKApiRequest();
		$request_body = self::to_dk_product_body( $wc_product, false );

		unset( $request_body['SalesLedgerCode'] );
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
	 * @param WC_Product $product The WooCommerce product.
	 * @param bool       $new_product Wether the product is new.
	 *                                False if updating only.
	 */
	public static function to_dk_product_body(
		WC_Product $product,
		bool $new_product = true
	): array {
		$product_props = array(
			'Description'  => $product->get_title(),
			'CurrencyCode' => get_woocommerce_currency(),
		);

		if ( true === $new_product ) {
			$product_props['ItemCode'] = $product->get_sku();

			// I'm not kidding. DK left this typo in their API!
			// The docs use `AllowNegativeInventory` but this is the key that
			// actually works.
			$product_props['AllowNegativeInventiry'] = true;

			$product_props['AllowDiscount'] = true;

			if ( 'reduced-rate' === $product->get_tax_class( 'edit' ) ) {
				$product_props['SalesLedgerCode'] = Config::get_ledger_code(
					'reduced'
				);
			} else {
				$product_props['SalesLedgerCode'] = Config::get_ledger_code(
					'standard'
				);
			}
		}

		if ( $product instanceof WC_Product_Variation ) {
			$product_props['Description2'] = $product->get_attribute_summary();
		}

		$product_props['ShowItemInWebShop'] = true;

		if ( 'publish' === $product->get_status() ) {
			$product_props['Inactive'] = false;
		} else {
			$product_props['Inactive'] = true;
		}

		$taxes = new WC_Tax();
		if ( true === is_null( WC()->countries ) ) {
			$tax_rate_p = 0;
		} else {
			$tax_class  = $product->get_tax_class();
			$tax_rate_a = $taxes->get_rates( $tax_class );
			$tax_rate_p = array_pop( $tax_rate_a )['rate'];
		}

		$product_props['TaxPercent'] = $tax_rate_p;

		$product_props['NetWeight'] = $product->get_weight( 'edit' );

		if ( true === (bool) $product->get_meta(
			'1984_woo_dk_price_sync',
			true,
			'edit'
		) ) {
			if ( true === wc_prices_include_tax() ) {
				$product_props['UnitPrice1WithTax'] = $product->get_regular_price();

				if ( false === empty( $product->get_sale_price() ) ) {
					$sale_price_after_tax = BigDecimal::of(
						$product->get_sale_price(),
					);

					$sale_tax_percentage = BigDecimal::of( $tax_rate_p );

					$sale_tax_fraction = $sale_tax_percentage->dividedBy(
						100,
						4,
						roundingMode: RoundingMode::HALF_UP
					);

					$sale_price_before_tax = $sale_price_after_tax->dividedBy(
						$sale_tax_fraction->plus( 1 )
					);

					$product_props['PropositionPrice'] = (
						$sale_price_before_tax->toFloat()
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

			if ( false === empty( $product->get_date_on_sale_from() ) ) {
				$sale_from = $product->get_date_on_sale_from();

				$product_props['PropositionDateFrom'] = $sale_from->format( 'c' );
			} else {
				$product_props['PropositionDateFrom'] = '';
			}

			if ( false === empty( $product->get_date_on_sale_to() ) ) {
				$sale_to = $product->get_date_on_sale_to();

				$product_props['PropositionDateTo'] = $sale_to->format( 'c' );
			} else {
				$product_props['PropositionDateTo'] = '';
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
