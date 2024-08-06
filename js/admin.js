class NineteenEightyWoo {
	static settingsForm() {
		return document.querySelector( '#nineteen-eighty-woo-settings-form' );
	}
	static settingsErrorIndicator() {
		return document.querySelector( '#nineteen-eighty-woo-settings-error' );
	}
	static settingsErrorIndicator() {
		return document.querySelector( '#nineteen-eighty-woo-settings-error' );
	}
	static settingsLoader() {
		return document.querySelector( '#nineteen-eighty-woo-settings-loader' );
	}
	static settingsSubmit() {
		return document.querySelector( '#nineteen-eighty-woo-settings-submit' );
	}
	static shippingSkuField() {
		return document.querySelector( '#shipping_sku_field' );
	}
	static rowElements() {
		return document.querySelectorAll(
			'#payment-gateway-id-map-table tbody tr'
		);
	}

	static onSettingsFormSubmit(event) {
		event.preventDefault();

		NineteenEightyWoo.settingsLoader().classList.remove( 'hidden' );
		NineteenEightyWoo.settingsSubmit().disabled = true;

		if ( false == NineteenEightyWoo.settingsForm().checkValidity() ) {
			NineteenEightyWoo.settingsErrorIndicator().classList.remove( 'hidden' );
			NineteenEightyWoo.settingsLoader().classList.add( 'hidden' );
			NineteenEightyWoo.settingsSubmit().disabled = false;
			return false;
		}
		NineteenEightyWoo.settingsErrorIndicator().classList.add( 'hidden' );

		const formData = new FormData( event.target );

		let paymentIds   = formData.getAll( 'payment_id' );
		let paymentModes = formData.getAll( 'payment_mode' );
		let paymentTerms = formData.getAll( 'payment_term' );

		let paymentMethods = [];
		let paymentsLength = paymentIds.length;

		for (let i = 0; i < paymentsLength; i++) {
			let wooId  = NineteenEightyWoo.rowElements()[i].dataset.gatewayId;
			let dkId   = parseInt( paymentIds[i] );
			let dkMode = paymentModes[i];
			let dkTerm = paymentTerms[i];

			if (isNaN( dkId )) {
				dkId = 0;
			}

			paymentMethods.push(
				{
					woo_id:  wooId,
					dk_id:   dkId,
					dk_mode: dkMode,
					dk_term: dkTerm,
				}
			);
		}

		const formDataObject = {
			api_key: formData.get( 'api_key' ).trim(),
			product_price_sync: Boolean( formData.get( 'product_price_sync' ) ),
			product_quantity_sync: Boolean( formData.get( 'product_quantity_sync' ) ),
			product_name_sync: Boolean( formData.get( 'product_name_sync' ) ),
			import_nonweb_products: Boolean( formData.get( 'import_nonweb_products' ) ),
			delete_inactive_products: Boolean( formData.get( 'delete_inactive_products' ) ),
			shipping_sku: formData.get( 'shipping_sku' ).trim(),
			cost_sku: formData.get( 'cost_sku' ).trim(),
			default_kennitala: formData.get( 'default_kennitala' ).trim(),
			enable_kennitala: Boolean( formData.get( 'enable_kennitala' ) ),
			default_sales_person: formData.get( 'default_sales_person' ).trim(),
			payment_methods: paymentMethods,
			ledger_code_standard: formData.get( 'ledger_code_standard' ).trim(),
			ledger_code_standard_purchase: formData.get( 'ledger_code_standard_purchase' ).trim(),
			ledger_code_reduced: formData.get( 'ledger_code_reduced' ).trim(),
			ledger_code_reduced_purchase: formData.get( 'ledger_code_reduced_purchase' ).trim(),
			customer_requests_kennitala_invoice: Boolean( formData.get( 'customer_requests_kennitala_invoice' ) ),
			make_invoice_if_kennitala_is_set: Boolean( formData.get( 'make_invoice_if_kennitala_is_set' ) ),
			make_invoice_if_kennitala_is_missing: Boolean( formData.get( 'make_invoice_if_kennitala_is_missing' ) ),
			email_invoice: Boolean( formData.get( 'email_invoice' ) ),
			make_credit_invoice: Boolean( formData.get( 'make_credit_invoice' ) ),
			domestic_customer_ledger_code: formData.get( 'domestic_customer_ledger_code' ),
			international_customer_ledger_code: formData.get( 'international_customer_ledger_code' ),
			use_attribute_description: Boolean( formData.get( 'use_attribute_description' ) ),
			use_attribute_value_description: Boolean( formData.get( 'use_attribute_value_description' ) ),
			product_convertion_to_variation_enabled: Boolean( formData.get( 'product_conversion' ) ),
			fetch_products: true
		}

		NineteenEightyWoo.postSettingsData( formDataObject );
	}

	static async postSettingsData(formDataObject) {

		const response = await fetch(
			wpApiSettings.root + 'NineteenEightyWoo/v1/settings',
			{
				method: 'POST',
				headers: {
					'Content-Type': 'application/json;charset=UTF-8',
					'X-WP-Nonce': wpApiSettings.nonce,
				},
				body: JSON.stringify( formDataObject ),
			}
		);

		NineteenEightyWoo.settingsLoader().classList.add( 'hidden' );

		window.location.reload();

		if ( response.ok ) {
			if ( 'onlyApiKey' in NineteenEightyWoo.settingsForm().dataset ) {
				window.location.reload( true );
			}
		} else {
			NineteenEightyWoo.settingsErrorIndicator().classList.remove( 'hidden' );
		}
	}
}

window.addEventListener(
	'DOMContentLoaded',
	() => {
		if (document.body) {
			if ( NineteenEightyWoo.settingsForm() ) {
				NineteenEightyWoo.settingsForm().addEventListener(
					'submit',
					NineteenEightyWoo.onSettingsFormSubmit
				);
			}

			if ( NineteenEightyWoo.settingsForm() ) {
				NineteenEightyWoo.settingsForm().addEventListener(
					'submit',
					NineteenEightyWoo.onSettingsFormSubmit
				);
			}
		}
	}
);
