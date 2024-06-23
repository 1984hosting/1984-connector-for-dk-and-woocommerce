<?php

declare(strict_types = 1);

namespace NineteenEightyFour\NineteenEightyWoo\Export;

use NineteenEightyFour\NineteenEightyWoo\Service\DKApiRequest;
use WP_Error;

class SalesPerson {
	const API_PATH = '/sales/person/';

	public static function is_in_dk(
		string $sales_person_number
	): bool|WP_Error {
		$api_request = new DKApiRequest();

		$result = $api_request->get_result(
			self::API_PATH . $sales_person_number
		);

		if ( $result instanceof WP_Error ) {
			return $result;
		}

		if ( 200 !== $result->response_code ) {
			return false;
		}

		return true;
	}
}
