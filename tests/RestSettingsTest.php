<?php

declare(strict_types = 1);

namespace NineteenEightyFour\NineteenEightyWoo\Tests;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\TestDox;
use NineteenEightyFour\NineteenEightyWoo\Config;
use NineteenEightyFour\NineteenEightyWoo\Rest\Settings;
use WP_REST_Request;
use WP_UnitTest_Factory_For_User;
use WP_User;

use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertIsString;

#[TestDox( 'The Rest Settings JSON API endpoint class' )]
final class RestSettingstest extends TestCase {
	const VALID_POST_BODY = array(
		'api_key'                => '3541031f-baf2-4737-a7e8-c66396e5a5e3',
		'customer_number_prefix' => 'WCN',
		'payment_methods'        => array(
			array(
				'woo_id'  => 'bacs',
				'dk_id'   => 10,
				'dk_mode' => 'GKR'
			),
		),
	);

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
		new Settings();

		do_action( 'rest_api_init' );

		$user_factory     = new WP_UnitTest_Factory_For_User();
		$this->admin_user = $user_factory->create_and_get();
		$this->admin_user->add_role( 'administrator' );
		$this->admin_user->remove_role( 'subscriber' );

		$this->admin_user_id = $this->admin_user->ID;
	}

	public function tearDown(): void {
		wp_delete_user( $this->admin_user );
	}

	#[TestDox( 'creates the NineteenEightyWoo/v1 namespace in the WP REST API' )]
	public function testNamespaceExsists(): void {
		$request  = new WP_REST_Request(
			'GET',
			'/NineteenEightyWoo/v1'
		);
		$response = rest_do_request( $request );

		assertEquals( 200, $response->status );
	}

	#[TestDox( 'protects the settings endpoint from outside requests' )]
	public function tesEndpointIsSecureFromOutsiders(): void {
		// We're assuming an external request here, so we're not using a nonce value.
		$request  = new WP_REST_Request(
			'POST',
			'/NineteenEightyWoo/v1/settings'
		);
		$response = rest_do_request( $request );

		assertEquals( 401, $response->status );
	}

	#[TestDox( 'processes a valid settings endpoint when an admin user makes that request' )]
	public function testEndpointProcessesValidRequest(): void {
		Config::set_dk_api_key( '3541031f-baf2-4737-a7e8-c66396e5a5e3' );

		// Start by setting the current user to our admin user and creating a nonce
		// for that user for the REST API request.
		wp_set_current_user( $this->admin_user_id );
		$request = new WP_REST_Request(
			'POST',
			'/NineteenEightyWoo/v1/settings'
		);

		$request->add_header( 'X-WP-Nonce', wp_create_nonce( 'wp_rest' ) );
		$request->set_body( wp_json_encode( self::VALID_POST_BODY ) );

		$response = rest_do_request( $request );
		assertEquals( 200, $response->status );

		$api_key_option = get_option( '1984_woo_dk_api_key' );
		assertIsString( $api_key_option );
	}
}
