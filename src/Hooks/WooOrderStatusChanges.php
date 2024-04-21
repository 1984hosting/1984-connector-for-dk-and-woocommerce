<?php

declare(strict_types = 1);

namespace NineteenEightyFour\NineteenEightyWoo\Hooks;

use NineteenEightyFour\NineteenEightyWoo\Export\Invoice as ExportInvoice;
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

		add_action(
			'woocommerce_order_status_refunded',
			array( __CLASS__, 'maybe_send_credit_invoice_on_refund' ),
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
	public static function maybe_send_invoice_on_payment( int $order_id ): void {
		$wc_order = new WC_Order( $order_id );

		if ( false === empty( ExportInvoice::get_dk_invoice_number( $wc_order ) ) ) {
			return;
		}

		$invoice_number = ExportInvoice::create_in_dk( $wc_order );

		if ( 'string' === gettype( $invoice_number ) ) {
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

			if ( true === ExportInvoice::email_in_dk( $wc_order ) ) {
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
		} elseif ( false === $invoice_number ) {
			$wc_order->add_order_note(
				__(
					'Connection was established to DK but an invoice was not created due to an error.',
					'1984-dk-woo'
				)
			);
		} elseif ( $invoice_number instanceof WP_Error ) {
			$wc_order->add_order_note(
				__(
					'Unable to establish a connection with DK to create an invoice.',
					'1984-dk-woo'
				)
			);
		}
	}

	/**
	 * Send a credit invoice after an order has been fully refunded
	 *
	 * Used for the `woocommerce_order_status_refunded` hook and creates a
	 * credit invoice for an order in DK, finally sending it to the customer, if
	 * a credit invoice has not yet been created.
	 *
	 * @param int $order_id The WooCommerce order ID.
	 */
	public static function maybe_send_credit_invoice_on_refund( int $order_id ): void {
		$wc_order = new WC_Order( $order_id );

		if ( false === empty( ExportInvoice::get_dk_credit_invoice_number( $wc_order ) ) ) {
			return;
		}

		$credit_invoice_number = ExportInvoice::reverse_in_dk( $wc_order );

		if ( 'string' === gettype( $credit_invoice_number ) ) {
			$wc_order->add_order_note(
				sprintf(
					// Translators: %1$s is a placeholder for the invoice number generated in DK.
					__(
						'A credit invoice for the refund has been created in DK. The invoice number is %1$s.',
						'1984-dk-woo'
					),
					$credit_invoice_number
				)
			);

			if ( true === ExportInvoice::email_in_dk( $wc_order, 'credit' ) ) {
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
		} elseif ( false === $credit_invoice_number ) {
			$wc_order->add_order_note(
				__(
					'Connection was established to DK but a credit invoice was not created due to an error.',
					'1984-dk-woo'
				)
			);
		} elseif ( $credit_invoice_number instanceof WP_Error ) {
			$wc_order->add_order_note(
				__(
					'Unable to establish a connection with DK to create a credit invoice.',
					'1984-dk-woo'
				)
			);
		}
	}
}
