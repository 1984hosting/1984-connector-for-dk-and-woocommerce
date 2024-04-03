<?php

declare(strict_types = 1);

namespace NineteenEightyFour\NineteenEightyWoo\Export;

use NineteenEightyFour\NineteenEightyWoo\Service\DKApiRequest;
use NineteenEightyFour\NineteenEightyWoo\Export\Customer;
use NineteenEightyFour\NineteenEightyWoo\Config;
use NineteenEightyFour\NineteenEightyWoo\Hooks\KennitalaField;
use WC_Order;
use WC_Product_Variation;
use WC_Order_Item_Product;
use WP_Error;

/**
 * The order Export class
 *
 * Provides functions for exporting WooCommerce orders as orders to the DK API.
 **/
class Order {
	/**
	 * Create an order record in DK based on a WooCommerce order
	 *
	 * @param WC_Order $order The WooCommerce order.
	 *
	 * @return int|false|WP_Error An integer representing the order number on
	 *                            success, false of connection was established
	 *                            but there was an error, or WP_Error on
	 *                            connection error.
	 */
	public static function create_in_dk( WC_Order $order ): int|false|WP_Error {
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

		return $result->data->OrderNumber;
	}

	/**
	 * Check if an order is in DK
	 *
	 * @param WC_Order $order The WooCommerce order.
	 *
	 * @return bool|WP_Error True if an order exists in DK, false if not,
	 *                       WP_Error if here was a connection error.
	 */
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

	/**
	 * Assign a DK order number to an order
	 *
	 * @param WC_Order $order The WooCommerce order.
	 * @param int      $dk_order_number The order number.
	 */
	public static function assign_dk_order_number(
		WC_Order $order,
		int $dk_order_number
	): int {
		$order->update_meta_data(
			'1984_woo_dk_order_number',
			$dk_order_number
		);

		$order->save_meta_data();

		return $dk_order_number;
	}

	/**
	 * Get the DK order number of an order from metadata
	 *
	 * @param WC_Order $order The WooCommerce order.
	 */
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
		$order_kennitala = KennitalaField::get_kennitala_from_order( $order );

		$order_props['Customer'] = array();

		if ( 0 === $order->get_customer_id() ) {
			if ( true === empty( $order_kennitala ) ) {
				$order_props['Customer']['SSNumber'] = $order_kennitala;
			} else {
				$order_props['Customer']['SSNumber'] = Config::get_default_kennitala();
			}
		} else {
			$customer_record = Customer::id_to_dk_customer_body(
				$order->get_customer_id()
			);

			$order_props['Customer']['Number']   = $customer_record['Number'];
			$order_props['Customer']['SSNumber'] = $order_kennitala;
		}

		if ( false === empty( $order->get_billing_company() ) ) {
			$order_props['Customer']['Company'] = $order->get_billing_company();
		}

		$order_props['Customer']['Name']     = $order->get_formatted_billing_full_name();
		$order_props['Customer']['Address1'] = $order->get_billing_address_1();
		$order_props['Customer']['Address2'] = $order->get_billing_address_2();
		$order_props['Customer']['City']     = $order->get_billing_city();
		$order_props['Customer']['ZipCode']  = $order->get_billing_postcode();
		$order_props['Customer']['Phone']    = $order->get_billing_phone();
		$order_props['Customer']['Email']    = $order->get_billing_email();

		$store_location = wc_get_base_location();

		if ( $order->get_billing_country() !== $store_location['country'] ) {
			$order_props['Customer']['Country'] = $order->get_billing_country();
		}

		$order_props['Lines'] = array();

		foreach ( $order->get_items() as $key => $item ) {
			$order_item_product = new WC_Order_Item_Product( $item->get_id() );
			$product            = $order_item_product->get_product();
			$sku                = $product->get_sku();

			$order_line_item = array(
				'ItemCode' => $sku,
				'Text'     => $item->get_name(),
				'Quantity' => $item->get_quantity(),
				'Price'    => $order->get_item_total( $item ),
			);

			if ( $product instanceof WC_Product_Variation ) {
				$order_line_item['Variation'] = array();

				$variation_attributes = $product->get_variation_attributes(
					false
				);
				foreach ( $variation_attributes as $key => $v ) {
					$order_line_item['Variation'][] = array( "$key" => "$v" );
				}
			}

			$order_props['Lines'][] = $order_line_item;
		}

		if ( 0 < count( $order->get_fees() ) ) {
			foreach ( $order->get_fees() as $fee ) {
				$unit_price = (
					(float) $fee->get_total() -
					(float) $fee->get_total_tax()
				);

				$sanitized_name = str_replace( '&nbsp;', '', $fee->get_name() );

				$order_props['Lines'][] = array(
					'ItemCode'         => Config::get_cost_sku(),
					'Text'             => __( 'Fee', 'NineteenEightyWoo' ),
					'Text2'            => $sanitized_name,
					'Quantity'         => 1,
					'UnitPrice'        => $unit_price,
					'UnitPriceWithTax' => (float) $fee->get_total(),
				);
			}
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
