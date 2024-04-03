<?php

declare(strict_types = 1);

namespace NineteenEightyFour\NineteenEightyWoo\Hooks;

use NineteenEightyFour\NineteenEightyWoo\Export\Customer as ExportCustomer;

use WC_Customer;
use WP_Error;

/**
 * The Update User class
 *
 * Updates customer information in DK when a user registers or a user profile is
 * updated.
 */
class UpdateUser {
	public function __construct() {
		/**
		 * The class constructor
		 */
		add_action(
			'wp_update_user',
			array( __CLASS__, 'create_or_update_wp_user_in_dk' ),
			10,
			1
		);

		add_action(
			'user_register',
			array( __CLASS__, 'create_or_update_wp_user_in_dk' ),
			10,
			1
		);
	}

	/**
	 * Create or update user in DK
	 *
	 * @param int $user_id The customer's WP user id.
	 */
	public static function create_or_update_wp_user_in_dk(
		int $user_id
	): bool|WP_Error {
		$customer = new WC_Customer( $user_id );

		if ( 0 === $customer->get_id() ) {
			return false;
		}

		if ( true === ExportCustomer::has_dk_customer_number( $customer ) ) {
			return ExportCustomer::update_in_dk( $customer );
		} else {
			return ExportCustomer::create_in_dk( $customer );
		}
	}
}
