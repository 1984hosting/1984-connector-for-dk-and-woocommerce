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

		register_post_meta(
			'order',
			'1984_woo_dk_invoice_number',
			array(
				'type'        => 'string',
				'description' => 'The invoice number assigned to the order in DK',
				'single'      => true,
			),
		);

		add_action(
			'save_post_product',
			array( __CLASS__, 'set_default_product_props' ),
			10,
			3
		);

		add_action(
			'woocommerce_rest_insert_product_object',
			array( __CLASS__, 'set_default_product_props_in_rest' ),
			10,
			3
		);
	}

	/**
	 * Set default product pops on creation
	 *
	 * Products are created and receive a database ID as soon as the user opens
	 * the traditional "add new" sidebar menu item. As that happens, we set the
	 * `manage_stock`
	 *
	 * @param int     $id The Post ID.
	 * @param WP_Post $post The post object (unused).
	 * @param bool    $update False if the post is being created, true if updating.
	 */
	public static function set_default_product_props(
		int $id,
		WP_Post $post,
		bool $update
	): void {
		if ( false === $update ) {
			$product = new WC_Product( $id );
			$product->set_manage_stock( true );
			$product->save();
		}
	}

	/**
	 * Set default product props when a product is created via the JSON API
	 *
	 * For some reason, we need to call a separate hook from save_post_product
	 * when the new block-ish WooCommerce Product Form (still in beta) is opened
	 * as a POST call to the WC Product REST endpoint is made.
	 *
	 * @param WC_Data         $object the WC_Data object, representing the product.
	 * @param WP_REST_Request $request The REST request (unused).
	 * @param bool            $creating True if the post is being created, false if not.
	 */
	public static function set_default_product_props_in_rest(
		WC_Data $object,
		WP_REST_Request $request,
		bool $creating
	): void {
		if ( true === $creating ) {
			$product = new WC_Product( $object->get_id() );
			$product->set_manage_stock( true );
			$product->save();
		}
	}
}
