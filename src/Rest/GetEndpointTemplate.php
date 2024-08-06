<?php

declare(strict_types = 1);

namespace NineteenEightyFour\NineteenEightyWoo\Rest;

use stdClass;
use WP_REST_Request;
use WP_REST_Response;
use WP_Error;

interface GetEndpointTemplate {
	/**
	 * The constructor
	 */
	public function __construct();

	/**
	 * Register the WP REST API route
	 */
	public static function register_route(): bool;

	/**
	 * The WP REST API callback
	 *
	 * @param WP_REST_Request $request The WP REST request object to process.
	 *
	 * @return WP_REST_Response|WP_Error WP_REST_Response object on success,
	 *                                   WP_Error object on failure.
	 */
	public static function rest_api_callback(
		WP_REST_Request $request
	): WP_REST_Response|WP_Error;

	/**
	 * The WP REST API permission check
	 *
	 * @return bool True if the user is permitted to do the action, false if not.
	 */
	public static function permission_check(): bool;
}
