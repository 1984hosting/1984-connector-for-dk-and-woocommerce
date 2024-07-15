<?php

declare(strict_types = 1);

namespace NineteenEightyFour\NineteenEightyWoo;

use NineteenEightyFour\NineteenEightyWoo\Import\SalesPayments as ImportSalesPayments;
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

	const DEFAULT_SHIPPING_SKU = 'shipping';
	const DEFAULT_COUPON_SKU   = 'coupon';
	const DEFAULT_COST_SKU     = 'cost';
	const DEFAULT_SALES_PERSON = 'websales';

	const DEFAULT_LEDGER_CODE_STANDARD_SALE     = 's002';
	const DEFAULT_LEDGER_CODE_STANDARD_PURCHASE = 'i001';
	const DEFAULT_LEDGER_CODE_REDUCED_SALE      = 's003';
	const DEFAULT_LEDGER_CODE_REDUCED_PURCHASE  = '';

	const DEFAULT_LEDGER_CODE_DOMESTIC_CUSTOMERS      = '0001';
	const DEFAULT_LEDGER_CODE_INTERNATIONAL_CUSTOMERS = '0002';

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
		if ( defined( 'DK_API_KEY' ) ) {
			return constant( 'DK_API_KEY' );
		}

		if ( getenv( 'DK_API_KEY' ) ) {
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
		if ( preg_match( '/' . self::DK_API_KEY_REGEX . '/', $value ) === 0 ) {
			return false;
		}
		return update_option( '1984_woo_dk_api_key', $value );
	}

	/**
	 * Map a WooCommerce payment gateway to a DK payment method
	 *
	 * @param string $woo_id The alphanumeric WooCommerce payment ID.
	 * @param int    $dk_id The payment method ID in DK.
	 * @param string $dk_mode The payment mode from DK.
	 * @param string $dk_term The payment term code from DK.
	 *
	 * @return bool True if the mapping is saved in the wp_options table, false if not.
	 */
	public static function set_payment_mapping(
		string $woo_id,
		int $dk_id,
		string $dk_mode,
		string $dk_term = '',
	): bool {
		$dk_payment_method = ImportSalesPayments::find_by_id( $dk_id );

		if ( ! $dk_payment_method ) {
			return false;
		}

		return update_option(
			'1984_woo_dk_payment_method_' . $woo_id,
			(object) array(
				'woo_id'  => $woo_id,
				'dk_id'   => $dk_payment_method->dk_id,
				'dk_name' => $dk_payment_method->dk_name,
				'dk_mode' => $dk_mode,
				'dk_term' => $dk_term,
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
		if ( $empty_object ) {
			$default = (object) array(
				'woo_id'  => '',
				'dk_id'   => '',
				'dk_name' => '',
				'dk_mode' => '',
				'dk_term' => '',
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
	 * Check if WooCommerce Payment Gateweay ID and DK Payment Mode match
	 *
	 * Even if we have payment methods per payment (and DK invoices can have
	 * multiple payments applied), an overall payment mode seems to be added.
	 *
	 * This seems to default on IB, which is the Icelandic bank payment
	 * processing and collection service.
	 *
	 * @param string $woo_id The WooCommrece gateway ID.
	 * @param string $dk_mode The DK payment mode.
	 */
	public static function payment_mode_matches(
		string $woo_id,
		string $dk_mode
	): bool {
		$payment_mapping = self::get_payment_mapping(
			$woo_id,
			true
		);

		if ( $payment_mapping->dk_mode === $dk_mode ) {
			return true;
		}

		return false;
	}

	/**
	 * Check if WooCommerce Payment Gateway ID and DK Payment Mode match
	 *
	 * @param string $woo_id The WooCommrece gateway ID.
	 * @param string $dk_term The DK payment term code.
	 */
	public static function payment_term_matches(
		string $woo_id,
		string $dk_term
	): bool {
		$payment_mapping = self::get_payment_mapping(
			$woo_id,
			true
		);

		if ( $payment_mapping->dk_term === $dk_term ) {
			return true;
		}

		return false;
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
		return update_option( '1984_woo_dk_shipping_sku', $sku );
	}

	/**
	 * Get the cost SKU
	 */
	public static function get_cost_sku(): string {
		return (string) get_option(
			'1984_woo_dk_cost_sku',
			self::DEFAULT_COST_SKU
		);
	}

	/**
	 * Set the cost SKU
	 *
	 * If the relevant service product does not exsist as a product in DK, a new
	 * one will be created.
	 *
	 * @param string $sku The cost SKU.
	 */
	public static function set_cost_sku( string $sku ): bool {
		return update_option( '1984_woo_dk_cost_sku', $sku );
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
			'0000000000'
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

	/**
	 * Get the default sales person number
	 */
	public static function get_default_sales_person_number(): string {
		return (string) get_option(
			'1984_woo_dk_default_sales_person_number',
			self::DEFAULT_SALES_PERSON
		);
	}

	/**
	 * Set the default sales person number
	 *
	 * @param string $sales_person_number The sales person number.
	 */
	public static function set_default_sales_person_number(
		string $sales_person_number
	): bool {
		return update_option(
			'1984_woo_dk_default_sales_person_number',
			$sales_person_number
		);
	}

	/**
	 * Get a ledger code by type
	 *
	 * Valid keys are `standard`, `reduced`, `shipping` and `costs`.
	 *
	 * @param string $key The ledger type. Defaults to `standard`.
	 *
	 * @return string The ledger code for the type.
	 */
	public static function get_ledger_code(
		string $key = 'standard'
	): string {
		switch ( $key ) {
			case 'standard':
				$default_value = self::DEFAULT_LEDGER_CODE_STANDARD_SALE;
				break;
			case 'standard_purchase':
				$default_value = self::DEFAULT_LEDGER_CODE_STANDARD_PURCHASE;
				break;
			case 'reduced':
				$default_value = self::DEFAULT_LEDGER_CODE_REDUCED_SALE;
				break;
			case 'reduced_purchase':
				$default_value = self::DEFAULT_LEDGER_CODE_REDUCED_PURCHASE;
				break;
			default:
				$default_value = '';
				break;
		}

		return (string) get_option(
			'1984_woo_dk_ledger_code_' . $key,
			$default_value
		);
	}

	/**
	 * Set the ledger code
	 *
	 * Valid keys are `standard`, `reduced`, `shipping` and `costs`.
	 *
	 * @param string $key The ledger type. Defaults to `standard`.
	 * @param string $value The ledger code in DK.
	 *
	 * @return bool True on success. False on failure.
	 */
	public static function set_ledger_code(
		string $key = 'standard',
		string $value = 's002'
	): bool {
		return update_option( '1984_woo_dk_ledger_code_' . $key, $value );
	}

	/**
	 * Get wether product price sync is enabled by default
	 *
	 * @return bool True if enabled, false if disabled.
	 */
	public static function get_product_price_sync(): bool {
		return (bool) get_option( '1984_woo_dk_product_price_sync', true );
	}

	/**
	 * Set wether prodct price sync is enabled by default
	 *
	 * @param bool $value True to enable product name sync by default,
	 *                    false to disable.
	 */
	public static function set_product_price_sync( bool $value ): bool {
		return update_option(
			'1984_woo_dk_product_price_sync',
			(int) $value
		);
	}

	/**
	 * Get wether product quantity sync is enabled by default
	 *
	 * @return bool True if enabled, false if disabled.
	 */
	public static function get_product_quantity_sync(): bool {
		return (bool) get_option( '1984_woo_dk_product_quantity_sync', true );
	}

	/**
	 * Set wether prodct quantity sync is enabled by default
	 *
	 * @param bool $value True to enable product quantity sync by default,
	 *                    false to disable.
	 */
	public static function set_product_quantity_sync( bool $value ): bool {
		return update_option(
			'1984_woo_dk_product_quantity_sync',
			(int) $value
		);
	}

	/**
	 * Get wether product name sync is enabled by default
	 *
	 * @return bool True if enabled, false if disabled.
	 */
	public static function get_product_name_sync(): bool {
		return (bool) get_option( '1984_woo_dk_product_name_sync', true );
	}

	/**
	 * Set wether product name sync is enabled by default
	 *
	 * @param bool $value True to enable product name sync, false to disable it.
	 */
	public static function set_product_name_sync( bool $value ): bool {
		return (bool) update_option(
			'1984_woo_dk_product_name_sync',
			(int) $value
		);
	}

	/**
	 * Get wether invoices should be emailed to customers automatically
	 *
	 * @return bool True if enabled, false if disabled.
	 */
	public static function get_email_invoice(): bool {
		return (bool) get_option(
			'1984_woo_dk_email_invoice',
			true
		);
	}

	/**
	 * Set wether invoices should be emailed to customers automatically
	 *
	 * @param bool $value True to enable invoice emailing, false to disable it.
	 */
	public static function set_email_invoice( bool $value ): bool {
		return update_option(
			'1984_woo_dk_email_invoice',
			(int) $value
		);
	}

	/**
	 * Get wether customers should request to have an invoice with a kennitala
	 */
	public static function get_customer_requests_kennitala_invoice(): bool {
		return (bool) get_option(
			'1984_woo_dk_customer_requests_kennitala_invoice',
			false
		);
	}

	/**
	 * Set wether customers should request to have an invoice with a kennitala
	 *
	 * @param bool $value True to make customers request having a kennitala on
	 *                    their invoices, false to disable it.
	 */
	public static function set_customer_requests_kennitala_invoice(
		bool $value
	): bool {
		return update_option(
			'1984_woo_dk_customer_requests_kennitala_invoice',
			(int) $value
		);
	}

	/**
	 * Get wether invoices should be made automatically if a kennitala is set for the order
	 */
	public static function get_make_invoice_if_kennitala_is_set(): bool {
		return (bool) get_option(
			'1984_woo_dk_make_invoice_if_kennitala_is_set',
			true
		);
	}

	/**
	 * Set wether invoices should be made automatically if a kennitala is set for the order
	 *
	 * @param bool $value True to enable automatic invoice generation if
	 *                    kennitala is set for an order, false to disable it.
	 */
	public static function set_make_invoice_if_kennitala_is_set(
		bool $value
	): bool {
		return (bool) update_option(
			'1984_woo_dk_make_invoice_if_kennitala_is_set',
			(int) $value
		);
	}

	/**
	 * Get wether an invoice should be made automatically for an orhder if a kennitala is missing
	 */
	public static function get_make_invoice_if_kennitala_is_missing(): bool {
		return (bool) get_option(
			'1984_woo_dk_make_invoice_if_kennitala_is_missing',
			true
		);
	}

	/**
	 * Set wether an invoice should be made automatically for an orhder if a kennitala is missing
	 *
	 * @param bool $value True to enable invoice generation if kennitala is
	 *                    missing from an order, false if not.
	 */
	public static function set_make_invoice_if_kennitala_is_missing(
		bool $value
	): bool {
		return (bool) update_option(
			'1984_woo_dk_make_invoice_if_kennitala_is_missing',
			(int) $value
		);
	}

	/**
	 * Get the DK currency
	 *
	 * Facilitates the currency conversion functionality by indicating the
	 * currency product prices are set in DK.
	 *
	 * @return string The currency code.
	 */
	public static function get_dk_currency(): string {
		return (string) get_option(
			'1984_woo_dk_dk_currency',
			'ISK'
		);
	}

	/**
	 * Set the DK currency
	 *
	 * @param string $currency The currency code.
	 */
	public static function set_dk_currency( string $currency ): bool {
		return update_option(
			'1984_woo_dk_dk_currency',
			$currency
		);
	}

	/**
	 * Get wether products that are not for online store should be imported as drafts
	 */
	public static function get_import_nonweb_products(): bool {
		return (bool) get_option(
			'1984_woo_dk_import_nonweb_products',
			true
		);
	}

	/**
	 * Set wether products that are not for online store should be imported as drafts
	 *
	 * @param bool $value True to enable non-web product import, false to
	 *                    disalbe it.
	 */
	public static function set_import_nonweb_products( bool $value ): bool {
		return update_option(
			'1984_woo_dk_import_nonweb_products',
			(int) $value
		);
	}

	/**
	 * Get wether to delete inactive products on sync
	 */
	public static function get_delete_inactive_products(): bool {
		return (bool) get_option(
			'1984_woo_dk_delete_inactive_products',
			true
		);
	}

	/**
	 * Set wether to delete inactive products on sync
	 *
	 * @param bool $value True to enable deletion of inactive products on sync,
	 *             false to disable it.
	 */
	public static function set_delete_inactive_products( bool $value ): bool {
		return update_option(
			'1984_woo_dk_delete_inactive_products',
			(int) $value
		);
	}

	/**
	 * Get wether to make a credit invoice when an order is labelled as refunded
	 */
	public static function get_make_credit_invoice(): bool {
		return (bool) get_option( 'make_credit_invoice', false );
	}

	/**
	 * Set wether to make a credit invoice when an order is labelled as refunded
	 *
	 * @param bool $value True to enable credit invoices,
	 *             false to disable it.
	 */
	public static function set_make_credit_invoice( bool $value ): bool {
		return update_option( 'make_credit_invoice', (int) $value );
	}

	/**
	 * Get the ledger code for domestic customers
	 *
	 * This is the ledger code that is used of the customer's country is the
	 * same as the shop's country.
	 *
	 * @return string The ledger code.
	 */
	public static function get_domestic_customer_ledger_code(): string {
		return (string) (
			get_option(
				'1984_woo_dk_domestic_customer_ledger_code',
				self::DEFAULT_LEDGER_CODE_DOMESTIC_CUSTOMERS
			)
		);
	}

	/**
	 * Set the ledger code for domestic customers
	 *
	 * @param string $value The ledger code to set.
	 *
	 * @return bool True on success, false on failure.
	 */
	public static function set_domestic_customer_ledger_code(
		string $value
	): bool {
		return update_option(
			'1984_woo_dk_domestic_customer_ledger_code',
			(string) $value
		);
	}

	/**
	 * Get the ledger code for international customers
	 *
	 * This is the ledger code that is used of the customer's country is not the
	 * same as the shop's country.
	 *
	 * @return string The ledger code.
	 */
	public static function get_international_customer_ledger_code(): string {
		return (string) (
			get_option(
				'1984_woo_dk_international_customer_ledger_code',
				self::DEFAULT_LEDGER_CODE_INTERNATIONAL_CUSTOMERS
			)
		);
	}

	/**
	 * Set the ledger code for international customers
	 *
	 * @param string $value The ledger code to set.
	 *
	 * @return bool True on success, false on failure.
	 */
	public static function set_international_customer_ledger_code(
		string $value
	): bool {
		return update_option(
			'1984_woo_dk_international_customer_ledger_code',
			(string) $value
		);
	}

	public static function get_use_attribute_description(): bool {
		return (bool) (
			get_option( '1984_woo_dk_use_attribute_description', true )
		);
	}

	public static function set_use_attribute_description( bool $value ): bool {
		return update_option(
			'1984_woo_dk_use_attribute_description',
			(bool) $value
		);
	}

	public static function get_use_attribute_value_description(): bool {
		return (bool) (
			get_option( '1984_woo_dk_use_attribute_value_description', true )
		);
	}

	public static function set_use_attribute_value_description(
		bool $value
	): bool {
		return update_option(
			'1984_woo_dk_use_attribute_value_description',
			(bool) $value
		);
	}

	public static function get_product_convertion_to_variation_enabled(): bool {
		return (bool) (
			get_option(
				'1984_woo_dk_product_convertion_to_variation_enabled',
				false
			)
		);
	}

	public static function set_product_convertion_to_variation_enabled(
		bool $value
	): bool {
		return update_option(
			'1984_woo_dk_product_convertion_to_variation_enabled',
			(bool) $value
		);
	}
}
