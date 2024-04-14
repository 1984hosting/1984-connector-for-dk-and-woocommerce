<?php

declare(strict_types = 1);

namespace NineteenEightyFour\NineteenEightyWoo\Export;

use NineteenEightyFour\NineteenEightyWoo\Service\DKApiRequest;
use NineteenEightyFour\NineteenEightyWoo\Config;
use WC_Product;
use WP_Error;

/**
 * The Inventory Export class
 *
 * Facilitates the use of the stock count and inventory features in WooCommerce
 * and upstream sync of inventory data to DK.
 */
class Inventory {
	/**
	 * Add or update the inventory quantity for a product in DK
	 *
	 * This also sets the correct product metadata so that we know that the
	 * inventory is being tracked in DK.
	 *
	 * @param WC_Product $product The WooCommerce product.
	 *
	 * @return bool|WP_Error True on success, false if connection was
	 *                       established but the request was rejected, WC_Error
	 *                       if there was a connection error.
	 */
	public static function add_or_update_count_in_dk(
		WC_Product $product
	): string|bool|WP_Error {
		$api_request  = new DKApiRequest();
		$request_body = self::to_journal_body( $product );

		if ( true === self::get_inventory_is_in_dk( $product ) ) {
			$path = '/product/register/Inventorying/';
		} else {
			$path = '/product/register/journal/';
		}

		$result = $api_request->request_result(
			$path,
			wp_json_encode( $request_body ),
		);

		if ( $result instanceof WP_Error ) {
			return $result;
		}

		if ( 200 !== $result->response_code ) {
			return false;
		}

		self::set_inventory_is_in_dk( $product );

		return true;
	}

	/**
	 * Prepare a POST request body for a product inventory request
	 *
	 * @param WC_Product $product The WooCommerce product.
	 *
	 * @return array An associative array representing the JSON object to be
	 *               sent to the DK API.
	 */
	public static function to_journal_body( WC_Product $product ): array {
		$description = __(
			'Inventory registration in WooCommerce',
			'NineteenEightyWoo'
		);

		$line = self::to_journal_line( $product );

		return array(
			'Description' => $description,
			'Lines'       => array( $line ),
		);
	}

	/**
	 * Prepare a line element for the product inventory request
	 *
	 * @param WC_Product $product The WooCommerce product.
	 *
	 * @return array An associative array representing the Line JSON object
	 *               portion to be sent to DK.
	 */
	public static function to_journal_line( WC_Product $product ): array {
		return array(
			'ItemCode'  => $product->get_sku(),
			'Warehouse' => Config::get_default_warehouse(),
			'Quantity'  => $product->get_stock_quantity(),
		);
	}

	/**
	 * Set the product metadata for indicating the the inventory for a product is being tracked in DK
	 *
	 * @param WC_Product $product The WooCommerce product.
	 */
	public static function set_inventory_is_in_dk( WC_Product $product ): void {
		$product->update_meta_data( '1984_woo_dk_inventory_is_in_dk', true );
	}

	/**
	 * Check if a product is being tracked in DK according to WooCommerce metadata
	 *
	 * @param WC_Product $product The WooCommerce product.
	 */
	public static function get_inventory_is_in_dk( WC_Product $product ): bool {
		return (bool) $product->get_meta( '1984_woo_dk_inventory_is_in_dk' );
	}
}
