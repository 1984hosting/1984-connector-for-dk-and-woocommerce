<?php

declare(strict_types = 1);

namespace NineteenEightyFour\NineteenEightyWoo\Export;

use NineteenEightyFour\NineteenEightyWoo\Export\Customer;
use NineteenEightyFour\NineteenEightyWoo\Config;
use WC_Order;
use WC_Product;

/**
 * The Order Export class
 *
 * Provides functions for exporting orders to a valid HTTP body for the DK API.
 *
 * @see https://api.dkplus.is/swagger/ui/index#/Sales32Invoice
 **/
class Order {
	/**
	 * Export a WooCommerce order to a DK API invoice POST body
	 *
	 * @param WC_Order $order The WooCommerce order object.
	 */
	public static function to_dk_invoice_body( WC_Order $order ): array {
		$invoice_props = array(
			'Number' => Config::get_invoice_number_prefix() . $order->get_id(),
		);

		$invoice_props['Customer'] = Customer::id_to_dk_customer_body(
			$order->get_customer_id()
		);

		$invoice_props['Lines'] = array();

		foreach ( $order->get_items() as $key => $item ) {
			$product = new WC_Product( $item['product_id'] );
			$sku     = $product->get_sku();

			$invoice_line_item = array(
				'ItemCode'           => $sku,
				'Text'               => $item->get_name(),
				'Quantity'           => $item->get_quantity(),
				'UnitPrice'          => $order->get_item_total( $item ),
				'UnitPriceWithTax'   => $order->get_item_total( $item, true ),
				'TotalAmount'        => $order->get_line_total( $item ),
				'TotalAmountWithTax' => $order->get_line_total( $item, true ),
			);

			$invoice_props['Lines'][] = $invoice_line_item;
		}

		if ( 0 < count( $order->get_shipping_methods() ) ) {
			foreach ( $order->get_shipping_methods() as $shipping_method ) {
				$unit_price = (
					(float) $shipping_method->get_total() -
					(float) $shipping_method->get_total_tax()
				);

				$invoice_props['Lines'][] = array(
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
			$invoice_props['Discount'] = $order->get_total_discount();
		}

		$invoice_props['TotalAmount'] = (
			(float) $order->get_total() -
			(float) $order->get_total_tax()
		);

		$invoice_props['TotalAmountWithTax'] = $order->get_total();

		if ( true === $order->is_paid() ) {
			$payment_mapping = Config::get_payment_mapping( $order->get_payment_method() );

			$invoice_props['Payments'] = array(
				'ID'     => $payment_mapping->dk_id,
				'Name'   => $payment_mapping->dk_name,
				'Amount' => $order->get_total(),
			);
		}

		return $invoice_props;
	}

	/**
	 * Export a WooCommerce order to a valid HTTP body based on its Id.
	 *
	 * @param int $order_id The Order ID.
	 */
	public static function id_to_dk_invoice_body( int $order_id ): array {
		$order = new WC_Order( $order_id );
		return self::to_dk_invoice_body( $order );
	}
}
