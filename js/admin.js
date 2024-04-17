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

		if ( 'onlyApiKey' in NineteenEightyWoo.settingsForm().dataset ) {
			const formData = new FormData( event.target );

			const formDataObject = {
				api_key: formData.get( 'api_key' ).trim(),
			}

			NineteenEightyWoo.postSettingsData( formDataObject );
		} else {
			const formData = new FormData( event.target );

			let apiKey                 = formData.get( 'api_key' ).trim();
			let shippingSku            = formData.get( 'shipping_sku' ).trim();
			let costSku                = formData.get( 'cost_sku' ).trim();
			let customerNumberPrefix   = formData.get( 'customer_number_prefix' ).trim();
			let defaultKennitala       = formData.get( 'default_kennitala' ).trim();
			let enableKennitala        = Boolean( formData.get( 'enable_kennitala' ) );
			let enableKennitalaInBlock = Boolean( formData.get( 'enable_kennitala_in_block' ) );
			let defaultSalesPerson     = formData.get( 'default_sales_person' ).trim();
			let paymentIds             = formData.getAll( 'payment_id' );
			let ledgerCodeStandard     = formData.get( 'ledger_code_standard' ).trim();
			let ledgerCodeReduced      = formData.get( 'ledger_code_reduced' ).trim();
			let ledgerCodeShipping     = formData.get( 'ledger_code_shipping' ).trim();
			let ledgerCodeCosts        = formData.get( 'ledger_code_costs' ).trim();
			let paymentMethods         = [];
			let paymentsLength         = paymentIds.length;

			for (let i = 0; i < paymentsLength; i++) {
				let wooId = NineteenEightyWoo.rowElements()[i].dataset.gatewayId;
				let dkId  = parseInt( paymentIds[i] );

				if (isNaN( dkId )) {
					dkId = 0;
				}

				paymentMethods.push(
					{
						woo_id: wooId,
						dk_id: dkId,
					}
				);
			}

			const formDataObject = {
				api_key: apiKey,
				shipping_sku: shippingSku,
				cost_sku: costSku,
				customer_number_prefix: customerNumberPrefix,
				default_kennitala: defaultKennitala,
				enable_kennitala: enableKennitala,
				enable_kennitala_in_block: enableKennitalaInBlock,
				default_sales_person: defaultSalesPerson,
				payment_methods: paymentMethods,
				ledger_code_standard: ledgerCodeStandard,
				ledger_code_reduced: ledgerCodeReduced,
				ledger_code_shipping: ledgerCodeShipping,
				ledger_code_costs: ledgerCodeCosts
			}

			NineteenEightyWoo.postSettingsData( formDataObject );
		}
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
					'woocommerce_page_NineteenEightyWoo'
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
