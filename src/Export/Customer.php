<?php

declare(strict_types = 1);

namespace NineteenEightyFour\NineteenEightyWoo\Export;

use NineteenEightyFour\NineteenEightyWoo\Config;

use WC_Customer;

/**
 * The Customer Export class
 *
 * Provides functions for exporting orders to a valid HTTP body for the DK API.
 *
 * @see https://api.dkplus.is/swagger/ui/index#/Sales32Invoice
 **/
class Customer {
	/**
	 * Export a WooCommerce customer to a DK API customer POST body
	 *
	 * @param WC_Customer $customer The WooCommerce customer object.
	 */
	public static function to_dk_customer_body( WC_Customer $customer ): array {
		$full_name = implode(
			' ',
			array(
				$customer->get_billing_first_name(),
				$customer->get_billing_last_name(),
			)
		);

		$customer_props = array(
			'Number'   => Config::get_customer_number_prefix() . $customer->get_id(),
			'Name'     => $full_name,
			'Address1' => $customer->get_billing_address_1(),
			'Address2' => $customer->get_billing_address_2(),
			'City'     => $customer->get_billing_city(),
			'ZipCode'  => $customer->get_billing_postcode(),
			'Phone'    => $customer->get_billing_phone(),
			'Email'    => $customer->get_billing_email(),
		);

		if ( get_option( 'woocommerce_default_country' ) !== $customer->get_billing_country() ) {
			$customer_props['CountryCode'] = $customer->get_billing_country();
		}

		return $customer_props;
	}

	/**
	 * Export a WooCommerce customer to a valid HTTP body based on their Id
	 *
	 * @param int $customer_id The customer ID.
	 */
	public static function id_to_dk_customer_body( int $customer_id ): array {
		$customer = new WC_Customer( $customer_id );
		return self::to_dk_customer_body( $customer );
	}
}
