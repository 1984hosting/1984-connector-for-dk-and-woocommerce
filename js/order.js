class NineteenEightyWooOrder {
	static invoiceMetaBox() {
		return document.getElementById(
			'nineteen-eighty-woo-dk-invoice-metabox'
		);
	}

	static invoiceNumberInput() {
		return document.getElementById(
			'nineteen-eighty-woo-dk-invoice-metabox-invoice-number-input'
		);
	}

	static creditInvoiceInput() {
		return document.getElementById(
			'1984-dk-woo-dk-invoice-metabox-credit-invoice-number-input'
		);
	}

	static invoiceNumberInvalid() {
		return document.getElementById(
			'nineteen-eighty-woo-dk-invoice-metabox-invoice-number-invalid'
		);
	}

	static creditInvoiceInvalid() {
		return document.getElementById(
			'nineteen-eighty-woo-dk-credit-invoice-metabox-invoice-number-invalid'
		);
	}

	static getPdfButton() {
		return document.getElementById(
			'nineteen-eighty-woo-dk-invoice-metabox-invoice-get-pdf-button'
		);
	}

	static getCreditPdfButton() {
		return document.getElementById(
			'nineteen-eighty-woo-dk-invoice-metabox-credit-invoice-get-pdf-button'
		);
	}


	static createDkInvoiceButton() {
		return document.getElementById(
			'nineteen-eighty-woo-dk-invoice-metabox-make-dk-invoice-button'
		);
	}

	static updateInvoiceButton() {
		return document.getElementById(
			'nineteen-eighty-woo-dk-invoice-metabox-invoice-number-update-button'
		);
	}

	static updateCreditInvoiceButton() {
		return document.getElementById(
			'nineteen-eighty-woo-dk-invoice-metabox-credit-invoice-number-update-button'
		);
	}

	static invoiceLoader() {
		return document.getElementById(
			'nineteen-eighty-woo-dk-invoice-metabox-invoice-loader'
		);
	}

	static creditInvoiceLoader() {
		return document.getElementById(
			'nineteen-eighty-woo-dk-invoice-metabox-credit-invoice-loader'
		);
	}

	static updateInvoiceButtonClickEvent( e ) {
		e.preventDefault();
		this.updateInvoiceButtonClickAction();
	}

	static updateCreditInvoiceButtonClickEvent( e ) {
		e.preventDefault();
		this.updateCreditInvoiceButtonClickAction();
	}

	static getPdfClickEvent( e ) {
		e.preventDefault();
		this.getPdfInvoiceButtonClickAction();
	}

	static getPdfInvoiceButtonClickAction() {
		this.invoiceLoader().classList.remove( 'hidden' );
		this.getInvoicePdf();
	}

	static formData() {
		let form = document.getElementById( 'post' );
		return new FormData( form );
	}

	static async getInvoicePdf() {
		const invoiceID = NineteenEightyWooOrder.formData().get(
			'1984_woo_dk_invoice_number'
	);

		const response = await fetch(
			wpApiSettings.root + 'NineteenEightyWoo/v1/order_invoice_pdf/' + invoiceID,
			{
				method: 'GET',
				headers: {
					'Content-Type': 'application/json;charset=UTF-8',
					'X-WP-Nonce': wpApiSettings.nonce,
				}
			}
		);

		if ( response ) {
			this.invoiceLoader().classList.add( 'hidden' );
		}

		if ( response.ok ) {
			const json = await response.json();

			window.open(
				'data:application/pdf;base64,' + json.data,
				'_blank'
			)
		}
	}

	static async submitInvoiceNumber( postID, invoiceNumber, type='debit' ) {
		const requestBody = {
			type: type,
			order_id: postID,
			invoice_number: invoiceNumber,
		};
		const response = await fetch(
			wpApiSettings.root + 'NineteenEightyWoo/v1/order_invoice_number',
			{
				method: 'POST',
				headers: {
					'Content-Type': 'application/json;charset=UTF-8',
					'X-WP-Nonce': wpApiSettings.nonce,
				},
				body: JSON.stringify( requestBody ),
			}
		);

		if ( response.ok ) {
			switch (type) {
				case 'credit':
					this.creditInvoiceLoader().classList.add( 'hidden' );
					this.updateCreditInvoiceButton().disabled = false;
					break;

				default:
					this.invoiceLoader().classList.add( 'hidden' );
					this.updateInvoiceButton().disabled = false;
					break;
			}
		}
	}

	static updateInvoiceButtonClickAction() {
		this.invoiceLoader().classList.remove( 'hidden' );
		this.updateInvoiceButton().disabled = true;

		const postID        = parseInt( this.formData().get( 'post_ID' ) );
		const invoiceNumber = parseInt( this.formData().get( '1984_woo_dk_invoice_number' ) );

		this.submitInvoiceNumber( postID, invoiceNumber, 'debit' );
	}

	static updateCreditInvoiceButtonClickAction() {
		this.creditInvoiceLoader().classList.remove( 'hidden' );
		this.updateCreditInvoiceButton().disabled = true;

		const postID        = parseInt( this.formData().get( 'post_ID' ) );
		const invoiceNumber = parseInt( this.formData().get( '1984_woo_dk_credit_invoice_number' ) );

		this.submitInvoiceNumber( postID, invoiceNumber, 'credit' );
	}

	static disableUpdateInvoiceFieldIfInvalid() {
		const invoiceNumber = this.formData().get(
			'1984_woo_dk_invoice_number'
		);

		if ( /^[1-9][0-9]{0,}$/.test( invoiceNumber ) ) {
			this.updateInvoiceButton().disabled = false;
			this.getPdfButton().disabled = false;
			this.invoiceNumberInvalid().classList.add('hidden');
		} else {
			this.updateInvoiceButton().disabled = true;
			this.getPdfButton().disabled = true;
			this.invoiceNumberInvalid().classList.remove('hidden');
		}

		if ( invoiceNumber === '' ) {
			this.createDkInvoiceButton().disabled = false;
			this.invoiceNumberInvalid().classList.add('hidden');
		} else {
			this.createDkInvoiceButton().disabled = true;
		}
	}

	static disableUpdateCreditInvoiceFieldIfInvalid() {
		const creditInvoiceNumber = this.formData().get(
			'1984_woo_dk_credit_invoice_number'
		);

		if ( /^[1-9][0-9]{0,}$/.test( creditInvoiceNumber ) ) {
			this.updateCreditInvoiceButton().disabled = false;
			this.getCreditPdfButton().disabled = false;
			this.creditInvoiceInvalid().classList.add('hidden');
		} else {
			this.updateCreditInvoiceButton().disabled = true;
			this.getCreditPdfButton().disabled = true;
			this.creditInvoiceInvalid().classList.remove('hidden');
		}

		if ( creditInvoiceNumber === '' ) {
			this.creditInvoiceInvalid().classList.add('hidden');
		}
	}
}

window.addEventListener(
	'DOMContentLoaded',
	() => {
		if ( NineteenEightyWooOrder.invoiceMetaBox() ) {
			NineteenEightyWooOrder.updateInvoiceButton().addEventListener(
				'click',
				( e ) => {
					NineteenEightyWooOrder.updateInvoiceButtonClickEvent( e );
				}
			);

			NineteenEightyWooOrder.updateCreditInvoiceButton().addEventListener(
				'click',
				( e ) => {
					NineteenEightyWooOrder.updateCreditInvoiceButtonClickEvent( e );
				}
			);

			NineteenEightyWooOrder.getPdfButton().addEventListener(
				'click',
				( e ) => {
					NineteenEightyWooOrder.getPdfClickEvent( e );
				}
			);

			NineteenEightyWooOrder.invoiceNumberInput().addEventListener(
				'input',
				( e ) => {
					NineteenEightyWooOrder.disableUpdateInvoiceFieldIfInvalid();
				}
			);

			NineteenEightyWooOrder.disableUpdateInvoiceFieldIfInvalid();

			NineteenEightyWooOrder.creditInvoiceInput().addEventListener(
				'input',
				( e ) => {
					NineteenEightyWooOrder.disableUpdateCreditInvoiceFieldIfInvalid();
				}
			);

			NineteenEightyWooOrder.disableUpdateCreditInvoiceFieldIfInvalid();
		}
	}
);
