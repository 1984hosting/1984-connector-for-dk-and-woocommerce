<?php

declare(strict_types = 1);

namespace NineteenEightyFour\NineteenEightyWoo\Rest;

use WP_REST_Request;
use WP_REST_Response;
use WP_Error;
use NineteenEightyFour\NineteenEightyWoo\Opis\JsonSchema\Validator;

interface RestEndpointTemplate {
	public function __construct();

	public static function register_route(): bool;

	public static function rest_api_callback(WP_REST_Request $request): WP_REST_Response|WP_Error;

	public static function permission_check(): bool;

	public static function validate_request( WP_REST_Request $request ): bool;

	public static function get_schema(): object;

	public static function json_schema(): string;
}

class OrderInvoiceNumber implements RestEndpointTemplate {
	const NAMESPACE = 'NineteenEightyWoo/v1';
	const PATH      = '/order_invoice_number/';
	const SCHEMA    = 'rest/order_invoice_number.json';

	public function __construct() {
		add_action( 'rest_api_init', array( __CLASS__, 'register_route' ) );
	}

	public static function register_route(): bool {
		return register_rest_route(
			self::NAMESPACE,
			self::PATH,
			array(
				'methods'             => 'POST',
				'callback'            => array( __CLASS__, 'rest_api_callback' ),
				'permission_callback' => array( __CLASS__, 'permission_check' ),
				'validate_callback'   => array( __CLASS__, 'validate_request' ),
				'schema'              => array( __CLASS__, 'get_schema' ),
			)
		);
	}

	public static function rest_api_callback(
		WP_REST_Request $request
	): WP_REST_Response|WP_Error {
		$rest_body = $request->get_body();
		$rest_json = json_decode( $rest_body );

		$validator  = new Validator();
		$validation = $validator->validate( $rest_json, self::json_schema() );

		if ( $validation->hasError() ) {
			return new WP_Error(
				'bad_request',
				'Bad Request',
				array( 'status' => '400' ),
			);
		}

		return new WP_REST_Response( array( 'status' => 200 ) );
	}

	public static function permission_check(): bool {
		return current_user_can( 'manage_options' );
	}

	public static function validate_request( WP_REST_Request $request ): bool {
		$rest_body = $request->get_body();
		$rest_json = json_decode( $rest_body );

		$validator  = new Validator();
		$validation = $validator->validate( $rest_json, self::json_schema() );

		if ( $validation->hasError() ) {
			return false;
		}

		return true;
	}

	public static function get_schema(): object {
		return json_decode( self::json_schema() );
	}

	public static function json_schema(): string {
		// phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
		return file_get_contents(
			dirname( __DIR__, 2 ) . '/json_schemas/' . self::SCHEMA
		);
	}
}
