const { __, _x, _n, _nx } = wp.i18n;

class NineteenEightyWooProducts {
	static actionSelect() {
		return document.getElementById( 'bulk-action-selector-top' );
	}

	static addProductIdInputToActions() {
		let actionsContainer = document.querySelector( '#posts-filter .bulkactions' );
		let actionButton     = document.getElementById( 'doaction' );
		let productIdInput   = document.createElement( 'input' );
		let spacerTextNode   = document.createTextNode( ' ' );

		productIdInput.setAttribute( 'type', 'text' );
		productIdInput.setAttribute( 'name', 'action_post_id' );
		productIdInput.setAttribute( 'id', 'action_post_id_input' );
		productIdInput.setAttribute(
			'placeholder',
			__( 'Parent ID', '1984-dk-woo' )
		);

		actionsContainer.insertBefore( productIdInput, actionButton );
		actionsContainer.insertBefore( spacerTextNode, actionButton );
	}

	static removeProductInputFromActions() {
		let element = document.getElementById( 'action_post_id_input' );

		if ( element !== null ) {
			element.remove();
		}
	}

	static selectMenuEvent( e ) {
		if ( e.target.value == 'convert_to_variant' ) {
			NineteenEightyWooProducts.addProductIdInputToActions();
		} else {
			NineteenEightyWooProducts.removeProductInputFromActions();
		}
	}
}

window.addEventListener(
	'DOMContentLoaded',
	() => {
		if ( NineteenEightyWooProducts.actionSelect() ) {
			NineteenEightyWooProducts.actionSelect().addEventListener(
				'change',
				( e ) => {
					NineteenEightyWooProducts.selectMenuEvent( e );
				}
			);
		}
	}
);
