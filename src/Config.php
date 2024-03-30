<?php

declare(strict_types = 1);

namespace NineteenEightyFour\NineteenEightyWoo;

use NineteenEightyFour\NineteenEightyWoo\Export\ServiceSKU;
use NineteenEightyFour\NineteenEightyWoo\Import\SalesPayments;
use NineteenEightyFour\NineteenEightyWoo\Hooks\KennitalaField;
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
	const DEFAULT_COUPON_SKU   = 'COUPON';
	const DEFAULT_COST_SKU     = 'COST';

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
	 *
	 * @return bool True if the mapping is saved in the wp_options table, false if not.
	 */
	public static function set_payment_mapping(
		string $woo_id,
		int $dk_id,
	): bool {
		$dk_payment_method = SalesPayments::find_by_id( $dk_id );

		if ( false === $dk_payment_method ) {
			return false;
		}

		return update_option(
			'1984_woo_dk_payment_method_' . $woo_id,
			(object) array(
				'woo_id'  => $woo_id,
				'dk_id'   => $dk_payment_method->dk_id,
				'dk_name' => $dk_payment_method->dk_name,
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
	 * Check if a Woo Payment Gateway ID and DK Payment method ID match
	 *
	 * @param string $woo_id The WooCommerce gateway ID.
	 * @param int    $dk_id The DK payment ID.
	 */
	public static function payment_mapping_matches(
		string $woo_id,
		int $dk_id
	): bool {
		$payment_mapping = self::get_payment_mapping(
			$woo_id,
			true
		);

		if ( $payment_mapping->dk_id === $dk_id ) {
			return true;
		}

		return false;
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
			( false === ServiceSKU::is_in_dk( $sku ) ) &&
			( true === ServiceSKU::create_in_dk( $sku ) )
		) {
			return update_option( '1984_woo_dk_shipping_sku', $sku );
		}

		return false;
	}

	public static function get_coupon_sku(): string {
		return (string) get_option(
			'1984_woo_dk_coupon_sku',
			self::DEFAULT_COUPON_SKU
		);
	}

	public static function set_coupon_sku( string $sku ): bool {
		if (
			( false === ServiceSKU::is_in_dk( $sku ) ) &&
			( true === ServiceSKU::create_in_dk( $sku, 'coupon' ) )
		) {
			return update_option( '1984_woo_dk_coupon_sku', $sku );
		}

		return false;
	}

	public static function get_cost_sku(): string {
		return (string) get_option(
			'1984_woo_dk_cost_sku',
			self::DEFAULT_COST_SKU
		);
	}

	public static function set_cost_sku( string $sku ): bool {
		if (
			( false === ServiceSKU::is_in_dk( $sku ) ) &&
			( true === ServiceSKU::create_in_dk( $sku, 'cost' ) )
		) {
			return update_option( '1984_woo_dk_cost_sku', $sku );
		}

		return false;
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

	/**
	 * Get the default kennitala
	 *
	 * This is the kennitala used for "other" customers that do not have a
	 * kennitala. (Yes, DK is silly like this.)
	 */
	public static function get_default_kennitala(): string {
		return (string) get_option(
			'1984_woo_dk_default_kennitala',
			'9999999999'
		);
	}

	/**
	 * Set the default kennitala
	 *
	 * @param string $kennitala The kennitala (may be unsanitized).
	 */
	public static function set_default_kennitala( string $kennitala ): bool {
		return update_option(
			'1984_woo_dk_default_kennitala',
			KennitalaField::sanitize_kennitala( $kennitala )
		);
	}

	/**
	 * Get wether the kennitala text input field is to be rendered in the
	 * classic, shortcode based checkout page
	 */
	public static function get_kennitala_classic_field_enabled(): bool {
		return (bool) get_option(
			'1984_woo_dk_kennitala_classic_field_enabled',
			true
		);
	}

	/**
	 * Set wether the kennitala text input field is to be redered in the
	 * classic, shortcode based checkout page
	 *
	 * @param bool $enabled True to enable, false to disable.
	 */
	public static function set_kennitala_classic_field_enabled(
		bool $enabled
	): bool {
		return update_option(
			'1984_woo_dk_kennitala_classic_field_enabled',
			$enabled
		);
	}

	/**
	 * Get wether the kennitala input field is to be rendered in the block based
	 * checkout page
	 */
	public static function get_kennitala_block_field_enabled(): bool {
		return (bool) get_option(
			'1984_woo_dk_kennitala_block_field_enabled',
			false
		);
	}

	/**
	 * Set wether the kennitala input field is to be rendered in the block based
	 * checkout page
	 *
	 * @param bool $enabled True to enable, false to disable.
	 */
	public static function set_kennitala_block_field_enabled(
		bool $enabled
	): bool {
		return update_option(
			'1984_woo_dk_kennitala_block_field_enabled',
			$enabled
		);
	}
}
