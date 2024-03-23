<?php

declare(strict_types = 1);

namespace NineteenEightyFour\NineteenEightyWoo;

use stdClass;

/**
 * The Config class
 *
 * This class is for handling configuration values and options.
 **/
class Config {
	const DK_API_KEY_REGEX = '^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$';

	/**
	 * Get the DK API key
	 *
	 * The order of priority when determening the API key value is:
	 *
	 * 1. The DK_API_KEY constant (defined in wp-config.php)
	 * 2. The DK_API_KEY environment variable
	 * 3. The 1984_woo_dk_api_key WP option
	 */
	public static function get_dk_api_key(): string|false {
		if ( true === defined( 'DK_API_KEY' ) ) {
			return constant( 'DK_API_KEY' );
		}

		if ( false !== getenv( 'DK_API_KEY' ) ) {
			return getenv( 'DK_API_KEY' );
		}

		return get_option( '1984_woo_dk_api_key' );
	}

	/**
	 * Set the DK API key option
	 *
	 * Note that this will not override the constant or environment variable
	 * value.
	 *
	 * @param string $value The API key value.
	 */
	public static function set_dk_api_key( string $value ): bool {
		if ( 0 === preg_match( '/' . self::DK_API_KEY_REGEX . '/', $value ) ) {
			return false;
		}
		return update_option( '1984_woo_dk_api_key', $value );
	}

	/**
	 * Map a WooCommerce payment gateway to a DK payment method
	 *
	 * @param string $woo_id The alphanumeric WooCommerce payment ID.
	 * @param int    $dk_id The payment method ID in DK.
	 * @param string $dk_name The payment method name in DK.
	 *
	 * @return bool True if the mapping is saved in the wp_options table, false if not.
	 */
	public static function set_payment_mapping(
		string $woo_id,
		int $dk_id,
		string $dk_name
	): bool {
		return update_option(
			'1984_woo_dk_payment_method_' . $woo_id,
			(object) array(
				'woo_id'  => $woo_id,
				'dk_id'   => $dk_id,
				'dk_name' => $dk_name,
			)
		);
	}

	/**
	 * Get a payment mapping from a WooCommerce payment gateway ID
	 *
	 * @param string $woo_id The WooCommerce payment gateway ID.
	 * @param bool   $empty_object Populates a default value as an object with
	 *                             empty properties. If false, it will return
	 *                             false if no mapping is found.
	 *
	 * @return stdClass An object containing woo_id, dk_id and dk_name properties.
	 */
	public static function get_payment_mapping( string $woo_id, bool $empty_object = true ): stdClass {
		if ( true === $empty_object ) {
			$default = (object) array(
				'woo_id'  => '',
				'dk_id'   => '',
				'dk_name' => '',
			);
		} else {
			$default = false;
		}

		return get_option(
			'1984_woo_dk_payment_method_' . $woo_id,
			$default
		);
	}
}
