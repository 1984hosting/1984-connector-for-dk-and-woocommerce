<?php

declare(strict_types = 1);

namespace NineteenEightyFour\NineteenEightyWoo\Export;

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

		$full_name = implode(
			' ',
			array(
				$order->get_billing_first_name(),
				$order->get_billing_last_name(),
			)
		);

		if ( empty( $address['company'] ) ) {
			$invoice_props['CName'] = $full_name;
		} else {
			$invoice_props['CName'] = $order->get_billing_company();
			if ( false === empty( $full_name ) ) {
				$invoice_props['CContact'] = $full_name;
			}
		}

		$invoice_props['CAddress1'] = $order->get_billing_address_1();
		$invoice_props['CAddress2'] = $order->get_billing_address_2();
		$invoice_props['CAddress3'] = $order->get_billing_city();
		$invoice_props['CZipCode']  = $order->get_billing_postcode();
		$invoice_props['CPhone']    = $order->get_billing_phone();

		if ( get_option( 'woocommerce_default_country' ) !== $order->get_billing_country() ) {
			$invoice_props['CCountryCode'] = $order->get_billing_country();
		}

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

		if ( '0' !== $order->get_shipping_total() ) {
			$invoice_props['Lines'][] = array(
				'Text'             => __( 'Shipping', 'NineteenEightyWoo' ),
				'Text2'            => $order->get_shipping_method(),
				'Quantity'         => 1,
				'UnitPrice'        => bcsub( $order->get_shipping_total(), $order->get_shipping_tax() ),
				'UnitPriceWithTax' => $order->get_shipping_total(),
			);
		}

		if ( 0 < $order->get_total_discount() ) {
			$invoice_props['Discount'] = $order->get_total_discount();
		}

		$invoice_props['TotalAmount'] = bcsub(
			(string) $order->get_total(),
			(string) $order->get_total_tax()
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
