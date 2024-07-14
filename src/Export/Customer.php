<?php

declare(strict_types = 1);

namespace NineteenEightyFour\NineteenEightyWoo\Export;

use NineteenEightyFour\NineteenEightyWoo\Service\DKApiRequest;
use NineteenEightyFour\NineteenEightyWoo\Config;
use NineteenEightyFour\NineteenEightyWoo\Helpers\Order as OrderHelper;
use stdClass;
use WC_Customer;
use WC_Order;
use WP_Error;

/**
 * The Customer Export class
 *
 * Provides functions for exporting orders to a valid HTTP body for the DK API.
 *
 * @see https://api.dkplus.is/swagger/ui/index#/Sales32Invoice
 **/
class Customer {
	const API_PATH = '/Customer/';

	/**
	 * Create a customer record in DK respresenting a WooCommerce customer record
	 *
	 * @param WC_Customer $customer The WooCommerce customer.
	 *
	 * @return bool|WP_Error True on success, false if connection was
	 *                       established but the request was rejected, WC_Error
	 *                       if there was a connection error.
	 */
	public static function create_in_dk( WC_Customer $customer ): bool|WP_Error {
		$api_request  = new DKApiRequest();
		$request_body = self::to_dk_customer_body( $customer );

		$result = $api_request->request_result(
			self::API_PATH,
			wp_json_encode( $request_body ),
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
	 * Create a customer record in DK representing a WooCommerce order record
	 *
	 * This facilitates "guests", as instead of using a WC_Customer object
	 * directly, this takes the customer information for the order itself.
	 *
	 * @param WC_Order $wc_order The WooCommerce order.
	 *
	 * @return bool|WP_Error True on success, false if connection was
	 *                       established but the request was rejected, WC_Error
	 *                       if there was a connection error.
	 */
	public static function create_in_dk_from_order(
		WC_Order $wc_order
	): bool|WP_Error {
		$api_request  = new DKApiRequest();
		$request_body = wp_json_encode(
			self::to_dk_customer_body_from_order( $wc_order )
		);

		$result = $api_request->request_result(
			self::API_PATH,
			$request_body,
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
	 * Update the DK customer record for a WooCommerce costumer
	 *
	 * @param WC_Customer $customer The WooCommerce customer.
	 *
	 * @return bool|WP_Error True on success, false if connection was
	 *                       established but the request was rejected, WC_Error
	 *                       if there was a connection error.
	 */
	public static function update_in_dk( WC_Customer $customer ): bool|WP_Error {
		$api_request  = new DKApiRequest();
		$request_body = self::to_dk_customer_body( $customer );

		$customer_number = $request_body['Number'];
		unset( $request_body['Number'] );

		$result = $api_request->request_result(
			self::API_PATH . $customer_number,
			wp_json_encode( $request_body ),
			'PUT'
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
	 * Check if a WooCommerce customer has a corresponding customer record in DK
	 *
	 * @param WC_Customer|string $customer The WooCommerce customer or the
	 *                           associated kennitala or customer number as a
	 *                           string.
	 *
	 * @return bool|WP_Error True if the customer exsists in DK, false if
	 *                       connection was established but the request was
	 *                       rejected, WC_Error if there was a connection error.
	 */
	public static function is_in_dk( WC_Customer|string $customer ): bool|WP_Error {
		$api_request = new DKApiRequest();

		if ( is_string( $customer ) ) {
			$dk_customer_number = $customer;
		} else {
			$dk_customer_number = self::assume_dk_customer_number( $customer );
		}

		$result = $api_request->get_result(
			'/Customer/' . $dk_customer_number
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
	 * Check if the WooCommerce customer has a DK customer number
	 *
	 * Checks if a WooCommerce costomer record has the corresponding DK customer
	 * number saved as metadata.
	 *
	 * @param WC_Customer $customer The WooCommerce customer.
	 *
	 * @return bool True if the customer number has been set, false if not.
	 */
	public static function has_dk_customer_number(
		WC_Customer $customer
	): bool {
		if ( empty( $customer->get_meta( '1984_woo_dk_customer_number' ) ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Make up a DK customer number for a customer
	 *
	 * This uses the kennitala if it is set, otherwise, it will create one based
	 * on the customer number prefix and the WordPress user ID.
	 *
	 * @see Config::get_customer_number_prefix()
	 *
	 * @param WC_Customer $customer The customer.
	 *
	 * @return string The assumed customer number.
	 */
	public static function assume_dk_customer_number(
		WC_Customer $customer
	): string {
		$kennitala = (string) $customer->get_meta( 'kennitala', true, 'edit' );
		if ( ! empty( $kennitala ) ) {
			return $kennitala;
		}

		return Config::get_default_kennitala();
	}

	/**
	 * Export a WooCommerce customer to a DK API customer POST body
	 *
	 * @param WC_Customer $customer The WooCommerce customer object.
	 */
	public static function to_dk_customer_body( WC_Customer $customer ): array {
		$full_name = implode(
			' ',
			array(
				$customer->get_billing_first_name(),
				$customer->get_billing_last_name(),
			)
		);

		$customer_props = array(
			'Number'   => self::assume_dk_customer_number( $customer ),
			'Name'     => $full_name,
			'Address1' => $customer->get_billing_address_1(),
			'Address2' => $customer->get_billing_address_2(),
			'City'     => $customer->get_billing_city(),
			'ZipCode'  => $customer->get_billing_postcode(),
			'Phone'    => $customer->get_billing_phone(),
			'Email'    => $customer->get_billing_email(),
		);

		if ( get_option( 'woocommerce_default_country' ) !== $customer->get_billing_country() ) {
			$customer_props['CountryCode'] = $customer->get_billing_country();
		}

		return $customer_props;
	}

	/**
	 * Export a WooCommerce customer to a valid HTTP body based on their Id
	 *
	 * @param int $customer_id The customer ID.
	 */
	public static function id_to_dk_customer_body( int $customer_id ): array {
		$customer = new WC_Customer( $customer_id );
		return self::to_dk_customer_body( $customer );
	}

	/**
	 * Generate a customer request body for DK from order information
	 *
	 * @param WC_Order $wc_order The WooCommerce order.
	 *
	 * @return stdClass An object representing the customer information for the order.
	 */
	public static function to_dk_customer_body_from_order(
		WC_Order $wc_order
	): stdClass {
		$payment_mapping = Config::get_payment_mapping(
			$wc_order->get_payment_method()
		);

		$store_location = wc_get_base_location();
		if ( $wc_order->get_billing_country() === $store_location['country'] ) {
			$ledger_code = Config::get_domestic_customer_ledger_code();
		} else {
			$ledger_code = Config::get_international_customer_ledger_code();
		}

		return (object) array(
			'Number'      => OrderHelper::get_kennitala( $wc_order ),
			'Name'        => $wc_order->get_formatted_billing_full_name(),
			'Address1'    => $wc_order->get_billing_address_1(),
			'Address2'    => $wc_order->get_billing_address_2(),
			'Country'     => $wc_order->get_billing_country(),
			'City'        => $wc_order->get_billing_city(),
			'ZipCode'     => $wc_order->get_billing_postcode(),
			'Phone'       => $wc_order->get_billing_phone(),
			'Email'       => $wc_order->get_billing_email(),
			'SalesPerson' => Config::get_default_sales_person_number(),
			'PaymentMode' => $payment_mapping->dk_mode,
			'PaymentTerm' => $payment_mapping->dk_term,
			'LedgerCode'  => $ledger_code,
		);
	}
}
