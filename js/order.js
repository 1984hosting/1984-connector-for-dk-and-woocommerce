class NineteenEightyWooOrder {
	static updateInvoiceButton() {
		return document.getElementById(
			'nineteen-eighty-woo-dk-invoice-metabox-invoice-number-update-button'
		);
	}

	static invoiceNumberInput() {
		return document.getElementById(
			'nineteen-eighty-woo-dk-invoice-metabox-invoice-number-input'
		);
	}
}

window.addEventListener(
	'DOMContentLoaded',
	() => {
		console.log( 'foo' );
	}
);
