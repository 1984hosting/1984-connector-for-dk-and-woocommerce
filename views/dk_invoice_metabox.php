<?php

declare(strict_types = 1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $post;

$wc_order = wc_get_order( $post->ID );

$invoice_number        = $wc_order->get_meta( '1984_woo_dk_invoice_number', true, 'edit' );
$credit_invoice_number = $wc_order->get_meta( '1984_woo_dk_credit_invoice_number', true, 'edit' );

?>

<div
	class="input-set"
	aria-labelledby="nineteen-eighty-woo-dk-invoice-metabox-invoice-number-label"
>
	<div class="input">
		<label
			id="nineteen-eighty-woo-dk-invoice-metabox-invoice-number-label"
			for="nineteen-eighty-woo-dk-invoice-metabox-invoice-number-input"
		>
			<?php esc_html_e( 'Invoice Number', '1984-dk-woo' ); ?>
		</label>
		<input
			id="nineteen-eighty-woo-dk-invoice-metabox-invoice-number-input"
			class="regular-text"
			name="1984_woo_dk_invoice_number"
			type="text"
			autocomplete="off"
			value="<?php echo esc_attr( $invoice_number ); ?>"
		/>
		<div class="errors" aria-live="polite">
			<p
				id="nineteen-eighty-woo-dk-invoice-metabox-invoice-number-invalid"
				class="infotext error hidden"
			>
				<span class="dashicons dashicons-no"></span>
				<?php esc_html_e( 'Needs to be numeric', '1984-dk-woo' ); ?>
			</p>
		</div>
	</div>
	<div class="buttons">
		<button
			id="nineteen-eighty-woo-dk-invoice-metabox-invoice-number-update-button"
			class="button button-small button-secondary"
			title="Update the invoice number reference without generating a new invoice in DK"
			<?php echo empty( $invoice_number ) ? 'disabled' : ''; ?>
		>
			<?php esc_html_e( 'Update', '1984-dk-woo' ); ?>
		</button>
		<button
			id="nineteen-eighty-woo-dk-invoice-metabox-invoice-get-pdf-button"
			class="button button-small button-primary"
			title="Get the invoice as a PDF file"
			<?php echo empty( $invoice_number ) ? 'disabled' : ''; ?>
		>
			<?php esc_html_e( 'Get PDF', '1984-dk-woo' ); ?>
		</button>
		<button
			id="nineteen-eighty-woo-dk-invoice-metabox-make-dk-invoice-button"
			class="button button-small button-primary"
			title="Generate a new invoice for this order in DK and assign it to this order"
			<?php echo empty( $invoice_number ) ? '' : 'disabled'; ?>
		>
			<?php esc_html_e( 'Create in DK', '1984-dk-woo' ); ?>
		</button>
		<img
			id="nineteen-eighty-woo-dk-invoice-metabox-invoice-loader"
			class="loader hidden"
			src="<?php echo esc_url( get_admin_url() . 'images/wpspin_light-2x.gif' ); ?>"
			width="16"
			height="16"
		/>
	</div>
</div>

<div class="input-set">
	<div class="input">
		<label
			for="1984-dk-woo-dk-invoice-metabox-credit-invoice-number-input"
		>
			<?php esc_html_e( 'Credit Invoice Number', '1984-dk-woo' ); ?>
		</label>
		<input
			id="1984-dk-woo-dk-invoice-metabox-credit-invoice-number-input"
			class="regular-text"
			name="1984_woo_dk_credit_invoice_number"
			type="text"
			autocomplete="off"
			value="<?php echo esc_attr( $credit_invoice_number ); ?>"
		/>
		<div class="errors" aria-live="polite">
			<p
				id="nineteen-eighty-woo-dk-credit-invoice-metabox-invoice-number-invalid"
				class="infotext error hidden"
			>
				<span class="dashicons dashicons-no"></span>
				<?php esc_html_e( 'Needs to be numeric', '1984-dk-woo' ); ?>
			</p>
		</div>
	</div>
	<div class="buttons">
		<button
			id="nineteen-eighty-woo-dk-invoice-metabox-credit-invoice-number-update-button"
			class="button button-small button-secondary"
			title="Update the credit invoice number reference without generating a new credit invoice in DK"
		>
			<?php esc_html_e( 'Update', '1984-dk-woo' ); ?>
		</button>
		<button
			id="nineteen-eighty-woo-dk-invoice-metabox-credit-invoice-get-pdf-button"
			class="button button-small button-primary"
			title="Get the credit invoice as a PDF file"
			<?php echo empty( $credit_invoice_number ) ? 'disabled' : ''; ?>
		>
			<?php esc_html_e( 'Get PDF', '1984-dk-woo' ); ?>
		</button>
		<img
			id="nineteen-eighty-woo-dk-invoice-metabox-credit-invoice-loader"
			class="loader hidden"
			src="<?php echo esc_url( get_admin_url() . 'images/wpspin_light-2x.gif' ); ?>"
			width="16"
			height="16"
		/>
	</div>
</div>
