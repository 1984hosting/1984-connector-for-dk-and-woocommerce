<?php

declare(strict_types = 1);

namespace NineteenEightyFour\NineteenEightyWoo\Rest;

use WP_REST_Request;
use WP_REST_Response;
use WP_Error;
use NineteenEightyFour\NineteenEightyWoo\Opis\JsonSchema\Validator;
use NineteenEightyFour\NineteenEightyWoo\Rest\GetEndpointTemplate;
use NineteenEightyFour\NineteenEightyWoo\Service\DKApiRequest;

class OrderInvoicePdf implements GetEndpointTemplate {
	const NAMESPACE = 'NineteenEightyWoo/v1';
	const PATH      = '/order_invoice_pdf/(?P<invoice_number>[\d]+)';
	const SCHEMA    = 'rest/order_invoice_number.json';

	public function __construct() {
		add_action( 'rest_api_init', array( __CLASS__, 'register_route' ) );
	}

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
				'data'   => base64_encode( $result['body'] ),
			)
		);
	}

	public static function permission_check(): bool {
		return current_user_can( 'edit_others_posts' );
	}
}
