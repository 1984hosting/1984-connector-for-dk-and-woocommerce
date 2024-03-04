class NinteenEightyWoo {
	static settingsForm() {
		return document.querySelector('#ninteen-eighty-woo-settings-form');
	}
	static settingsLoader() {
		return document.querySelector('#ninteen-eighty-woo-settings-loader');
	}
	static settingsSubmit() {
		return document.querySelector('#ninteen-eighty-woo-settings-submit');
	}
	static rowElements() {
		return document.querySelectorAll(
			'#payment-gateway-id-map-table tbody tr'
		);
	}

	static onSettingsFormSubmit(event) {
		event.preventDefault();

		const formData = new FormData(event.target);

		let apiKey         = formData.get('api_key').trim();
		let paymentIds     = formData.getAll('payment_id');
		let paymentNames   = formData.getAll('payment_name');
		let paymentMethods = [];

		for (let i = 0; i < paymentIds.length; i++) {
			let wooId = NinteenEightyWoo.rowElements()[i].dataset.gatewayId;

			paymentMethods.push(
				{
					woo_id: wooId,
					dk_id: parseInt(paymentIds[i]),
					dk_name: paymentNames[i].trim()
				}
			);
		}

		const formDataObject = {
			apiKey: apiKey,
			paymentMethods: paymentMethods
		}

		console.log(formDataObject);

		NinteenEightyWoo.settingsLoader().classList.remove('hidden');
		NinteenEightyWoo.settingsSubmit().disabled = true;
		NinteenEightyWoo.postSettingsData(formDataObject);
	}

	static async postSettingsData(formDataObject) {
		const response = await fetch(
			wpApiSettings.root + 'NinteenEightyWoo/v1/settings',
			{
				method: 'POST',
				headers: {
					'Content-Type': 'application/json;charset=UTF-8',
					'X-WP-Nonce': wpApiSettings.nonce,
				},
				body: JSON.stringify(formDataObject),
			}
		);

		if (response.ok) {
			NinteenEightyWoo.settingsLoader().classList.add('hidden');
			NinteenEightyWoo.settingsSubmit().disabled = false;
		}
	}
}

window.addEventListener('DOMContentLoaded', () => {
	if (document.body) {
		if (
			document.body.classList.contains(
				'woocommerce_page_NinteenEightyWoo'
			)
		) {
			NinteenEightyWoo.settingsForm().addEventListener(
				'submit',
				NinteenEightyWoo.onSettingsFormSubmit
			);
		}
	}
});
