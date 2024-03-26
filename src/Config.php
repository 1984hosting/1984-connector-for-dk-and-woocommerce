<?php

declare(strict_types = 1);

namespace NineteenEightyFour\NineteenEightyWoo;

use NineteenEightyFour\NineteenEightyWoo\Export\ShippingSKU;
use stdClass;

/**
 * The Config class
 *
 * This class is for handling configuration values and options.
 **/
class Config {
	const DK_API_KEY_REGEX = '^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$';

	const DEFAULT_CUSTOMER_NUMBER_PREFIX = 'WCC';
	const DEFAULT_PRODUCT_NUMBER_PREFIX  = 'WCP';
	const DEFAULT_INVOICE_NUMBER_PREFIX  = 'WCI';

	const DEFAULT_SHIPPING_SKU = 'SHIPPING';

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
	public static function get_payment_mapping(
		string $woo_id,
		bool $empty_object = true
	): stdClass {
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

	/**
	 * Get the prefix used for customer records from WooCommerce in DK
	 *
	 * If a customer's ID in WC/WP is `5885522`, their "customer number" in DK
	 * becomes `WCC5885522` if the prefix is set to `WCC`.
	 */
	public static function get_customer_number_prefix(): string {
		return get_option(
			'1984_woo_dk_customer_number_prefix',
			self::DEFAULT_CUSTOMER_NUMBER_PREFIX
		);
	}

	/**
	 * Set the prefix used for customer records from WooCommerce in DK
	 *
	 * @param string $prefix The prefix.
	 */
	public static function set_customer_number_prefix( string $prefix ): bool {
		return update_option( '1984_woo_dk_customer_number_prefix', $prefix );
	}

	/**
	 * Get the prefix used for order records from WooCommerce in DK
	 *
	 * If an order's ID in WooCommerce is `602214076`, then it becomes
	 * `WCO602214076` once it has made it to DK if the prefix is set to `WCO`.
	 */
	public static function get_invoice_number_prefix(): string {
		return get_option(
			'1984_woo_dk_invoice_number_prefix',
			self::DEFAULT_INVOICE_NUMBER_PREFIX
		);
	}

	/**
	 * Set the prefix used for order records from WooCommerce in DK
	 *
	 * @param string $prefix The prefix.
	 */
	public static function set_invoice_number_prefix( string $prefix ): bool {
		return update_option( '1984_woo_dk_order_number_prefix', $prefix );
	}

	/**
	 * Get the shipping SKU
	 */
	public static function get_shipping_sku(): string {
		return (string) get_option(
			'1984_woo_dk_shipping_sku',
			self::DEFAULT_SHIPPING_SKU
		);
	}

	/**
	 * Set the value of the shipping SKU
	 *
	 * @param string $sku The SKU.
	 */
	public static function set_shipping_sku( string $sku ): bool {
		if (
			( false === self::get_shipping_sku_is_in_dk() ) &&
			( false === ShippingSKU::is_in_dk( $sku ) ) &&
			( true === ShippingSKU::create_in_dk( $sku ) )
		) {
			return update_option( '1984_woo_dk_shipping_sku', $sku );
		}

		return false;
	}

	/**
	 * Check if the shipping SKU has been set
	 *
	 * This is a lazy value that is set once the shipping SKU has been set, so
	 * that we aren't checking the DK API for it having been set.
	 */
	public static function get_shipping_sku_is_in_dk(): string {
		return (string) get_option( '1984_woo_dk_shipping_sku_is_in_dk', false );
	}

	/**
	 * Set wether the shipping SKU has been set or not
	 *
	 * This used when the shipping SKU has been set, so that we aren't checking
	 * the DK API for it all the time.
	 *
	 * @see NineteenEightyFour\NineteenEightyWoo\Export\ShippingSKU::create_in_dk()
	 */
	public static function set_shipping_sku_is_in_dk(): bool {
		return update_option( '1984_woo_dk_shipping_sku_is_in_dk', true );
	}
}
