<?php

declare(strict_types = 1);

namespace NineteenEightyFour\NineteenEightyWoo\Export;

use NineteenEightyFour\NineteenEightyWoo\Service\DKApiRequest;
use WP_Error;
use stdClass;

/**
 * The Employee Export class
 */
class Employee {
	const API_PATH                = '/General/Employee/';
	const DEFAULT_EMPLOYEE_NUMBER = 'WEBSALES';

	/**
	 * Create an employee record in DK
	 *
	 * @param string $employee_number The employee number.
	 * @param string $employee_name The employee name.
	 */
	public static function create_in_dk(
		string $employee_number = self::DEFAULT_EMPLOYEE_NUMBER,
		string $employee_name = 'Online Store'
	): bool|WP_Error {
		$api_request  = new DKApiRequest();
		$request_body = self::to_dk_employee_body(
			$employee_number,
			$employee_name
		);

		$result = $api_request->request_result(
			self::API_PATH,
			wp_json_encode( $request_body )
		);

		if ( $result instanceof WP_Error ) {
			return $result;
		}

		if ( $result->response_code !== 200 ) {
			return false;
		}

		return true;
	}

	/**
	 * Get an employee record from DK
	 *
	 * @param string $employee_number The employee number.
	 */
	public static function get_from_dk(
		string $employee_number = self::DEFAULT_EMPLOYEE_NUMBER
	): stdClass|bool|WP_Error {
		$api_request = new DKApiRequest();

		$result = $api_request->get_result(
			self::API_PATH . $employee_number
		);

		if ( $result instanceof WP_Error ) {
			return $result;
		}

		if ( $result->response_code !== 200 ) {
			return false;
		}

		return $result->data;
	}

	/**
	 * Check if an employee is in DK
	 *
	 * @param string $employee_number The employee number.
	 */
	public static function is_in_dk(
		string $employee_number = self::DEFAULT_EMPLOYEE_NUMBER
	): bool|WP_Error {
		$dk_record = self::get_from_dk( $employee_number );

		if ( $dk_record instanceof stdClass ) {
			return true;
		}

		return $dk_record;
	}

	/**
	 * Prepare an employee POST request body
	 *
	 * @param string $employee_number The employee number.
	 * @param string $employee_name The employee name.
	 */
	public static function to_dk_employee_body(
		string $employee_number = 'WEBSALES',
		string $employee_name = 'Online Store'
	): array {
		return array(
			'Number' => $employee_number,
			'Name'   => $employee_name,
		);
	}
}
