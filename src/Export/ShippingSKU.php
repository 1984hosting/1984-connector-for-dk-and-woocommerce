<?php

declare(strict_types = 1);

namespace NineteenEightyFour\NineteenEightyWoo\Export;

use NineteenEightyFour\NineteenEightyWoo\Service\DKApiRequest;
use NineteenEightyFour\NineteenEightyWoo\Config;

/**
 * The Shipping SKU class
 *
 * Provides functions for creating a product in DK representing a specific
 * service item product used for shipping costs and fees.
 */
class ShippingSKU {
	/**
	 * Check if shipping SKU is in DK
	 *
	 * Checks the DK API if a product with a shipping SKU has been created and
	 * saves that information in the options table.
	 *
	 * @param string $sku The SKU.
	 *
	 * @see NineteenEightyFour\NineteenEightyWoo\Config::set_shipping_sku_is_in_dk()
	 */
	public static function is_in_dk( string $sku ): bool {
		$api_request = new DKApiRequest();

		$result = $api_request->get_result(
			'/Product/' . $sku
		);

		if ( 200 !== $result->response_code ) {
			return false;
		}

		Config::set_shipping_sku_is_in_dk();

		return true;
	}

	/**
	 * Create the shipping product in DK
	 *
	 * This creates a Service Item product in DK used for shipping that has a
	 * specific SKU.
	 *
	 * @param string $sku The SKU.
	 */
	public static function create_in_dk( string $sku ): bool {
		$api_request = new DKApiRequest();

		$result = $api_request->request_result(
			'/Product/',
			wp_json_encode( self::post_body_for_dk( $sku ) ),
		);

		if ( 200 !== $result->response_code ) {
			return false;
		}

		Config::set_shipping_sku_is_in_dk();

		return true;
	}

	/**
	 * Get POST body for creating the shipping product
	 *
	 * Creates an array representing a valid HTTP POST body for creating the
	 * Shipping SKU.
	 *
	 * @param string $sku The SKU.
	 */
	public static function post_body_for_dk( string $sku ): array {
		return array(
			'ItemCode'          => $sku,
			'ShowItemInWebShop' => false,
			'Description'       => __( 'Shipping' ),
			'ItemClass'         => 'ServiceItem',
		);
	}
}
