<?php

declare(strict_types = 1);

namespace NineteenEightyFour\NineteenEightyWoo\Rest;

use WP_REST_Request;
use WP_REST_Response;
use WP_Error;
use NineteenEightyFour\NineteenEightyWoo\Rest\EmptyBodyEndpointTemplate;
use NineteenEightyFour\NineteenEightyWoo\Export\Invoice as ExportInvoice;
use NineteenEightyFour\NineteenEightyWoo\Config;
use NineteenEightyFour\NineteenEightyWoo\Helpers\Order as OrderHelper;

/**
 * The Order DK Invoice endpoint class
 *
 * This is a WP REST API endpoint for requesting an invoice to be made for an
 * order in DK.
 */
class OrderDKInvoice implements EmptyBodyEndpointTemplate {
	const NAMESPACE = 'NineteenEightyWoo/v1';
	const PATH      = '/order_dk_invoice/(?P<order_id>[\d]+)';

	/**
	 * The constructor
	 */
	public function __construct() {
		add_action( 'rest_api_init', array( __CLASS__, 'register_route' ) );
	}

	/**
	 * Register the REST API route
	 *
	 * This registers a POST endpoint `/order_dk_invoice/(?P<order_id>[\d]+)`
	 * under our namespace.
	 */
	public static function register_route(): bool {
		return register_rest_route(
			self::NAMESPACE,
			self::PATH,
			array(
				'methods'             => 'POST',
				'callback'            => array( __CLASS__, 'rest_api_callback' ),
				'permission_callback' => array( __CLASS__, 'permission_check' ),
			)
		);
	}

	/**
	 * The REST API callback
	 *
	 * @param WP_REST_Request $request The request object.
	 *
	 * @return WP_REST_Response|WP_Error WP_REST_Response on success, WP_Error
	 *                                   on if the request is invalid.
	 */
	public static function rest_api_callback(
		WP_REST_Request $request
	): WP_REST_Response|WP_Error {
		$wc_order = wc_get_order( $request['order_id'] );

		if ( ! OrderHelper::can_be_invoiced( $wc_order ) ) {
			$wc_order->add_order_note(
				__(
					'An invoice could not be created in DK for this order as a line item in this order does not have a SKU.',
					'1984-dk-woo'
				)
			);

			return new WP_REST_Response( status: 400 );
		}

		$invoice_number = ExportInvoice::create_in_dk(
			$wc_order,
			true
		);

		if ( ! is_string( $invoice_number ) ) {
			return new WP_REST_Response( status: 400 );
		}

		$wc_order->add_order_note(
			sprintf(
				// Translators: %1$s is a placeholder for the invoice number generated in DK.
				__(
					'An invoice for this order has been created in DK. The invoice number is %1$s.',
					'1984-dk-woo'
				),
				$invoice_number
			)
		);

		if ( Config::get_email_invoice() ) {
			if ( ExportInvoice::email_in_dk( $wc_order ) ) {
				$wc_order->add_order_note(
					__(
						'An email containing the invoice as a PDF attachment was sent to the customer.',
						'1984-dk-woo'
					)
				);
			} else {
				$wc_order->add_order_note(
					__(
						'It was not possible to send an email to the customer containing the invoice as a PDF attachment.',
						'1984-dk-woo'
					)
				);
			}
		}

		$wc_order->delete_meta_data(
			'1984_dk_woo_invoice_creation_error'
		);

		$wc_order->delete_meta_data(
			'1984_dk_woo_invoice_creation_error_message'
		);

		$wc_order->save_meta_data();

		return new WP_REST_Response(
			$invoice_number,
			201
		);
	}

	/**
	 * The permission check
	 */
	public static function permission_check(): bool {
		return current_user_can( 'edit_others_posts' );
	}
}
