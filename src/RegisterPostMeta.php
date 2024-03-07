<?php

declare(strict_types = 1);

namespace NineteenEightyFour\NineteenEightyWoo;

/**
 * The RegisterPostMeta class
 *
 * Registers the post_meta values needed for the plugin.
 */
class RegisterPostMeta {
	/**
	 * The RegisterPostMeta constructor
	 *
	 * Registers the post_meta values.
	 */
	public function __construct() {
		register_post_meta(
			'product',
			'1984_woo_dk_price_sync',
			array(
				'type'        => 'boolean',
				'description' => 'Wether to enable price sync with DK',
				'single'      => true,
				'default'     => true,
			),
		);

		register_post_meta(
			'product',
			'1984_woo_dk_stock_sync',
			array(
				'type'        => 'boolean',
				'description' => 'Wether to enable stock sync with DK',
				'single'      => true,
				'default'     => true,
			),
		);
	}
}
