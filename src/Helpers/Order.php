<?php

declare(strict_types = 1);

namespace NineteenEightyFour\NineteenEightyWoo\Helpers;

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
}
