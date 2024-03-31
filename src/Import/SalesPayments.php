<?php

declare(strict_types = 1);

namespace NineteenEightyFour\NineteenEightyWoo\Import;

use NineteenEightyFour\NineteenEightyWoo\Service\DKApiRequest;
use stdClass;
use WP_Error;

/**
 * The SalesPayments class
 *
 * Provides funtions for handling downstream payment methods from DK
 */
class SalesPayments {
	const TRANSIENT_EXPIRY = 600;

	/**
	 * Get payment methods
	 *
	 * Uses a transient to cache the results from the DK API for 24 hours.
	 */
	public static function get_methods(): array {
		$methods_transient = get_transient( '1984_woo_dk_payment_methods' );

		if ( false === $methods_transient ) {
			$methods_from_dk = self::get_methods_from_dk();

			if ( true === is_array( $methods_from_dk ) ) {
				self::save_methods( $methods_from_dk );
				return $methods_from_dk;
			}

			return [];
		}

		return $methods_transient;
	}

	/**
	 * Get payment methods from the DK API, bypassing the transient cache
	 */
	public static function get_methods_from_dk(): array|false {
		$request = new DKApiRequest();
		$result  = $request->get_result(
			'/Sales/Payment/Type?include=PaymentId%2CName%2CActive'
		);

		if ( $result instanceof WP_Error ) {
			return $result;
		}

		if ( 200 !== $result->response_code ) {
			return false;
		}

		return self::convert_json_response( $result->data );
	}

	/**
	 * Find a payment method by ID
	 *
	 * Finds a payment method by its value in DK.
	 *
	 * @param int $id The DK payment method ID.
	 */
	public static function find_by_id( int $id ): stdClass|false {
		foreach ( self::get_methods() as $method ) {
			if ( $id === $method->dk_id ) {
				return $method;
			}
		}

		return false;
	}

	/**
	 * Clean and order the JSON response from the DK API
	 *
	 * @param array $json_response The already-decoded JSON data as it comes
	 *                             from the API.
	 */
	public static function convert_json_response(
		array $json_response
	): array {
		$methods = array();

		foreach ( $json_response as $method ) {
			$methods[] = (object) array(
				'dk_id'     => $method->PaymentId,
				'dk_name'   => $method->Name,
				'dk_active' => $method->Active,
			);
		}

		usort(
			$methods,
			function ( $a, $b ) {
				if ( $a > $b ) {
					return 1;
				}
				return -1;
			}
		);

		return $methods;
	}

	/**
	 * Save the payment methods from DK as 24-hour transient value
	 *
	 * @param array $methods The array of methods as they come from the DK API.
	 */
	public static function save_methods( array $methods ): bool {
		return set_transient(
			'1984_woo_dk_payment_methods',
			$methods,
			self::TRANSIENT_EXPIRY
		);
	}
}
