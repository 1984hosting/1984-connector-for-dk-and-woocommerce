<?php

declare(strict_types = 1);

namespace NineteenEightyFour\NineteenEightyWoo\Export;

use NineteenEightyFour\NineteenEightyWoo\Service\DKApiRequest;
use NineteenEightyFour\NineteenEightyWoo\Config;

/**
 * The Service SKU class
 */
class ServiceSKU {
	/**
	 * Check if shipping SKU is in DK
	 *
	 * Checks the DK API if a product with a shipping SKU has been created and
	 * saves that information in the options table.
	 *
	 * @param string $sku The SKU.
	 */
	public static function is_in_dk( string $sku ): bool {
		$api_request = new DKApiRequest();

		$result = $api_request->get_result(
			'/Product/' . $sku
		);

		if ( 200 !== $result->response_code ) {
			return false;
		}

		return true;
	}

	/**
	 * Create the shipping product in DK
	 *
	 * This creates a Service Item product in DK used for shipping that has a
	 * specific SKU.
	 *
	 * @param string $sku The SKU.
	 * @param string $type The type of service. Can be `shipping`, `cost` and `coupon`.
	 */
	public static function create_in_dk(
		string $sku,
		string $type = 'shipping'
	): bool {
		$api_request = new DKApiRequest();
		$api_body    = self::post_body_for_dk( $sku, $type );

		if ( false === $api_body ) {
			return false;
		}

		$result = $api_request->request_result(
			'/Product/',
			wp_json_encode( $api_body ),
		);

		if ( 200 !== $result->response_code ) {
			return false;
		}

		return true;
	}

	/**
	 * Get POST body for creating the shipping product
	 *
	 * Creates an array representing a valid HTTP POST body for creating the
	 * Shipping SKU.
	 *
	 * @param string $sku The SKU.
	 * @param string $type The type of service. Can be `shipping`, `cost` and `coupon`.
	 */
	public static function post_body_for_dk(
		string $sku,
		string $type = 'shipping'
	): array {
		switch ( $type ) {
			case 'shipping':
				$description = __( 'Shipping', 'NineteenEightyWoo' );
				break;
			case 'cost':
				$description = __( 'Cost', 'NineteenEightyWoo' );
				break;
			case 'coupon':
				$description = __( 'Coupon', 'NineteenEightyWoo' );
				break;
			default:
				return false;
		}

		return array(
			'ItemCode'          => $sku,
			'ShowItemInWebShop' => false,
			'Description'       => $description,
			'ItemClass'         => 'ServiceItem',
		);
	}
}