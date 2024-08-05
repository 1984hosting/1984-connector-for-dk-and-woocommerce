<?php

declare(strict_types = 1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$wc_order = wc_get_order( $_GET['post'] );

$invoice_number        = $wc_order->get_meta( '1984_woo_dk_invoice_number', true, 'edit' );
$credit_invoice_number = $wc_order->get_meta( '1984_woo_dk_credit_invoice_number', true, 'edit' );

?>

<div
	class="input-set"
	aria-labelledby="nineteen-eighty-woo-dk-invoice-metabox-invoice-number-label"
>
	<div class="input">
		<label id="nineteen-eighty-woo-dk-invoice-metabox-invoice-number-label" for="nineteen-eighty-woo-dk-invoice-metabox-invoice-number-input">Invoice Number</label>
		<input
			id="nineteen-eighty-woo-dk-invoice-metabox-invoice-number-input"
			class="regular-text"
			name="1984_woo_dk_invoice_number"
			type="text"
			value="<?php echo esc_attr( $invoice_number ); ?>"
		/>
	</div>
	<div class="buttons">
		<button
			id="nineteen-eighty-woo-dk-invoice-metabox-invoice-number-update-button"
			class="button button-small button-secondary"
		>
			Update
		</button>
		<button class="button button-small button-primary" disabled>Create in DK</button>
		<img
			class="loader hidden"
			src="<?php echo esc_url( get_admin_url() . 'images/wpspin_light-2x.gif' ); ?>"
			width="16"
			height="16"
		/>
	</div>
</div>

<div class="input-set">
	<div class="input">
		<label for="1984-dk-woo-dk-invoice-metabox-credit-invoice-number-input">Credit Invoice Number</label>
		<input
			id="1984-dk-woo-dk-invoice-metabox-credit-invoice-number-input"
			class="regular-text"
			name="1984_woo_dk_credit_invoice_number"
			type="text"
			value="<?php echo esc_attr( $credit_invoice_number ); ?>"
		/>
	</div>
	<div class="buttons">
		<button class="button button-small button-secondary">Update</button>
		<img
			class="loader hidden"
			src="<?php echo esc_url( get_admin_url() . 'images/wpspin_light-2x.gif' ); ?>"
			width="16"
			height="16"
		/>
	</div>
</div>
