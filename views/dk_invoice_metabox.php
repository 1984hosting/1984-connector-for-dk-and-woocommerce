<?php

declare(strict_types = 1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$wc_order = wc_get_order( $_GET['post'] );

$invoice_number        = $wc_order->get_meta( '1984_woo_dk_invoice_number', true, 'edit' );
$credit_invoice_number = $wc_order->get_meta( '1984_woo_dk_credit_invoice_number', true, 'edit' );

?>

<div>
	<div>
		<label>Invoice Number</label>
		<input
			name="1984_woo_dk_invoice_number"
			type="text"
			value="<?php echo esc_attr( $invoice_number ); ?>"
		/>
	</div>
	<div>
		<button class="button button-small button-secondary">Set Invoice Number</button>
		<button class="button button-small button-primary">Create in DK</button>
	</div>
</div>

<div>
	<label>Credit Invoice Number</label>
	<input
		name="1984_woo_dk_credit_invoice_number"
		type="text"
		value="<?php echo esc_attr( $credit_invoice_number ); ?>"
	/>
	<div>
		<button class="button button-small button-secondary">Set Credit Invoice Number</button>
	</div>
</div>
