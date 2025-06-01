const { __, _x, _n, _nx } = wp.i18n;

class NineteenEightyWooProducts {
	static assignClickToDKPriceCheckboxes() {
		const checkboxes = document.querySelectorAll(
			'[data-variation-price-checkbox-for]'
		);

		checkboxes.forEach(
			(node) => {
				node.addEventListener(
					'click',
					( e ) => {
						const variationId        = e.target.getAttribute(
							'data-variation-price-checkbox-for'
						);
						const variationFieldsDiv = document.querySelector(
							"[data-variation-price-fields-for='" + variationId + "']"
						);
					if ( e.target.checked ) {
						var disableInputs = false;
						variationFieldsDiv.classList.remove( 'hidden' );
					} else {
						var disableInputs = true;
						variationFieldsDiv.classList.add( 'hidden' );
					}

						document.querySelector(
							"[name='dk_variable_price[" + variationId + "]']"
						).disabled = disableInputs;
						document.querySelector(
							"[name='dk_variable_sale_price[" + variationId + "]']"
						).disabled = disableInputs;
					}
				);
			}
		);
	}

	static assignClickToDKInventoryCheckboxes() {
		const checkboxes = document.querySelectorAll(
			'[data-variation-inventory-checkbox-for]'
		);

		checkboxes.forEach(
			(node) => {
				node.addEventListener(
					'click',
					( e ) => {
						const variationId        = e.target.getAttribute(
							'data-variation-inventory-checkbox-for'
						);
						const variationFieldsDiv = document.querySelector(
							"[data-variation-inventory-fields-for='" + variationId + "']"
						);
						const backorderRadios    = document.querySelectorAll(
							"[data-variation-inventory-fields-for='" + variationId + "'] input[type='radio']"
						);
						const quantityCheckbox   = document.querySelector(
							"[data-variation-inventory-qty-checkbox-for='" + variationId + "']"
						);
						const quantityInput      = document.querySelector(
							"[data-variation-inventory-fields-for='" + variationId + "'] input[type='number']"
						);
					if ( e.target.checked ) {
						quantityCheckbox.disabled = false;
						var disableInputs         = ! quantityCheckbox.checked;
						variationFieldsDiv.classList.remove( 'hidden' );
					} else {
						quantityCheckbox.disabled = true;
						var disableInputs         = true;
						variationFieldsDiv.classList.add( 'hidden' );
					}

						quantityInput.disabled     = disableInputs;
						backorderRadios.forEach(
							(fieldNode) => {
								fieldNode.disabled = disableInputs;
							}
						);
					}
				);
			}
		);
	}

	static assignClickToDKQuantityCheckboxes() {
		const checkboxes = document.querySelectorAll(
			'[data-variation-inventory-qty-checkbox-for]'
		);

		checkboxes.forEach(
			(node) => {
				node.addEventListener(
					'click',
					( e ) => {
						const variationId     = e.target.getAttribute(
							'data-variation-inventory-qty-checkbox-for'
						);
						const quantityInput   = document.querySelector(
							"[data-variation-inventory-fields-for='" + variationId + "'] input[type='number']"
						);
						const backorderRadios = document.querySelectorAll(
							"[data-variation-inventory-fields-for='" + variationId + "'] input[type='radio']"
						);
						const togglableDiv    = document.querySelector(
							"[data-variation-inventory-fields-for='" + variationId + "'] .togglable"
						);
					if ( e.target.checked ) {
						var disableInputs = false;
						togglableDiv.classList.remove( 'hidden' );
					} else {
						var disableInputs = true;
						togglableDiv.classList.add( 'hidden' );
					}

						quantityInput.disabled     = disableInputs;
						backorderRadios.forEach(
							(fieldNode) => {
								fieldNode.disabled = disableInputs;
							}
						);
					}
				);
			}
		);
	}

	static assignClickToDKThumbnailImages() {
		const buttons = document.querySelectorAll(
			'button[data-variation-thumbnail-for]'
		);

		buttons.forEach(
			(node) => {
				node.addEventListener(
					'click',
					( e ) => {
						e.preventDefault();
						const variationId                = e.currentTarget.getAttribute(
							'data-variation-thumbnail-for'
						);
						const variationImageIdInput      = document.querySelector(
							"input[data-variation-image-id-for='" + variationId + "']"
						);
						const variationImageRemoveButton = document.querySelector(
							"button[data-remove-thumbnail-for='" + variationId + "']"
						);
						const variationImageThumbnail    = e.currentTarget.querySelector(
							'img'
						);
						const mediaPopover               = wp.media(
							{
								title: 'Select or upload image for this variation',
								button: { text: 'Use' },
								multiple: false,
								library: {
									type: 'image'
								},
						}
							);
						mediaPopover.on(
							'select',
							function () {
								const selection                = mediaPopover.state().get( 'selection' ).first().toJSON();
								variationImageIdInput.value    = selection.id;
								variationImageIdInput.disabled = false;
								variationImageThumbnail.setAttribute( 'src', selection.url );
								variationImageThumbnail.setAttribute( 'alt', selection.alt );
								variationImageRemoveButton.disabled = false;
							}
							);
						mediaPopover.on(
							'close',
							function () {
								mediaPopover.detach();
							}
							);
						mediaPopover.open();
					}
				)
			}
		)
	}

	static assignClickToDKThumbnailRemoveButton() {
		const buttons = document.querySelectorAll(
			'button[data-remove-thumbnail-for]'
		);

		buttons.forEach(
			(node) => {
				node.addEventListener(
					'click',
					( e ) => {
						e.preventDefault();
						const variationId             = e.currentTarget.getAttribute(
							'data-remove-thumbnail-for'
						);
						const variationImageThumbnail = document.querySelector(
							"[data-variation-thumbnail-for='" + variationId + "'] img"
						);
						const variationImageIdInput   = document.querySelector(
							"input[data-variation-image-id-for='" + variationId + "']"
						);
						variationImageThumbnail.setAttribute(
							'src',
							variationImageThumbnail.getAttribute( 'data-placeholer-src' )
						);
						variationImageThumbnail.setAttribute( 'alt', '' )

						e.currentTarget.disabled       = true;
						variationImageIdInput.disabled = false;
						variationImageIdInput.value    = '0';
					}
				)
			}
		)
	}
}

window.addEventListener(
	'DOMContentLoaded',
	() => {
		NineteenEightyWooProducts.assignClickToDKPriceCheckboxes();
		NineteenEightyWooProducts.assignClickToDKInventoryCheckboxes();
		NineteenEightyWooProducts.assignClickToDKQuantityCheckboxes();
		NineteenEightyWooProducts.assignClickToDKThumbnailImages();
		NineteenEightyWooProducts.assignClickToDKThumbnailRemoveButton();
	}
);
