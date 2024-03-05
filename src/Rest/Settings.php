<?php

declare(strict_types = 1);

namespace NineteenEightyFour\NineteenEightyWoo\Rest;

use WP_Error;
use WP_REST_Request;
use WP_REST_Response;
use NineteenEightyFour\NineteenEightyWoo\Opis\JsonSchema\Validator;

/**
 * The REST API Settings endpoint class
 *
 * Handles the `NineteenEightyWoo/v1/settings/` REST endpoint.
 */
class Settings {
	const JSON_SCHEMA = <<<'JSON'
	{
		"$schema": "http://json-schema.org/draft-07/schema#",
		"type": "object",
		"properties": {
			"api_key": { "type": "string" },
			"payment_methods": {
				"type": "array",
				"items": {
					"type": "object",
					"properties": {
						"woo_id": { "type": "string" },
						"dk_id": { "type": "number" },
						"dk_name": { "type": "string" }
					},
					"required": ["woo_id", "dk_id", "dk_name" ]
				}
			}
		},
		"required": ["api_key", "payment_methods" ]
	}
	JSON;

	/**
	 * The Constructor for the Settings REST endpoint
	 *
	 * Registers the NineteenEightyWoo/v1/settings/ endpoint, that receives
	 * requests from the admin interface.
	 */
	public function __construct() {
		add_action( 'rest_api_init', array( __CLASS__, 'register_route' ) );
	}

	/**
	 * Register the route
	 *
	 * @return bool True on success, false on failure.
	 */
	public static function register_route(): bool {
		return register_rest_route(
			'NineteenEightyWoo/v1',
			'/settings/',
			array(
				'methods'             => 'POST',
				'callback'            => array( __CLASS__, 'rest_api_callback' ),
				'permission_callback' => array( __CLASS__, 'permission_check' ),
				'validate_callback'   => array( __CLASS__, 'validate_request' ),
				'schema'              => array( __CLASS__, 'get_schema' ),
			)
		);
	}

	/**
	 * The request callback for the Settings REST endpoint
	 *
	 * @param WP_REST_Request $request The REST request object.
	 *
	 * @return WP_REST_Response Returns a 200 HTTP status as a confirmation.
	 */
	public static function rest_api_callback(
		WP_REST_Request $request
	): WP_REST_Response|WP_Error {
		$rest_body = $request->get_body();
		$rest_json = json_decode( $rest_body );

		$validator  = new Validator();
		$validation = $validator->validate( $rest_json, self::JSON_SCHEMA );

		if ( true === $validation->hasError() ) {
			return new WP_Error(
				'bad_request',
				'Bad Request',
				array( 'status' => '400' ),
			);
		}

		update_option( '1984_woo_dk_api_key', $rest_json->api_key );

		foreach ( $rest_json->payment_methods as $p ) {
			update_option(
				'1984_woo_dk_payment_method_' . $p->woo_id,
				$p
			);
		}

		return new WP_REST_Response( array( 'status' => 200 ) );
	}

	/**
	 * The permission callback for the Settings RESt endpoint
	 *
	 * Checks if the current user holdin the nonce has the `manage_options`
	 * capability.
	 */
	public static function permission_check(): bool {
		return current_user_can( 'manage_options' );
	}

	/**
	 * Get the JSON schema as an object
	 *
	 * This facilitates the endpoint registration and REST endpoint discovery.
	 * The Opis validator still wants the schema as a JSON-encoded string and
	 * that's absolutely fine as well.
	 *
	 * @return object The schema as a standard PHP object.
	 */
	public static function get_schema(): object {
		return json_decode( self::JSON_SCHEMA );
	}

	/**
	 * Validate the JSON request based on the JSON schema
	 *
	 * Used as the validate_callback calable in the endpoint registration.
	 *
	 * @param WP_REST_Request $request The request.
	 *
	 * @return bool True if the request is valid, false if not.
	 */
	public static function validate_request( WP_REST_Request $request ): bool {
		$rest_body = $request->get_body();
		$rest_json = json_decode( $rest_body );

		$validator  = new Validator();
		$validation = $validator->validate( $rest_json, self::JSON_SCHEMA );

		if ( true === $validation->hasError() ) {
			return false;
		}

		return true;
	}
}
