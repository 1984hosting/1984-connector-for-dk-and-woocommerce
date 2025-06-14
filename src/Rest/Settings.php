<?php

declare(strict_types = 1);

namespace NineteenEightyFour\NineteenEightyWoo\Rest;

use NineteenEightyFour\NineteenEightyWoo\Config;
use NineteenEightyFour\NineteenEightyWoo\Currency;
use NineteenEightyFour\NineteenEightyWoo\Import\Products as ImportProducts;
use NineteenEightyFour\NineteenEightyWoo\Import\Currencies as ImportCurrencies;
use WP_Error;
use WP_REST_Request;
use WP_REST_Response;
use NineteenEightyFour\NineteenEightyWoo\Opis\JsonSchema\Validator;
use NineteenEightyFour\NineteenEightyWoo\Service\DKApiRequest;

/**
 * The REST API Settings endpoint class
 *
 * Handles the `NineteenEightyWoo/v1/settings/` REST endpoint.
 */
class Settings {
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
		$validation = $validator->validate( $rest_json, self::json_schema() );

		if ( $validation->hasError() ) {
			return new WP_Error(
				'bad_request',
				'Bad Request',
				array( 'status' => '400' ),
			);
		}

		if ( property_exists( $rest_json, 'api_key' ) ) {
			Config::set_dk_api_key( $rest_json->api_key );
		}

		$authentication_request = new DKApiRequest();
		$company_result         = $authentication_request->get_result( '/company/' );

		if ( $company_result instanceof WP_Error ) {
			return new WP_Error(
				'bad_gateway',
				'Bad Gateway',
				array( 'status' => '502' ),
			);
		}

		if ( $company_result->response_code !== 200 ) {
			return new WP_Error(
				'unauthorized',
				'Unauthorized',
				array( 'status' => '401' ),
			);
		}

		if ( $company_result->data->General->CurrencyEnabled ) {
			Config::set_dk_currency( $company_result->data->General->DefaultCurrency );
		} else {
			Config::set_dk_currency( Currency::BASE_CURRENCY );
		}

		ImportCurrencies::save_all_from_dk();

		if ( property_exists( $rest_json, 'product_price_sync' ) ) {
			Config::set_product_price_sync( $rest_json->product_price_sync );
		}

		if ( property_exists( $rest_json, 'product_quantity_sync' ) ) {
			Config::set_product_quantity_sync( $rest_json->product_quantity_sync );
		}

		if ( property_exists( $rest_json, 'product_name_sync' ) ) {
			Config::set_product_name_sync( $rest_json->product_name_sync );
		}

		if ( property_exists( $rest_json, 'import_nonweb_products' ) ) {
			Config::set_import_nonweb_products(
				$rest_json->import_nonweb_products
			);
		}

		if ( property_exists( $rest_json, 'delete_inactive_products' ) ) {
			Config::set_delete_inactive_products(
				$rest_json->delete_inactive_products
			);
		}

		if ( property_exists( $rest_json, 'ledger_code_standard' ) ) {
			Config::set_ledger_code(
				'standard',
				$rest_json->ledger_code_standard
			);
		}

		if ( property_exists( $rest_json, 'ledger_code_standard_purchase' ) ) {
			Config::set_ledger_code(
				'standard_purchase',
				$rest_json->ledger_code_standard_purchase
			);
		}

		if ( property_exists( $rest_json, 'ledger_code_reduced' ) ) {
			Config::set_ledger_code(
				'reduced',
				$rest_json->ledger_code_reduced
			);
		}

		if ( property_exists( $rest_json, 'ledger_code_reduced_purchase' ) ) {
			Config::set_ledger_code(
				'reduced_purchase',
				$rest_json->ledger_code_reduced_purchase
			);
		}

		if ( property_exists( $rest_json, 'shipping_sku' ) ) {
			Config::set_shipping_sku( $rest_json->shipping_sku );
		}

		if ( property_exists( $rest_json, 'cost_sku' ) ) {
			Config::set_cost_sku( $rest_json->cost_sku );
		}

		if ( property_exists( $rest_json, 'default_sales_person' ) ) {
			Config::set_default_sales_person_number(
				$rest_json->default_sales_person
			);
		}

		if ( property_exists( $rest_json, 'default_kennitala' ) ) {
			Config::set_default_kennitala( $rest_json->default_kennitala );
		}

		if ( property_exists( $rest_json, 'enable_kennitala' ) ) {
			Config::set_kennitala_classic_field_enabled(
				$rest_json->enable_kennitala
			);
		}

		if ( property_exists( $rest_json, 'customer_requests_kennitala_invoice' ) ) {
			Config::set_customer_requests_kennitala_invoice(
				$rest_json->customer_requests_kennitala_invoice
			);
		}

		if ( property_exists( $rest_json, 'make_invoice_if_kennitala_is_set' ) ) {
			Config::set_make_invoice_if_kennitala_is_set(
				$rest_json->make_invoice_if_kennitala_is_set
			);
		}

		if ( property_exists( $rest_json, 'make_invoice_if_kennitala_is_missing' ) ) {
			Config::set_make_invoice_if_kennitala_is_missing(
				$rest_json->make_invoice_if_kennitala_is_missing
			);
		}

		if ( property_exists( $rest_json, 'email_invoice' ) ) {
			Config::set_email_invoice(
				$rest_json->email_invoice
			);
		}

		if ( property_exists( $rest_json, 'domestic_customer_ledger_code' ) ) {
			Config::set_domestic_customer_ledger_code(
				$rest_json->domestic_customer_ledger_code
			);
		}

		if ( property_exists( $rest_json, 'international_customer_ledger_code' ) ) {
			Config::set_international_customer_ledger_code(
				$rest_json->international_customer_ledger_code
			);
		}

		if ( property_exists( $rest_json, 'enable_kennitala_in_block' ) ) {
			Config::set_kennitala_block_field_enabled(
				$rest_json->enable_kennitala_in_block
			);
		}

		foreach ( $rest_json->payment_methods as $p ) {
			Config::set_payment_mapping(
				$p->woo_id,
				$p->dk_id,
				$p->dk_mode,
				$p->dk_term,
			);
		}

		if ( property_exists( $rest_json, 'use_attribute_description' ) ) {
			Config::set_use_attribute_description(
				$rest_json->use_attribute_description
			);
		}

		if ( property_exists( $rest_json, 'use_attribute_value_description' ) ) {
			Config::set_use_attribute_value_description(
				$rest_json->use_attribute_value_description
			);
		}

		if (
			property_exists( $rest_json, 'fetch_products' ) &&
			$rest_json->fetch_products
		) {
			ImportProducts::save_all_from_dk();
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
		return json_decode( self::json_schema() );
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
		$validation = $validator->validate( $rest_json, self::json_schema() );

		if ( $validation->hasError() ) {
			return false;
		}

		return true;
	}

	/**
	 * Get the JSON schema for POST requests to the settings endpoint
	 */
	public static function json_schema(): string {
		// phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
		return file_get_contents(
			dirname( __DIR__, 2 ) . '/json_schemas/rest/settings.json'
		);
	}
}
