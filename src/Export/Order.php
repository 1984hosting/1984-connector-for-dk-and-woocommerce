<?php

declare(strict_types = 1);

namespace NineteenEightyFour\NineteenEightyWoo\Export;

use Automattic\WooCommerce\Admin\API\Customers;
use NineteenEightyFour\NineteenEightyWoo\Brick\Math\BigDecimal;
use NineteenEightyFour\NineteenEightyWoo\Service\DKApiRequest;
use NineteenEightyFour\NineteenEightyWoo\Config;
use NineteenEightyFour\NineteenEightyWoo\Hooks\KennitalaField;
use NineteenEightyFour\NineteenEightyWoo\Helpers\Order as OrderHelper;
use NineteenEightyFour\NineteenEightyWoo\Export\Customer as ExportCustomer;
use NineteenEightyFour\NineteenEightyWoo\Import\ProductVariations;
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
		$kennitala = OrderHelper::get_kennitala( $wc_order );

		$order_props     = array();
		$recipient_array = array();
		$customer_array  = array( 'Number' => $kennitala );

		$order_props['Reference'] = 'WC-' . $wc_order->get_id();

		$recipient_array['Name']     = $wc_order->get_formatted_billing_full_name();
		$recipient_array['Address1'] = $wc_order->get_shipping_address_1();
		$recipient_array['Address2'] = $wc_order->get_shipping_address_2();
		$recipient_array['City']     = $wc_order->get_shipping_city();
		$recipient_array['ZipCode']  = $wc_order->get_shipping_postcode();
		$recipient_array['Phone']    = $wc_order->get_shipping_phone();

		$store_location = wc_get_base_location();

		if ( $wc_order->get_shipping_country() !== $store_location['country'] ) {
			$recipient_array['Country'] = $wc_order->get_shipping_country();
		}

		$order_props['Customer'] = $customer_array;
		$order_props['Receiver'] = $recipient_array;

		$order_props['Currency'] = $wc_order->get_currency();

		$order_props['Lines'] = array();

		foreach ( $wc_order->get_items() as $key => $item ) {
			$order_item_product = new WC_Order_Item_Product( $item->get_id() );
			$product_id         = $order_item_product->get_product_id();
			$product            = wc_get_product( $product_id );
			$sku                = $product->get_sku();

			$order_line_item = array(
				'ItemCode'     => $sku,
				'Text'         => $item->get_name(),
				'Quantity'     => $item->get_quantity(),
				'Price'        => $wc_order->get_item_total(
					$item,
					wc_prices_include_tax()
				),
				'IncludingVAT' => wc_prices_include_tax(),
			);

			$origin       = $product->get_meta( '1984_dk_woo_origin', true, 'edit' );
			$variant_code = $product->get_meta( '1984_dk_woo_variant_code', true, 'edit' );
			$variation    = wc_get_product( $order_item_product->get_variation_id() );

			if (
				'product_variation' === $origin &&
				false !== $variation
			) {
				$attributes = ProductVariations::attributes_to_woocommerce_variation_attributes( $variant_code );

				$variation_attributes = array_keys( $attributes );

				$variation_line = array();

				if ( key_exists( 0, $variation_attributes ) ) {
					$variation_line['Code'] = $variation->get_attribute( $variation_attributes[0] );
				}

				if ( key_exists( 1, $variation_attributes ) ) {
					$variation_line['Code2'] = $variation->get_attribute( $variation_attributes[1] );;
				}

				$variation_line['Quantity'] = $item->get_quantity();

				$order_line_item['Variations'] = array( (object) $variation_line );
			}

			$order_props['Lines'][] = $order_line_item;
		}

		if ( 0 < count( $wc_order->get_fees() ) ) {
			foreach ( $wc_order->get_fees() as $fee ) {
				$sanitized_name = str_replace( '&nbsp;', '', $fee->get_name() );

				$order_props['Lines'][] = array(
					'ItemCode'     => Config::get_cost_sku(),
					'Text'         => __( 'Fee', '1984-dk-woo' ),
					'Text2'        => $sanitized_name,
					'Price'        => $fee->get_total(),
					'IncludingVAT' => false,
				);
			}
		}

		if ( 0 < count( $wc_order->get_shipping_methods() ) ) {
			foreach ( $wc_order->get_shipping_methods() as $shipping_method ) {
				if ( 0.0 === floatval( $shipping_method->get_total() ) ) {
					continue;
				}

				$shipping_price_with_tax = (
					$shipping_method->get_total() +
					$shipping_method->get_total_tax()
				);

				$order_props['Lines'][] = array(
					'ItemCode'     => Config::get_shipping_sku(),
					'Text'         => __( 'Shipping', '1984-dk-woo' ),
					'Text2'        => $shipping_method->get_method_title(),
					'Quantity'     => 1,
					'Price'        => $shipping_method->get_total(),
					'IncludingVAT' => $shipping_price_with_tax,
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
		$order_props['TotalAmountWithTax'] = $total->toFloat();

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
		$billing_kennitala = OrderHelper::get_kennitala( $wc_order );

		if ( ! empty( $billing_kennitala ) ) {
			return $billing_kennitala;
		}

		return Config::get_default_kennitala();
	}
}
