<?php

declare(strict_types = 1);

namespace NineteenEightyFour\NineteenEightyWoo\Export;

use NineteenEightyFour\NineteenEightyWoo\Service\DKApiRequest;
use NineteenEightyFour\NineteenEightyWoo\Export\Customer;
use NineteenEightyFour\NineteenEightyWoo\Config;
use WC_Order;
use WC_Product_Variation;
use WP_User;
use WP_Error;

/**
 * The order Export class
 *
 * Provides functions for exporting WooCommerce orders as orders to the DK API.
 **/
class Order {
	public static function create_in_dk( WC_Order $order ): bool|WP_Error {
		$api_request  = new DKApiRequest();
		$request_body = self::to_dk_order_body( $order );

		$result = $api_request->request_result(
			'/Sales/Order/',
			wp_json_encode( $request_body ),
		);

		if ( $result instanceof WP_Error ) {
			return $result;
		}

		if ( 200 !== $result->response_code ) {
			return false;
		}

		self::assign_dk_order_number( $order, $result->data->OrderNumber );

		return true;
	}

	public static function is_in_dk( WC_Order $order ): bool|WP_Error {
		if ( true === empty( self::get_dk_order_number( $order ) ) ) {
			return false;
		}

		$api_request = new DKApiRequest();

		$result = $api_request->get_result(
			'/Sales/Order/' . self::get_dk_order_number( $order )
		);

		if ( $result instanceof WP_Error ) {
			return $result;
		}

		if ( 200 !== $result->response_code ) {
			return false;
		}

		return true;
	}

	public static function assign_dk_order_number(
		WC_Order $order,
		int $dk_order_number
	): int {
		$order->update_meta_data(
			'1984_woo_dk_order_number',
			$dk_order_number
		);

		$order->save();

		return $dk_order_number;
	}

	public static function get_dk_order_number(
		WC_Order $order
	): int|string {
		return $order->get_meta(
			'1984_woo_dk_order_number'
		);
	}

	/**
	 * Export a WooCommerce order to a DK API order POST body
	 *
	 * @param WC_Order $order The WooCommerce order object.
	 */
	public static function to_dk_order_body( WC_Order $order ): array {
		if ( $order->get_user() instanceof WP_User ) {
			$order_props['Customer'] = Customer::id_to_dk_customer_body(
				$order->get_customer_id()
			);
		} else {
			$order_props['Customer'] = array(
				'Number' => Config::get_guest_customer_number(),
			);
		}

		$order_props['Lines'] = array();

		foreach ( $order->get_items() as $key => $item ) {
			$product = $item->get_product();
			$sku     = $product->get_sku();

			$order_line_item = array(
				'ItemCode' => $sku,
				'Text'     => $item->get_name(),
				'Quantity' => $item->get_quantity(),
				'Price'    => $order->get_item_total( $item ),
			);

			if ( $product instanceof WC_Product_Variation ) {
				var_dump( $product->get_variation_attributes( false ) );
				$order_line_item['Variation'] = array();

				$variation_attributes = $product->get_variation_attributes(
					false
				);
				foreach ( $variation_attributes as $key => $v ) {
					$order_line_item['Variation'][] = array(
						"$key" => "$v",
					);
				}
			}

			$order_props['Lines'][] = $order_line_item;
		}

		if ( 0 < count( $order->get_shipping_methods() ) ) {
			foreach ( $order->get_shipping_methods() as $shipping_method ) {
				$unit_price = (
					(float) $shipping_method->get_total() -
					(float) $shipping_method->get_total_tax()
				);

				$order_props['Lines'][] = array(
					'ItemCode'         => Config::get_shipping_sku(),
					'Text'             => __( 'Shipping', 'NineteenEightyWoo' ),
					'Text2'            => $shipping_method->get_method_title(),
					'Quantity'         => 1,
					'UnitPrice'        => $unit_price,
					'UnitPriceWithTax' => (float) $shipping_method->get_total(),
				);
			}
		}

		if ( 0 < $order->get_total_discount() ) {
			$order_props['Discount'] = $order->get_total_discount();
		}

		$order_props['TotalAmount'] = (
			(float) $order->get_total() -
			(float) $order->get_total_tax()
		);

		$order_props['TotalAmountWithTax'] = (float) $order->get_total();

		// if ( true === $order->is_paid() ) {
		// 	$payment_mapping = Config::get_payment_mapping( $order->get_payment_method() );

		// 	$order_props['Payments'] = array(
		// 		'ID'     => $payment_mapping->dk_id,
		// 		'Name'   => $payment_mapping->dk_name,
		// 		'Amount' => $order->get_total(),
		// 	);
		// }

		return $order_props;
	}

	/**
	 * Export a WooCommerce order to a valid HTTP body based on its Id.
	 *
	 * @param int $order_id The Order ID.
	 */
	public static function id_to_dk_order_body( int $order_id ): array {
		$order = new WC_Order( $order_id );
		return self::to_dk_order_body( $order );
	}
}
