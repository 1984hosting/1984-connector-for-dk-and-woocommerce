<?php

declare(strict_types = 1);

namespace NineteenEightyFour\NineteenEightyWoo\Export;

use NineteenEightyFour\NineteenEightyWoo\Config;
use NineteenEightyFour\NineteenEightyWoo\Export\Order as ExportOrder;
use NineteenEightyFour\NineteenEightyWoo\Service\DKApiRequest;
use WC_Order;
use WC_Email;
use WP_Error;
use DateTime;

/**
 * The Invoice Export class
 *
 * Facilitates the creation of invoices and credit invoices in DK from orders in
 * WooCommerce.
 */
class Invoice {
	const API_PATH = '/Sales/Invoice/';

	/**
	 * Create an invoice in DK based on A WooCommerce order
	 *
	 * @param WC_Order $wc_order The WooCommerce order.
	 *
	 * @return string|false|WP_Error A string representing the invoice number
	 *                               from DK on success, false if connection was
	 *                               established but the request was rejected,
	 *                               WC_Error if there was a connection error.
	 */
	public static function create_in_dk(
		WC_Order $wc_order
	): string|false|WP_Error {
		$api_request  = new DKApiRequest();
		$request_body = self::to_dk_invoice_body( $wc_order );

		$result = $api_request->request_result(
			self::API_PATH,
			wp_json_encode( $request_body ),
		);

		if ( $result instanceof WP_Error ) {
			return $result;
		}

		if ( 200 !== $result->response_code ) {
			return false;
		}

		if ( property_exists( $result->data, 'Number' ) ) {
			self::assign_dk_invoice_number(
				$wc_order,
				$result->data->Number
			);
			return (string) $result->data->Number;
		}

		return false;
	}

	/**
	 * Reverse an invoice for a WooCommerce order, creating a credit invoice in the process
	 *
	 * @param WC_Order $wc_order The WooCommerce order.
	 *
	 * @return string|false|WP_Error A string representing the credit invoice
	 *                               number from DK on success, false if
	 *                               connection was established but the request
	 *                               was rejected, WC_Error if there was a
	 *                               connection error.
	 */
	public static function reverse_in_dk(
		WC_Order $wc_order
	): string|false|WP_Error {
		$api_request    = new DKApiRequest();
		$invoice_number = self::get_dk_invoice_number( $wc_order );

		if ( true === empty( $invoice_number ) ) {
			return false;
		}

		$date           = new DateTime();
		$formatted_date = $date->format( 'Y-m-d' );

		$result = $api_request->request_result(
			self::API_PATH .
			$invoice_number .
			'/reverse?=date=' .
			$formatted_date,
		);

		if ( $result instanceof WP_Error ) {
			return $result;
		}

		if ( 200 !== $result->response_code ) {
			return false;
		}

		if ( property_exists( $result->data, 'Number' ) ) {
			self::assign_dk_credit_invoice_number(
				$wc_order,
				$result->data->Number
			);
			return $result->data->Number;
		}

		return false;
	}

	/**
	 * Sends an invoice for an order to a customer via DK
	 *
	 * @param WC_Order $wc_order The WooCommerce order.
	 * @param string   $invoice_type The type of invoice. (Either debit or credit).
	 *
	 * @return bool|WP_Error True on success. False or WP_Error on failure.
	 */
	public static function email_in_dk(
		WC_Order $wc_order,
		string $invoice_type = 'debit'
	): bool|WP_Error {
		if (
			false === in_array(
				$invoice_type,
				array( 'debit', 'credit' ),
				true
			)
		) {
			return false;
		}

		$to = $wc_order->get_billing_email();

		if ( true === empty( $to ) ) {
			return false;
		}

		$subject = sprintf(
			// Translators: The %1$s is a placeholder for the site's title.
			__( 'Your Invoice From %1$s', 'NineteenEightyWoo' ),
			get_bloginfo( 'name' )
		);

		$request_body = array(
			'To'      => $to,
			'Subject' => $subject,
		);

		$api_request = new DKApiRequest();

		if ( 'debit' === $invoice_type ) {
			$invoice_number = self::get_dk_invoice_number( $wc_order );
		}

		if ( 'credit' === $invoice_type ) {
			$invoice_number = self::get_dk_credit_invoice_number( $wc_order );
		}

		if ( true === empty( $invoice_number ) ) {
			return false;
		}

		$result = $api_request->request_result(
			self::API_PATH . $invoice_number . '/email',
			wp_json_encode( $request_body )
		);

		if ( $result instanceof WP_Error ) {
			return $result;
		}

		if ( 200 !== $result->response_code ) {
			return false;
		}

		if ( 200 === $result->response_code ) {
			return true;
		}

		return false;
	}

	/**
	 * Prepare a JSON POST body for creating a DK invoice
	 *
	 * @param WC_Order $wc_order The WooCommerce order.
	 *
	 * @return array An associative array representing the JSON request body.
	 */
	public static function to_dk_invoice_body(
		WC_Order $wc_order,
	): array {
		$invoice_body = ExportOrder::to_dk_order_body( $wc_order );

		$invoice_body['SalesPerson'] = Config::get_default_sales_person_number();

		if ( true === $wc_order->is_paid() ) {
			$payment_mapping = Config::get_payment_mapping(
				$wc_order->get_payment_method()
			);

			$invoice_body['Payments'] = array(
				'ID'     => $payment_mapping->dk_id,
				'Name'   => $payment_mapping->dk_name,
				'Amount' => $wc_order->get_total(),
			);
		}

		return $invoice_body;
	}

	/**
	 * Assign a DK invoice number to a WooCommerce order
	 *
	 * @param WC_Order $wc_order The WooCommerce order.
	 * @param string   $dk_invoice_number The invoice number.
	 *
	 * @return string The invoice number.
	 */
	public static function assign_dk_invoice_number(
		WC_Order $wc_order,
		string $dk_invoice_number
	): string {
		$wc_order->update_meta_data(
			'1984_woo_dk_invoice_number',
			$dk_invoice_number
		);

		$wc_order->save_meta_data();

		return $dk_invoice_number;
	}

	/**
	 * The the DK invoice number for an order based on metadata
	 *
	 * @param WC_Order $wc_order The WooCommerce order.
	 */
	public static function get_dk_invoice_number(
		WC_Order $wc_order
	): string {
		return (string) $wc_order->get_meta(
			'1984_woo_dk_invoice_number'
		);
	}

	/**
	 * Assign a DK credit invoice number to an order
	 *
	 * @param WC_Order $wc_order The WooCommerce order.
	 * @param string   $dk_credit_invoice_number The credit invoice number.
	 */
	public static function assign_dk_credit_invoice_number(
		WC_Order $wc_order,
		string $dk_credit_invoice_number
	): string {
		$wc_order->update_meta_data(
			'1984_woo_dk_credit_invoice_number',
			$dk_credit_invoice_number
		);

		$wc_order->save_meta_data();

		return $dk_credit_invoice_number;
	}

	/**
	 * Get the DK credit invoice number from an order
	 *
	 * @param WC_Order $wc_order The WooCommerce order.
	 *
	 * @return string The credit invoice number from DK.
	 */
	public static function get_dk_credit_invoice_number(
		WC_Order $wc_order
	): string {
		return (string) $wc_order->get_meta(
			'1984_woo_dk_credit_invoice_number'
		);
	}
}
