<?php

declare(strict_types = 1);

namespace NineteenEightyFour\NineteenEightyWoo\Hooks;

use WP_Post;
use WP_REST_Request;
use WC_Product;
use WC_Data;

/**
 * The RegisterPostMeta class
 *
 * Registers the post_meta values and Product props needed for the plugin to work.
 */
class RegisterUserMeta {
	/**
	 * The class constructor
	 */
	public function __construct() {
		register_meta(
			'user',
			'1984_woo_dk_customer_number',
			array(
				'type'        => 'string',
				'description' => 'The \'Number\' attribute assigned to the user in DK. This is composed of a prefix and the user ID attribute from WP',
				'single'      => true,
				'default'     => '',
			),
		);
	}
}
