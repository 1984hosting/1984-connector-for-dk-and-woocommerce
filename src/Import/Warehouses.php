<?php

declare(strict_types = 1);

namespace NineteenEightyFour\NineteenEightyWoo\Import;

use NineteenEightyFour\NineteenEightyWoo\Service\DKApiRequest;
use WP_Error;

/**
 * The Warehoses Import class
 *
 * This imports and handles Warehoses from the DK api.
 */
class Warehouses {
	const API_PATH         = '/productwarehouse/';
	const TRANSIENT_EXPIRY = 600;

	/**
	 * Get warehouses
	 *
	 * Those results are cached for 10 minutes.
	 */
	public static function get(): array {
		$transient = get_transient( '1984_woo_dk_warehouses' );

		if ( false === $transient ) {
			$warehouses_from_dk = self::get_from_dk();

			if ( true === is_array( $warehouses_from_dk ) ) {
				self::save( $warehouses_from_dk );
				return $warehouses_from_dk;
			}

			return [];
		}

		return $transient;
	}

	/**
	 * Get warehouses directly from DK
	 */
	public static function get_from_dk(): array {
		$request = new DKApiRequest();
		$result  = $request->get_result( self::API_PATH );

		if ( $result instanceof WP_Error ) {
			return $result;
		}

		if ( 200 !== $result->response_code ) {
			return false;
		}

		return $result->data;
	}

	/**
	 * Save the results as a transient
	 *
	 * @param array $results The results from the DK endpoint.
	 */
	public static function save( array $results ): bool {
		return set_transient(
			'1984_woo_dk_warehouses',
			$results,
			self::TRANSIENT_EXPIRY
		);
	}
}
