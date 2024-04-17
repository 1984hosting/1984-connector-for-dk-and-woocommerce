<?php

declare(strict_types = 1);

namespace NineteenEightyFour\NineteenEightyWoo\Service;

use NineteenEightyFour\NineteenEightyWoo\Config;
use stdClass;
use WP_Error;
use WP_Http;

/**
 * The DK API Request class
 *
 * Handles the low-level HTTP requests to the DK JSON API.
 */
class DKApiRequest {
	const DK_API_URL = 'https://api.dkplus.is/api/v1';

	/**
	 * The DK API key
	 */
	public string|false $api_key;

	/**
	 * The HTTP headers used for GET requests to the DK API
	 */
	public array $get_headers;

	/**
	 * The HTTP headers used for requests such as POST, PATCH etc.
	 */
	public array $post_headers;

	/**
	 * The WP_Http class used by the class instance
	 */
	public WP_Http $wp_http;

	/**
	 * Oh, don't worry, it' just a class constructor
	 */
	public function __construct() {
		$this->api_key = Config::get_dk_api_key();

		$this->get_headers = array(
			'Accept'        => 'application/json',
			'Authorization' => 'bearer ' . $this->api_key,
		);

		$this->post_headers = array(
			'Content-Type'  => 'application/json',
			'Authorization' => 'bearer ' . $this->api_key,
		);

		$this->wp_http = new WP_Http();
	}

	/**
	 * Get a record from the DK API
	 *
	 * This uses the `WP_Http` class to fets a API resource from the DK API
	 * using a GET request.
	 *
	 * @param string $path The internal path do the API resource.
	 *
	 * @return WP_Error|stdClass An object containing a `data` attribute with
	 *                           the JSON encoded response body and a
	 *                           `response_code` attribute, with the numeric
	 *                           HTTP response status as an integer, or WP_Error
	 *                           on failure.
	 */
	public function get_result( string $path ): WP_Error|stdClass {
		if ( true === empty( Config::get_dk_api_key() ) ) {
			return new WP_Error(
				'dk-api-key-missing',
				__( 'The DK API key is missing.', '1984-dk-woo' )
			);
		}

		$request = $this->wp_http->get(
			self::DK_API_URL . $path,
			array(
				'httpversion' => '1.1',
				'headers'     => $this->get_headers,
			),
		);

		return $this->parse_wp_http_response( $request );
	}

	/**
	 * Send a resource modifying request to the DK API
	 *
	 * @param string $path   The internal path do the API resource.
	 * @param string $body   The request body.
	 * @param string $method The HTTP method to use. Defaults to POST.
	 *
	 * @return WP_Error|stdClass An object containing a `data` attribute with
	 *                           the JSON encoded response body and a
	 *                           `response_code` attribute, with the numeric
	 *                           HTTP response status as an integer, or WP_Error
	 *                           on failure.
	 */
	public function request_result(
		string $path,
		string $body = '',
		string $method = 'POST'
	): WP_Error|stdClass {
		if ( true === empty( Config::get_dk_api_key() ) ) {
			return new WP_Error(
				'dk-api-key-missing',
				__( 'The DK API key is missing.', '1984-dk-woo' )
			);
		}

		$request = $this->wp_http->request(
			self::DK_API_URL . $path,
			array(
				'method'      => $method,
				'httpversion' => '1.1',
				'headers'     => $this->post_headers,
				'body'        => $body,
			),
		);

		return $this->parse_wp_http_response( $request );
	}

	/**
	 * Convert a WP_Http result into the retrned value
	 *
	 * @param array|WP_Error $request The WP_Http result.
	 *
	 * @return WP_Error|stdClass An object containing a `data` attribute with
	 *                           the JSON encoded response body and a
	 *                           `response_code` attribute, with the numeric
	 *                           HTTP response status as an integer, or WP_Error
	 *                           on failure.
	 */
	private function parse_wp_http_response(
		array|WP_Error $request
	): WP_Error|stdClass {
		if ( $request instanceof WP_Error ) {
			return $request;
		}

		$status = $request['http_response']->get_status();
		$body   = json_decode( $request['http_response']->get_data() );

		return (object) array(
			'data'          => $body,
			'response_code' => $status,
		);
	}
}
