<?php

declare(strict_types = 1);

namespace NineteenEightyFour\NineteenEightyWoo\Rest;

use WP_REST_Request;
use WP_REST_Response;
use WP_Error;
use NineteenEightyFour\NineteenEightyWoo\Rest\EmptyBodyEndpointTemplate;
use NineteenEightyFour\NineteenEightyWoo\Service\DKApiRequest;

/**
 * The Order Invoice PDF REST API endpoint
 */
class OrderInvoicePdf implements EmptyBodyEndpointTemplate {
	const NAMESPACE = 'NineteenEightyWoo/v1';
	const PATH      = '/order_invoice_pdf/(?P<invoice_number>[\d]+)';

	/**
	 * The constructor
	 */
	public function __construct() {
		add_action( 'rest_api_init', array( __CLASS__, 'register_route' ) );
	}

	/**
	 * Register the REST API route
	 */
	public static function register_route(): bool {
		return register_rest_route(
			self::NAMESPACE,
			self::PATH,
			array(
				'methods'             => 'GET',
				'callback'            => array( __CLASS__, 'rest_api_callback' ),
				'permission_callback' => array( __CLASS__, 'permission_check' ),
			)
		);
	}

	/**
	 * The REST API callback
	 *
	 * @param WP_REST_Request $request The REST API callback.
	 */
	public static function rest_api_callback(
		WP_REST_Request $request
	): WP_REST_Response|WP_Error {
		$api_request = new DKApiRequest();

		$headers = array_merge(
			$api_request->get_headers,
			array( 'Accept-Language' => substr( get_locale(), 0, 2 ) )
		);

		$result = $api_request->wp_http->get(
			DKApiRequest::DK_API_URL .
			'/Sales/Invoice/' .
			$request['invoice_number'] .
			'/pdf',
			array( 'headers' => $headers ),
		);

		if ( $result instanceof WP_Error ) {
			return new WP_REST_Response( status: 404 );
		}

		if ( $result['response']['code'] !== 200 ) {
			return new WP_REST_Response( status: 404 );
		}

		return new WP_REST_Response(
			array(
				'status' => 200,
				// phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode
				'data'   => base64_encode( $result['body'] ),
			)
		);
	}

	/**
	 * The permission check
	 */
	public static function permission_check(): bool {
		return current_user_can( 'edit_others_posts' );
	}
}
