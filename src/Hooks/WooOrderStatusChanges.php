<?php

declare(strict_types = 1);

namespace NineteenEightyFour\NineteenEightyWoo\Hooks;

use NineteenEightyFour\NineteenEightyWoo\Config;
use NineteenEightyFour\NineteenEightyWoo\Export\Invoice as ExportInvoice;
use NineteenEightyFour\NineteenEightyWoo\Helpers\Order as OrderHelper;
use WC_Order;
use WP_Error;

/**
 * The WooCommerce Order Status Changes class
 *
 * This class contains hook actions for when an order's status has been changed
 * to completed or refunded and is the correct place for using hooks beginning
 * with `woocommerce_order_status`.
 *
 * Note that any email functionality herein is via the DK API and does not use
 * WordPress' mailing functionality at all.
 */
class WooOrderStatusChanges {
	/**
	 * The class constructor, silly
	 */
	public function __construct() {
		add_action(
			'woocommerce_order_status_completed',
			array( __CLASS__, 'maybe_send_invoice_on_payment' ),
			10,
			1
		);

		add_action(
			'woocommerce_order_status_processing',
			array( __CLASS__, 'maybe_send_invoice_on_payment' ),
			10,
			1
		);
	}

	/**
	 * Send invoice after a completed payment
	 *
	 * Creates an invoice in DK and sends an invoice to the user from there if
	 * an invoice has not already been created.
	 *
	 * @param int $order_id The WooCommerce order ID.
	 */
	public static function maybe_send_invoice_on_payment(
		int $order_id
	): void {
		$wc_order = new WC_Order( $order_id );

		if ( ! empty( ExportInvoice::get_dk_invoice_number( $wc_order ) ) ) {
			return;
		}

		if (
			! empty(
				$wc_order->get_meta(
					'1984_dk_woo_invoice_creation_error',
					true
				)
			)
		) {
			return;
		}

		$kennitala = OrderHelper::get_kennitala( $wc_order );

		if (
			( Config::get_default_kennitala() !== $kennitala ) &&
			( ! Config::get_make_invoice_if_kennitala_is_set() )
		) {
			$wc_order->add_order_note(
				__(
					'An invoice was not created as the customer entered a kennitala. The invoice needs to be created manually.',
					'1984-dk-woo'
				)
			);

			return;
		}

		if (
			( Config::get_default_kennitala() === $kennitala ) &&
			( ! Config::get_make_invoice_if_kennitala_is_missing() )
		) {
			$wc_order->add_order_note(
				__(
					'An invoice was not created as the customer did not enter a kennitala. The invoice needs to be created manually.',
					'1984-dk-woo'
				)
			);

			return;
		}

		if ( ! OrderHelper::can_be_invoiced( $wc_order ) ) {
			$wc_order->add_order_note(
				__(
					'An invoice could not be created in DK for this order as a line item in this order does not have a SKU.',
					'1984-dk-woo'
				)
			);

			return;
		}

		$invoice_number = ExportInvoice::create_in_dk( $wc_order );

		if ( is_string( $invoice_number ) ) {
			$wc_order->add_order_note(
				sprintf(
					// Translators: %1$s is a placeholder for the invoice number generated in DK.
					__(
						'An invoice for this order has been created in DK. The invoice number is %1$s.',
						'1984-dk-woo'
					),
					$invoice_number
				)
			);

			if ( Config::get_email_invoice() ) {
				if ( ExportInvoice::email_in_dk( $wc_order ) === true ) {
					$wc_order->add_order_note(
						__(
							'An email containing the invoice as a PDF attachment was sent to the customer.',
							'1984-dk-woo'
						)
					);
				} else {
					$wc_order->add_order_note(
						__(
							'It was not possible to send an email to the customer containing the invoice as a PDF attachment.',
							'1984-dk-woo'
						)
					);
				}
			}
		} elseif ( $invoice_number instanceof WP_Error ) {
			$wc_order->update_meta_data(
				'1984_dk_woo_invoice_creation_error',
				$invoice_number->get_error_code()
			);
			$wc_order->update_meta_data(
				'1984_dk_woo_invoice_creation_error_message',
				$invoice_number->get_error_message()
			);
			$wc_order->update_meta_data(
				'1984_dk_woo_invoice_creation_error_data',
				$invoice_number->get_error_data()
			);
			$wc_order->add_order_note(
				__(
					'Unable to create an invoice in DK: ',
					'1984-dk-woo'
				) . $invoice_number->get_error_code()
			);
			$wc_order->save();
		} else {
			$wc_order->add_order_note(
				__(
					'An invoice could not be created in DK due to an unhandled error.',
					'1984-dk-woo'
				)
			);
		}
	}
}
