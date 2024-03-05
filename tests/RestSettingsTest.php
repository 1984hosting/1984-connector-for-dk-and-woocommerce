<?php

declare(strict_types = 1);

namespace NineteenEightyFour\NinteenEightyWoo\Tests;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\TestDox;
use NineteenEightyFour\NinteenEightyWoo\Rest\Settings;
use WP_REST_Request;
use WP_UnitTest_Factory_For_User;
use WP_User;

use function PHPUnit\Framework\assertEquals;

#[TestDox( 'The Rest Settings JSON API endpoint class' )]
final class RestSettingstest extends TestCase {
	/**
	 * The WordPress administrator user
	 *
	 * The WP_User object representing the admin user we are using for testing
	 *
	 * @var WP_User
	 */
	public WP_User $admin_user;

	/**
	 * The WordPress admin user's ID
	 *
	 * The ID of the admin user we are using for testing
	 *
	 * @var int
	 */
	public int $admin_user_id;

	/**
	 * Valid example post body
	 *
	 * A PHP object representing a valid JSON request body for the endpoint.
	 *
	 * @var array
	 */
	public array $valid_post_body;

	public function setUp(): void {
		$settings = new Settings();

		do_action( 'rest_api_init' );

		$this->valid_post_body = [
			'api_key'         => 'MwmTtCTfhVQvlEOKCqFxfRAuPYHgy17E',
			'payment_methods' => [
				[
					'woo_id'  => 'bacs',
					'dk_id'   => 10,
					'dk_name' => 'Direct bank transfer',
				],
			],
		];

		$user_factory     = new WP_UnitTest_Factory_For_User();
		$this->admin_user = $user_factory->create_and_get();
		$this->admin_user->add_role( 'administrator' );
		$this->admin_user->remove_role( 'subscriber' );

		$this->admin_user_id = $this->admin_user->ID;
	}

	public function tearDown(): void {
		wp_delete_user( $this->admin_user );
	}

	#[TestDox( 'creates the NinteenEightyWoo/v1 namespace in the WP REST API' )]
	public function testNamespaceExsists(): void {
		$request  = new WP_REST_Request( 'GET', '/NinteenEightyWoo/v1' );
		$response = rest_do_request( $request );

		assertEquals( 200, $response->status );
	}

	#[TestDox( 'protects the settings endpoint from outside requests' )]
	public function tesEndpointIsSecureFromOutsiders(): void {
		// We're assuming an external request here, so we're not using a nonce value.
		$request  = new WP_REST_Request( 'POST', '/NinteenEightyWoo/v1/settings' );
		$response = rest_do_request( $request );

		assertEquals( 401, $response->status );
	}

	#[TestDox( 'processes a valid settings endpoint when an admin user makes that request' )]
	public function testEndpointProcessesValidRequest(): void {
		// Start by setting the current user to our admin user and creating a nonce
		// for that user for the REST API request.
		wp_set_current_user( $this->admin_user_id );
		$request = new WP_REST_Request( 'POST', '/NinteenEightyWoo/v1/settings' );

		$request->add_header( 'X-WP-Nonce', wp_create_nonce( 'wp_rest' ) );
		$request->set_body( wp_json_encode( $this->valid_post_body ) );

		$response = rest_do_request( $request );

		assertEquals( 200, $response->status );
	}

	#[TestDox( 'rejects an authenticated request when the POST body is invalid' )]
	public function testEndpointRejectsInvalidTypeRequest(): void {
		wp_set_current_user( $this->admin_user_id );
		$string_request = new WP_REST_Request(
			'POST',
			'/NinteenEightyWoo/v1/settings'
		);

		$string_request->add_header( 'X-WP-Nonce', wp_create_nonce( 'wp_rest' ) );
		$string_request->set_body( wp_json_encode( 'This string is invalid' ) );
		$string_response = rest_do_request( $string_request );
		assertEquals( 400, $string_response->status );

		$object_request = new WP_REST_Request( 'POST', '/NinteenEightyWoo/v1/settings' );
		$object_request->add_header( 'X-WP-Nonce', wp_create_nonce( 'wp_rest' ) );
		$object_request->set_body(
			wp_json_encode(
				[ 'foo' => 'This is an object, but has none of the required keys.' ]
			)
		);
		$object_response = rest_do_request( $object_request );
		assertEquals( 400, $object_response->status );

		$api_key_unset_request = new WP_REST_Request( 'POST', '/NinteenEightyWoo/v1/settings' );
		$api_key_unset_request->add_header( 'X-WP-Nonce', wp_create_nonce( 'wp_rest' ) );
		$api_key_unset_request->set_body(
			wp_json_encode(
				array( 'payment_methods' => $this->valid_post_body['payment_methods'] )
			)
		);
		$api_key_unset_response = rest_do_request( $api_key_unset_request );
		assertEquals( 400, $api_key_unset_response->status );
	}
}
