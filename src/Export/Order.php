<?php

declare(strict_types = 1);

namespace NineteenEightyFour\NineteenEightyWoo\Export;

use NineteenEightyFour\NineteenEightyWoo\Service\DKApiRequest;
use NineteenEightyFour\NineteenEightyWoo\Export\Customer as ExportCustomer;
use NineteenEightyFour\NineteenEightyWoo\Config;
use NineteenEightyFour\NineteenEightyWoo\Hooks\KennitalaField;
use WC_Customer;
use WC_Order;
use WC_Product_Variation;
use WC_Order_Item_Product;
use WP_Error;

/**
 * The Order Export class
 *
 * Provides functions for exporting WooCommerce orders as orders to the DK API.
 **/
class Order {
	/**
	 * Create an order record in DK based on a WooCommerce order
	 *
	 * @param WC_Order $wc_order The WooCommerce order.
	 *
	 * @return int|false|WP_Error An integer representing the order number on
	 *                            success, false of connection was established
	 *                            but there was an error, or WP_Error on
	 *                            connection error.
	 */
	public static function create_in_dk(
		WC_Order $wc_order
	): int|false|WP_Error {
		$api_request  = new DKApiRequest();
		$request_body = self::to_dk_order_body( $wc_order );

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

		self::assign_dk_order_number( $wc_order, $result->data->OrderNumber );

		return $result->data->OrderNumber;
	}

	/**
	 * Check if an wc_order is in DK
	 *
	 * @param WC_Order $wc_order The WooCommerce order.
	 *
	 * @return bool|WP_Error True if an order exists in DK, false if not,
	 *                       WP_Error if here was a connection error.
	 */
	public static function is_in_dk( WC_Order $wc_order ): bool|WP_Error {
		if ( true === empty( self::get_dk_order_number( $wc_order ) ) ) {
			return false;
		}

		$api_request = new DKApiRequest();

		$result = $api_request->get_result(
			'/Sales/Order/' . self::get_dk_order_number( $wc_order )
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
	 * @param WC_Order $wc_order The WooCommerce order.
	 * @param int      $dk_order_number The order number.
	 */
	public static function assign_dk_order_number(
		WC_Order $wc_order,
		int $dk_order_number
	): int {
		$wc_order->update_meta_data(
			'1984_woo_dk_order_number',
			$dk_order_number
		);

		$wc_order->save_meta_data();

		return $dk_order_number;
	}

	/**
	 * Get the DK order number of an order from metadata
	 *
	 * @param WC_Order $wc_order The WooCommerce order.
	 */
	public static function get_dk_order_number(
		WC_Order $wc_order
	): int|string {
		return $wc_order->get_meta(
			'1984_woo_dk_order_number'
		);
	}

	/**
	 * Export a WooCommerce wc_order to a DK API wc_order POST body
	 *
	 * @param WC_Order $wc_order The WooCommerce order object.
	 */
	public static function to_dk_order_body( WC_Order $wc_order ): array {
		$customer_array  = array();
		$recipient_array = array();

		$customer_array['Number']   = self::assume_dk_customer_number( $wc_order );
		$customer_array['Name']     = $wc_order->get_formatted_billing_full_name();
		$customer_array['Address1'] = $wc_order->get_billing_address_1();
		$customer_array['Address2'] = $wc_order->get_billing_address_2();
		$customer_array['City']     = $wc_order->get_billing_city();
		$customer_array['ZipCode']  = $wc_order->get_billing_postcode();
		$customer_array['Phone']    = $wc_order->get_billing_phone();
		$customer_array['Email']    = $wc_order->get_billing_email();

		$recipient_array['Name']     = $wc_order->get_formatted_billing_full_name();
		$recipient_array['Address1'] = $wc_order->get_shipping_address_1();
		$recipient_array['Address2'] = $wc_order->get_shipping_address_2();
		$recipient_array['City']     = $wc_order->get_shipping_city();
		$recipient_array['ZipCode']  = $wc_order->get_shipping_postcode();
		$recipient_array['Phone']    = $wc_order->get_shipping_phone();

		$store_location = wc_get_base_location();

		if ( $wc_order->get_billing_country() !== $store_location['country'] ) {
			$customer_array['Country'] = $wc_order->get_billing_country();
		}

		if ( $wc_order->get_shipping_country() !== $store_location['country'] ) {
			$recipient_array['Country'] = $wc_order->get_shipping_country();
		}

		$order_props['Customer']    = $customer_array;
		$order_props['ItemReciver'] = $recipient_array;

		$order_props['Lines'] = array();

		foreach ( $wc_order->get_items() as $key => $item ) {
			$order_item_product = new WC_Order_Item_Product( $item->get_id() );
			$product            = $order_item_product->get_product();
			$sku                = $product->get_sku();

			$order_line_item = array(
				'ItemCode' => $sku,
				'Text'     => $item->get_name(),
				'Quantity' => $item->get_quantity(),
				'Price'    => $wc_order->get_item_total( $item ),
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

		if ( 0 < count( $wc_order->get_fees() ) ) {
			foreach ( $wc_order->get_fees() as $fee ) {
				$fee_total     = BigDecimal::of( $fee->get_total() );
				$fee_total_tax = BigDecimal::of( $fee->get_total_tax() );
				$fee_price     = $fee_total->minus( $fee_total_tax );

				$sanitized_name = str_replace( '&nbsp;', '', $fee->get_name() );

				$order_props['Lines'][] = array(
					'ItemCode'         => Config::get_cost_sku(),
					'Text'             => __( 'Fee', '1984-dk-woo' ),
					'Text2'            => $sanitized_name,
					'Quantity'         => 1,
					'UnitPrice'        => $fee_price->toFloat(),
					'UnitPriceWithTax' => $fee->get_total(),
				);
			}
		}

		if ( 0 < count( $wc_order->get_shipping_methods() ) ) {
			foreach ( $wc_order->get_shipping_methods() as $shipping_method ) {
				$shipping_total     = BigDecimal::of(
					$shipping_method->get_total()
				);
				$shipping_total_tax = BigDecimal::of(
					$shipping_method->get_total_tax()
				);

				$unit_price = $shipping_total->minus( $shipping_total_tax );

				if ( 0.0 === $unit_price->toFloat() ) {
					continue;
				}

				$order_props['Lines'][] = array(
					'ItemCode'         => Config::get_shipping_sku(),
					'Text'             => __( 'Shipping', '1984-dk-woo' ),
					'Text2'            => $shipping_method->get_method_title(),
					'Quantity'         => 1,
					'UnitPrice'        => $unit_price->toFloat(),
					'UnitPriceWithTax' => (float) $shipping_method->get_total(),
				);
			}
		}

		if ( 0 < $wc_order->get_total_discount() ) {
			$order_props['Discount'] = $wc_order->get_total_discount();
		}

		$total        = BigDecimal::of( $wc_order->get_total() );
		$total_tax    = BigDecimal::of( $wc_order->get_total_tax() );
		$total_amount = $total->minus( $total_tax );

		$order_props['TotalAmount']        = $total_amount->toFloat();
		$order_props['TotalAmountWithTax'] = $total_tax->toFloat();

		return $order_props;
	}

	/**
	 * Export a WooCommerce wc_order to a valid HTTP body based on its Id.
	 *
	 * @param int $order_id The Order ID.
	 */
	public static function id_to_dk_order_body( int $order_id ): array {
		$order_object = new WC_Order( $order_id );
		return self::to_dk_order_body( $order_object );
	}

	/**
	 * Assume the DK customer number for an order
	 *
	 * Starts by checking if a billing kennitala has been set for the order and
	 * uses that as the main kennitala.
	 *
	 * If it can't find a billing kennitala, it will check if the customer for
	 * the order has a kennitala and uses that as the customer number in DK.
	 *
	 * If that does not work out, the default kennitala will be used for the
	 * customer.
	 *
	 * @see NineteenEightyFour\NineteenEightyWoo\Config::get_default_kennitala()
	 *
	 * @param WC_Order $wc_order The WooCommerce order.
	 *
	 * @return string The kennitala or customer number used.
	 */
	public static function assume_dk_customer_number(
		WC_Order $wc_order
	): string {
		$billing_kennitala = KennitalaField::get_kennitala_from_order(
			$wc_order
		);

		$customer_id = $wc_order->get_customer_id();

		if ( true === empty( $billing_kennitala ) ) {
			if ( 0 === $customer_id ) {
				return Config::get_default_kennitala();
			} else {
				$customer = new WC_Customer( $customer_id );
				return ExportCustomer::assume_dk_customer_number( $customer );
			}
		}

		return $billing_kennitala;
	}
}
