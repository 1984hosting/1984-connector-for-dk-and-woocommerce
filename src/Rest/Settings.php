<?php

declare(strict_types = 1);

namespace NineteenEightyFour\NinteenEightyWoo\Rest;

use WP_REST_Request;
use WP_REST_Response;

class Settings {
	public function __construct() {
		add_action(
			'rest_api_init',
			array( __CLASS__, 'register_route' )
		);
	}

	public static function register_route() {
		register_rest_route(
			'NinteenEightyWoo/v1',
			'/settings/',
			array(
				'methods'             => 'POST',
				'callback'            => array( __CLASS__, 'rest_api_callback' ),
				'permission_callback' => array( __CLASS__, 'permission_check' ),
			)
		);
	}

	public static function rest_api_callback(
		WP_REST_Request $request
	): WP_REST_Response {
		return new WP_REST_Response(
			array( 'status' => 200 )
		);
	}

	public static function permission_check(): bool {
		return current_user_can( 'manage_options' );
	}
}
