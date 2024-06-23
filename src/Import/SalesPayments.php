<?php

declare(strict_types = 1);

namespace NineteenEightyFour\NineteenEightyWoo\Import;

use NineteenEightyFour\NineteenEightyWoo\Service\DKApiRequest;
use NineteenEightyFour\NineteenEightyWoo\Config;
use stdClass;
use WP_Error;

/**
 * The SalesPayments class
 *
 * Provides funtions for handling downstream payment methods from DK
 */
class SalesPayments {
	const TRANSIENT_EXPIRY = 600;

	const DK_PAYMENT_MODES = array(
		'ABG',
		'BM',
		'CG',
		'GKR',
		'GM',
		'IB',
		'STGR',
		'TGR',
	);

	const DK_PAYMENT_TERMS = array(
		'D15',
		'D20',
		'D30',
		'LM',
		'M15',
		'M20',
		'POST',
		'STGR',
	);

	/**
	 * Get payment terms from the DK API
	 */
	public static function get_payment_terms_from_dk(): array|WP_Error|false {
		$request = new DKApiRequest();
		$result  = $request->get_result(
			'/general/table/ARPTERM.DAT/records?legacy=true&fields=CODE,DESCRIPTION'
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
	 * Get the name of a payment term from key
	 *
	 * @param string $key The payment term key from DK_PAYMENT_TERMS.
	 *
	 * @return string A "translated" name for the key, or just the key itself if
	 *                it is not defined in DK_PAYMENT_TERMS.
	 */
	public static function get_payment_term_name( string $key ): string {
		switch ( $key ) {
			case 'D15':
				return __( '15 Day Payment Deadline (D15)', '1984-dk-woo' );
			case 'D20':
				return __( '30 Day Payment Deadline (D20)', '1984-dk-woo' );
			case 'D30':
				return __( '30 Day Payment Deadline (D30)', '1984-dk-woo' );
			case 'LM':
				return __( 'Current Month (LM)', '1984-dk-woo' );
			case 'M15':
				return __( 'Current Month + 15 Days (M15)', '1984-dk-woo' );
			case 'M20':
				return __( 'Current Month + 20 Days (M20)', '1984-dk-woo' );
			case 'POST':
				return __( 'Postal COD (POST)', '1984-dk-woo' );
			case 'STGR':
				return __( 'Cash Payment (STGR)', '1984-dk-woo' );
		}

		return $key;
	}

	/**
	 * Get payment term codes
	 *
	 * If the API key has been set, returns the payment term codes from the
	 * relevant database table in DK.
	 *
	 * If the API key has not been set, or if there is a connection error, the
	 * contents of DK_PAYMENT_TERMS will be returned.
	 *
	 * @return array<string>
	 */
	public static function get_payment_terms(): array {
		if ( ! empty( Config::get_dk_api_key() ) ) {
			$terms = self::get_payment_terms_from_dk();
			if ( is_array( $terms ) ) {
				return array_column( $terms, 'CODE' );
			}
		}

		return self::DK_PAYMENT_TERMS;
	}

	/**
	 * Get payment modes form the DK API
	 *
	 * @return array<string>
	 */
	public static function get_payment_modes_from_dk(): array {
		$request = new DKApiRequest();
		$result  = $request->get_result(
			'/general/table/ARPMODE.DAT/records?legacy=true&fields=CODE,DESCRIPTION'
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
	 * Get the name of a payment mode from key
	 *
	 * @param string $key The payment mode key from DK_PAYMENT_MODES.
	 *
	 * @return string The full name of the payment mode. If the key is not
	 *                defined in DK_PAYMENT_MODES, only the key itself is
	 *                returned.
	 */
	public static function get_payment_mode_name( string $key ): string {
		switch ( $key ) {
			case 'ABG':
				return __( 'A or B Giro Request (ABG)', '1984-dk-woo' );
			case 'BM':
				return __( 'Bank Transfer (BM)', '1984-dk-woo' );
			case 'CG':
				return __( 'C Giro Request (CG)', '1984-dk-woo' );
			case 'GKR':
				return __( 'Card Payment (GKR)', '1984-dk-woo' );
			case 'GM':
				return __( 'Giro Transfer (GM)', '1984-dk-woo' );
			case 'IB':
				return __( 'Bank Collection Service (IB)', '1984-dk-woo' );
			case 'STGR':
				return __( 'Cash Payment (STGR)', '1984-dk-woo' );
			case 'TGR':
				return __( 'Cheque Payment (TGR)', '1984-dk-woo' );
		}

		return $key;
	}

	/**
	 * Get payment modes
	 *
	 * If the API key has been set, returns the payment mode codes from the
	 * relevant database table in DK.
	 *
	 * If the API has not been set, or if there is a connection error, the
	 * contents of DK_PAYMENT_MODES will be returned.
	 */
	public static function get_payment_modes(): array {
		if ( ! empty( Config::get_dk_api_key() ) ) {
			$modes = self::get_payment_modes_from_dk();
			return array_column( $modes, 'CODE' );
		}

		return self::DK_PAYMENT_MODES;
	}

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
	public static function get_methods_from_dk(): array|WP_Error|false {
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

		return self::convert_json_response_for_methods( $result->data );
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
	public static function convert_json_response_for_methods(
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
