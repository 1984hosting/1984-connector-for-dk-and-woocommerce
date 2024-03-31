<?php

declare(strict_types = 1);

namespace NineteenEightyFour\NineteenEightyWoo\Export;

use NineteenEightyFour\NineteenEightyWoo\Service\DKApiRequest;
use WP_Error;
use stdClass;

/**
 * The Sales Person Export class
 */
class SalesPerson {
	const API_PATH                    = '/Sales/Person/';
	const DEFAULT_SALES_PERSON_NUMBER = 'WEBSALES';

	/**
	 * Create a sales person record in DK
	 *
	 * @param string $sales_person_number The sales person number.
	 * @param string $employee_number The employee number.
	 */
	public static function create_in_dk(
		string $sales_person_number = self::DEFAULT_SALES_PERSON_NUMBER,
		string $employee_number = Employee::DEFAULT_EMPLOYEE_NUMBER
	): bool|WP_Error {
		$api_request  = new DKApiRequest();
		$request_body = self::to_dk_sales_person_body(
			$sales_person_number,
			$employee_number
		);

		$result = $api_request->request_result(
			self::API_PATH,
			wp_json_encode( $request_body )
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
	 * Get a sales person from DK
	 *
	 * @param string $sales_person_number The sales person number.
	 */
	public static function get_from_dk(
		string $sales_person_number = self::DEFAULT_SALES_PERSON_NUMBER
	): stdClass|bool|WP_Error {
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

		return $result->data;
	}

	/**
	 * Check if a sales person is in DK
	 *
	 * @param string $sales_person_number The sales person number.
	 */
	public static function is_in_dk(
		string $sales_person_number = self::DEFAULT_SALES_PERSON_NUMBER
	): bool|WP_Error {
		$dk_record = self::get_from_dk( $sales_person_number );

		if ( $dk_record instanceof stdClass ) {
			return true;
		}

		return $dk_record;
	}

	/**
	 * Prepare a POST request body
	 *
	 * @param string $sales_person_number The sales person number.
	 * @param string $employee_number The employee number.
	 */
	public static function to_dk_sales_person_body(
		string $sales_person_number = self::DEFAULT_SALES_PERSON_NUMBER,
		string $employee_number = Employee::DEFAULT_EMPLOYEE_NUMBER
	): array {
		return array(
			'Number'            => $sales_person_number,
			'Employee'          => $employee_number,
			'NameOnSalesOrders' => __( 'Online Store', 'NineteenEightyWoo' ),
			'PriceGroup'        => 0,
			'Price1Closed'      => false,
			'Price2Closed'      => false,
			'Price3Closed'      => false,
			'CanChangeDueDate'  => true,
			'FilterOnCustomer'  => false,
		);
	}
}
