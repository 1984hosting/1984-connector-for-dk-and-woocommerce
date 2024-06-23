<?php

declare(strict_types = 1);

namespace NineteenEightyFour\NineteenEightyWoo\Helpers;

use NineteenEightyFour\NineteenEightyWoo\Config;
use WC_Customer;
use WC_Order;
use WC_Order_Item_Product;

/**
 * The Order Helper class
 */
class Order {
	/**
	 * Check if an order can be invoiced in DK
	 *
	 * Checks if any of the order items does not have a SKU and returns false.
	 *
	 * @param WC_Order $wc_order The WooCommerce order.
	 *
	 * @return bool True if the order can be invoiced, false if not.
	 */
	public static function can_be_invoiced( WC_Order $wc_order ): bool {
		foreach ( $wc_order->get_items( 'line_item' ) as $order_item ) {
			if ( $order_item instanceof WC_Order_Item_Product ) {
				if ( empty( $order_item->get_product()->get_sku() ) ) {
					return false;
				}
			}
		}
		return true;
	}

	public static function get_kennitala( WC_Order $wc_order ): string {
		$block_kennitala = $wc_order->get_meta(
			'_wc_other/1984_woo_dk/kennitala',
			true
		);

		if ( ! empty( $block_kennitala ) ) {
			return (string) $block_kennitala;
		}

		$classic_kennitala = $wc_order->get_meta( 'billing_kennitala', true );

		if ( ! empty( $classic_kennitala ) ) {
			return (string) $classic_kennitala;
		}

		$customer_id = $wc_order->get_customer_id();

		if ( 0 !== $customer_id ) {
			$customer           = new WC_Customer( $customer_id );
			$customer_kennitala = $customer->get_meta(
				'kennitala',
				true,
				'edit'
			);

			if ( false === empty( $customer_kennitala ) ) {
				return $customer_kennitala;
			}
		}

		return Config::get_default_kennitala();
	}

	public static function get_kennitala_invoice_requested(
		WC_Order $wc_order
	): bool {
		$block_value = $wc_order->get_meta(
			'_wc_other/1984_woo_dk/kennitala_invoice_requested',
			true
		);

		if ( ! empty( $block_value ) ) {
			return (bool) $block_value;
		}

		$classic_value = $wc_order->get_meta(
			'kennitala_invoice_requested',
			true
		);

		if ( ! empty( $classic_value ) ) {
			return (bool) $classic_value;
		}

		return false;
	}
}
