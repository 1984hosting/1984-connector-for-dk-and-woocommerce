<?php

declare(strict_types = 1);

namespace NineteenEightyFour\NinteenEightyWoo\Rest;

use WP_Error;
use WP_REST_Request;
use WP_REST_Response;

/**
 * The REST API Settings endpoint class
 *
 * Handles the `NinteenEightyWoo/v1/settings/` REST endpoint.
 */
class Settings {
	/**
	 * The Constructor for the Settings REST endpoint
	 *
	 * Registers the NinteenEightyWoo/v1/settings/ endpoint, that receives
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
			'NinteenEightyWoo/v1',
			'/settings/',
			array(
				'methods'             => 'POST',
				'callback'            => array( __CLASS__, 'rest_api_callback' ),
				'permission_callback' => array( __CLASS__, 'permission_check' ),
			)
		);
	}

	/**
	 * The request callback for the Settings REST endpoint
	 *
	 * @param WP_REST_Request $request The REST request object.
	 */
	public static function rest_api_callback(
		WP_REST_Request $request
	): WP_REST_Response|WP_Error {
		$rest_body = $request->get_body();
		$rest_json = json_decode( $rest_body );

		if (
			false === is_object( $rest_json ) ||
			( false === self::validate_post_schema( $rest_json ) )
		) {
			return new WP_Error(
				'bad_request',
				'Bad Request',
				array( 'status' => '400' ),
			);
		}

		update_option( '1984_woo_dk_api_key', $rest_json->api_key );

		update_option(
			'1984_woo_dk_payment_methods',
			$rest_json->payment_methods
		);

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
	 * Validate the POST JSON data
	 *
	 * @param object $json_body The JSON-decoded body of the settings POST
	 *                          request.
	 */
	public static function validate_post_schema( object $json_body ): bool {
		if ( false === property_exists( $json_body, 'api_key' ) ) {
			return false;
		}

		if ( false === is_string( $json_body->api_key ) ) {
			return false;
		}

		foreach ( $json_body->payment_methods as $p ) {
			if ( false === is_string( $p->woo_id ) ) {
				return false;
			}
			if ( false === is_int( $p->dk_id ) ) {
				return false;
			}
			if ( false === is_string( $p->dk_name ) ) {
				return false;
			}
		}

		return true;
	}
}