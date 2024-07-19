<?php

declare(strict_types = 1);

namespace NineteenEightyFour\NineteenEightyWoo\Import;

use NineteenEightyFour\NineteenEightyWoo\Service\DKApiRequest;
use NineteenEightyFour\NineteenEightyWoo\Currency;
use WP_Error;

/**
 * The Currenty Import class
 *
 * Handles fetching currency rates
 */
class Currencies {
	const API_PATH = '/General/Currency/';

	/**
	 * Save all currency rates from DK
	 *
	 * Fetches all the currency rates from DK
	 */
	public static function save_all_from_dk(): void {
		if ( defined( '1984_DK_WOO_DOING_SYNC' ) ) {
			return;
		}

		define( '1984_DK_WOO_DOING_SYNC', true );

		$json_objects = self::get_all_from_dk();

		foreach ( $json_objects as $c ) {
			Currency::set_rate( $c->Number, $c->Rate );
		}
	}

	/**
	 * Get all currency rates from the DK API
	 */
	public static function get_all_from_dk(): false|WP_Error|array {
		$api_request = new DKApiRequest();
		$result      = $api_request->get_result( self::API_PATH );

		if ( $result instanceof WP_Error ) {
			return $result;
		}

		if ( $result->response_code !== 200 ) {
			return false;
		}

		return (array) $result->data;
	}
}
