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

		let apiKey               = formData.get( 'api_key' ).trim();
		let productPriceSync     = Boolean( formData.get( 'product_price_sync' ) );
		let productQuantitySync  = Boolean( formData.get( 'product_quantity_sync' ) );
		let productNameSync      = Boolean( formData.get( 'product_name_sync' ) );
		let importNonwebProducts = Boolean( formData.get( 'import_nonweb_products' ) );;
		let deleteInactiveProducts = Boolean( formData.get( 'delete_inactive_products' ) );
		let shippingSku            = formData.get( 'shipping_sku' ).trim();
		let costSku                = formData.get( 'cost_sku' ).trim();
		let defaultKennitala       = formData.get( 'default_kennitala' ).trim();
		let enableKennitala        = Boolean( formData.get( 'enable_kennitala' ) );
		let defaultSalesPerson     = formData.get( 'default_sales_person' ).trim();
		let paymentIds             = formData.getAll( 'payment_id' );
		let paymentModes           = formData.getAll( 'payment_mode' );
		let paymentTerms           = formData.getAll( 'payment_term' );
		let ledgerCodeStandard     = formData.get( 'ledger_code_standard' ).trim();
		let ledgerCodeReduced      = formData.get( 'ledger_code_reduced' ).trim();

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
			api_key: apiKey,
			product_price_sync: productPriceSync,
			product_quantity_sync: productQuantitySync,
			product_name_sync: productNameSync,
			import_nonweb_products: importNonwebProducts,
			delete_inactive_products: deleteInactiveProducts,
			shipping_sku: shippingSku,
			cost_sku: costSku,
			default_kennitala: defaultKennitala,
			enable_kennitala: enableKennitala,
			default_sales_person: defaultSalesPerson,
			payment_methods: paymentMethods,
			ledger_code_standard: ledgerCodeStandard,
			ledger_code_reduced: ledgerCodeReduced,
			customer_requests_kennitala_invoice: Boolean( formData.get( 'customer_requests_kennitala_invoice' ) ),
			make_invoice_if_kennitala_is_set: Boolean( formData.get( 'make_invoice_if_kennitala_is_set' ) ),
			make_invoice_if_kennitala_is_missing: Boolean( formData.get( 'make_invoice_if_kennitala_is_missing' ) ),
			email_invoice: Boolean( formData.get( 'email_invoice' ) ),
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
		NineteenEightyWoo.settingsSubmit().disabled = false;

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
			if (
				document.body.classList.contains(
					'woocommerce_page_1984-dk-woo'
				)
			) {
				NineteenEightyWoo.settingsForm().addEventListener(
					'submit',
					NineteenEightyWoo.onSettingsFormSubmit
				);
			}

			if (
				document.body.classList.contains(
					'post-type-product'
				)
			) {
				NineteenEightyWoo.settingsForm().addEventListener(
					'submit',
					NineteenEightyWoo.onSettingsFormSubmit
				);
			}
		}
	}
);
