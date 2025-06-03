<?php

declare(strict_types = 1);

namespace NineteenEightyFour\NineteenEightyWoo\Hooks;

use NineteenEightyFour\NineteenEightyWoo\Import\Products as ImportProducts;
use WC_Product;
use WC_Product_Variable;
use WC_Product_Variation;

/**
 * The WooMetaboxes class
 *
 * The WooMetaboxes class adds fields to the WooCommerce editor metaboxes that
 * handle properties facilitating price and inventory sync with DK.
 */
class WooMetaboxes {
	const PROTECTED_META = array(
		'1984_woo_dk_price_sync',
		'1984_woo_dk_stock_sync',
		'1984_woo_dk_name_sync',
		'1984_woo_dk_dk_currency',
		'1984_woo_dk_credit_invoice_number',
		'1984_woo_dk_invoice_number',
		'last_downstream_sync',
	);

	/**
	 * The constructor for the WooMetaboxes class
	 */
	public function __construct() {
		add_action(
			'woocommerce_product_options_pricing',
			array( $this, 'render_product_options_pricing_partial' )
		);

		add_action(
			'woocommerce_product_options_advanced',
			array( $this, 'render_product_options_advanced_partial' )
		);

		add_action(
			'woocommerce_product_options_sku',
			array( $this, 'render_product_options_sku_partial' )
		);

		add_action(
			'woocommerce_update_product',
			array( $this, 'save_product_meta' ),
			10,
			2
		);

		add_filter(
			'is_protected_meta',
			array( __CLASS__, 'protect_meta' ),
			10,
			2
		);

		add_filter(
			'woocommerce_product_data_tabs',
			array( __CLASS__, 'remove_variations_from_product_editor' ),
			98,
			1
		);

		add_action(
			'woocommerce_product_data_panels',
			array( __CLASS__, 'variations_panel' )
		);

		add_action( 'init', array( __CLASS__, 'add_image_sizes' ) );
	}

	public static function add_image_sizes(): void {
		add_image_size( '1984_dk_woo_variant', 400 );
	}

	/**
	 * Remove the default WooCommerce variations UI from the product meta tabs
	 *
	 * As we are relying on DK's variant products feature, we replace the
	 * WooCommerce product variations interface with our own, which is primarily
	 * read-only.
	 *
	 * This replaces our previous approach, which was to trust users to
	 * manipulate product variations in WooCommerce, which may conflict with
	 * DK's variation feature. This was assumed to be an acceptable approach
	 * before we realised that DK supported variations, but that the feature had
	 * to be enabled by DK's support as it is not on by default.
	 *
	 * @param array $tabs The tabs array to be filtered.
	 */
	public static function remove_variations_from_product_editor(
		array $tabs
	): array {
		$wc_product = wc_get_product();

		if ( $wc_product->get_meta( '1984_dk_woo_origin' ) !== 'product_variation' ) {
			return $tabs;
		}

		unset( $tabs['variations'] );

		$tabs['attribute']['class'] = array( 'hide_if_variable' );

		$tabs['1984_dk_woo_variations'] = array(
			'label'    => 'DK Variations',
			'target'   => 'dk_variations_tab',
			'priority' => 60,
			'class'    => array( 'show_if_variable' ),
		);

		return $tabs;
	}

	/**
	 * Render the DK variations panel
	 */
	public static function variations_panel(): void {
		require dirname( __DIR__, 2 ) . '/views/dk_product_variations.php';
	}

	/**
	 * Render the pricing metabox partial
	 */
	public static function render_product_options_pricing_partial(): void {
		require dirname( __DIR__, 2 ) . '/views/product_options_pricing_partial.php';
	}

	/**
	 * Render the advanced partial
	 */
	public static function render_product_options_advanced_partial(): void {
		require dirname( __DIR__, 2 ) .
			'/views/product_options_advanced_partial.php';
	}

	/**
	 * Render the SKU metabox partial
	 */
	public static function render_product_options_sku_partial(): void {
		require dirname( __DIR__, 2 ) . '/views/product_options_sku_partial.php';
	}

	/**
	 * Protect the meta values so they don't appear in the Custom Fields box
	 *
	 * This prevents the kennitala value from appearing in the Custom Fields
	 * metabox, overriding the order editor.
	 *
	 * @param bool   $protected Wether the meta value is already protected.
	 * @param string $meta_key The meta key.
	 */
	public static function protect_meta(
		bool $protected,
		string $meta_key
	): bool {
		if ( in_array( $meta_key, self::PROTECTED_META, true ) ) {
			return true;
		}

		return $protected;
	}

	/**
	 * Save the NineteenEightyWoo related meta tags for a product using superglobals
	 *
	 * Fired during the `save_post_product` hook.
	 *
	 * @param int        $id The post ID for the product.
	 * @param WC_Product $wc_product The post object (unused).
	 */
	public function save_product_meta(
		int $id,
		WC_Product $wc_product
	): void {
		// As the hook seems to be run twice for some reason, this should
		// prevent the function to run twice during the same HTTP request.
		if ( defined( '1984_DK_WOO_META_UPDATE_HAS_RUN' ) ) {
			return;
		}

		define( '1984_DK_WOO_META_UPDATE_HAS_RUN', true );

		self::set_product_sync_meta_from_post( $wc_product, 'price' );
		self::set_product_sync_meta_from_post( $wc_product, 'stock' );
		self::set_product_sync_meta_from_post( $wc_product, 'name' );

		if ( is_a( $wc_product, 'WC_Product_Variable' ) ) {
			self::set_product_variation_meta_from_post( $wc_product );
		}
	}

	/**
	 * Set a product sync meta value from a POST superglobal
	 *
	 * Checks the relevant nonces and sets the `1984_woo_dk_` product meta from
	 * a $_POST superglobal.
	 *
	 * @param WC_Product $wc_product The WooCommerce product.
	 * @param string     $meta_key The relevant meta key such as price, stock or name.
	 */
	public static function set_product_sync_meta_from_post(
		WC_Product $wc_product,
		string $meta_key
	): void {
		$nonce_superglobal = "set_1984_woo_dk_{$meta_key}_sync_nonce";
		$wc_meta_key       = "1984_woo_dk_{$meta_key}_sync";

		if (
			! empty( $_POST[ $nonce_superglobal ] ) &&
			wp_verify_nonce(
				sanitize_text_field(
					wp_unslash( $_POST[ $nonce_superglobal ] )
				),
				"set_1984_woo_dk_{$meta_key}_sync"
			)
		) {
			if ( isset( $_POST[ $wc_meta_key ] ) ) {
				switch ( $_POST[ $wc_meta_key ] ) {
					case 'true':
						$wc_product->update_meta_data( $wc_meta_key, 'true' );
						break;
					case 'false':
						$wc_product->update_meta_data( $wc_meta_key, 'false' );
						break;
					default:
						$wc_product->update_meta_data( $wc_meta_key, '' );
				}

				$wc_product->save_meta_data();
			}
		}
	}

	/**
	 * Save information about the product variations from the POST superglobal
	 *
	 * Checks the relevant nonce and sets the relevant status and other
	 * attributes for the variable product's variations.
	 *
	 * @param WC_Product_Variable $wc_product The WooCommerce product.
	 */
	private static function set_product_variation_meta_from_post(
		WC_Product_Variable $wc_product
	): void {
		$nonce_superglobal = 'set_1984_woo_dk_variations_nonce';

		if (
			! empty( $_POST[ $nonce_superglobal ] ) &&
			wp_verify_nonce(
				sanitize_text_field(
					wp_unslash( $_POST[ $nonce_superglobal ] )
				),
				'set_1984_woo_dk_variations'
			)
		) {
			self::set_default_attributes_via_post( $wc_product );

			foreach ( $wc_product->get_children() as $variation_id ) {
				$variation = wc_get_product( $variation_id );

				if ( $variation === false ) {
					continue;
				}

				if ( isset( $_POST['dk_variable_enabled'][ $variation_id ] ) ) {
					if ( $variation->get_status( 'edit' ) !== 'publish' ) {
						$variation->set_status( 'publish' );
					}
				} else {
					if ( $variation->get_status( 'edit' ) !== 'private' ) {
						$variation->set_status( 'private' );
					}
				}

				$variation->set_downloadable(
					isset( $_POST['dk_variable_is_downloadable'][ $variation_id ] )
				);

				$variation->set_virtual(
					isset( $_POST['dk_variable_is_virtual'][ $variation_id ] )
				);

				if ( isset( $_POST['dk_variable_description'][ $variation_id ] ) ) {
					$variation->set_description(
						sanitize_text_field(
							wp_unslash(
								$_POST['dk_variable_description'][ $variation_id ]
							)
						)
					);
				}

				if ( isset( $_POST['dk_variable_price_override'][ $variation_id ] ) ) {
					$variation->update_meta_data(
						'1984_dk_woo_variable_price_override',
						1
					);
					if ( isset( $_POST['dk_variable_price'][ $variation_id ] ) ) {
						$variation->set_regular_price(
							sanitize_text_field(
								wp_unslash(
									$_POST['dk_variable_price'][ $variation_id ]
								)
							)
						);
					}
					if ( isset( $_POST['dk_variable_sale_price'][ $variation_id ] ) ) {
						$variation->set_sale_price(
							sanitize_text_field(
								wp_unslash(
									$_POST['dk_variable_sale_price'][ $variation_id ]
								)
							)
						);
					}
				} else {
					self::reset_variation_price( $variation );
				}

				if ( isset( $_POST['dk_variable_inventory_override'][ $variation_id ] ) ) {
					$variation->update_meta_data(
						'1984_dk_woo_variable_inventory_override',
						'true'
					);

					if (
						isset( $_POST['dk_variable_quantity_track_in_wc'][ $variation_id ] ) &&
						isset( $_POST['dk_variable_quantity'][ $variation_id ] ) &&
						isset( $_POST['dk_variable_override_allow_backorders_in_wc'][ $variation_id ] )
					) {

						$variation->update_meta_data(
							'1984_dk_woo_variable_quantity_track_in_wc',
							'true'
						);

						$variation->set_manage_stock( true );

						$variation->set_stock_quantity(
							(float) sanitize_text_field(
								wp_unslash(
									$_POST['dk_variable_quantity'][ $variation_id ]
								)
							)
						);

						$variation->set_backorders(
							sanitize_text_field(
								wp_unslash(
									$_POST['dk_variable_override_allow_backorders_in_wc'][ $variation_id ]
								)
							)
						);
					} else {
						self::reset_variation_stock( $variation );
					}
				} else {
					self::reset_variation_stock( $variation );
				}

				if ( isset( $_POST['dk_variable_image_id'][ $variation_id ] ) ) {
					$variation->set_image_id(
						intval(
							sanitize_text_field(
								wp_unslash(
									$_POST['dk_variable_image_id'][ $variation_id ]
								)
							)
						)
					);
				}

				$variation->save();
			}
		}
	}

	/**
	 * Reset the prices for a WooCommerce product variation
	 *
	 * Sets the variant price back to the main DK price of the product and
	 * enables price sync for the variant again.
	 *
	 * @param WC_Product_Variation $variation The WooCommerce product variation.
	 */
	private static function reset_variation_price(
		WC_Product_Variation $variation
	): void {
		$variation->delete_meta_data(
			'1984_dk_woo_variable_price_override',
		);

		$parent = wc_get_product( $variation->get_parent_id() );

		$price = $parent->get_meta( '1984_dk_woo_price' );

		if ( is_object( $price ) ) {
			$variation->set_regular_price( $price->price );
			$variation->set_sale_price( $price->sale_price );
			$variation->set_date_on_sale_from( $price->date_on_sale_from );
			$variation->set_date_on_sale_to( $price->date_on_sale_to );

			$variation->save();
		}
	}

	/**
	 * Reset the stock quantity and inventory for product variation
	 *
	 * Sets the variant quantity to back to what is defined in DK and enbales
	 * quantity sync for the variant.
	 *
	 * @param WC_Product_Variation $variation The WooCommerce product variation.
	 */
	private static function reset_variation_stock(
		WC_Product_Variation $variation,
	): void {
		$parent = wc_get_product( $variation->get_parent_id() );

		$variation->delete_meta_data( '1984_dk_woo_variable_inventory_override' );
		$variation->delete_meta_data( '1984_dk_woo_variable_quantity_track_in_wc' );

		$variation->set_manage_stock( $parent->get_manage_stock() );
		$variation->set_backorders( $parent->get_backorders() );

		$product_json = json_decode(
			$parent->get_meta( '1984_dk_woo_product_json' )
		);

		$variation->set_stock_quantity(
			ImportProducts::get_variation_quantity_from_json(
				$product_json,
				array_values( $variation->get_attributes() )
			)
		);

		$variation->save();
	}

	/**
	 * Set the default product attributes via the $_POST superglobal
	 *
	 * Checks the nonce value and sets the default variation for a product via
	 * $_POST['dk_variable_defaults'].
	 *
	 * @param WC_Product_Variable $wc_product The WooCommerce product.
	 */
	private static function set_default_attributes_via_post(
		WC_Product_Variable $wc_product
	): void {
		if (
			! isset( $_POST['set_1984_woo_dk_variations_nonce'] ) ||
			! wp_verify_nonce(
				sanitize_text_field(
					wp_unslash( $_POST['set_1984_woo_dk_variations_nonce'] )
				),
				'set_1984_woo_dk_variations'
			) ||
			! isset( $_POST['dk_variable_defaults'] )
		) {
			return;
		}

		$variation_attributes = array_keys( $wc_product->get_variation_attributes() );
		$variation_defaults   = array();

		foreach ( $variation_attributes as $attribute ) {
			if (
				isset( $_POST['dk_variable_defaults'][ $attribute ] ) &&
				is_string( $_POST['dk_variable_defaults'][ $attribute ] )
			) {
				$variation_defaults[ $attribute ] = sanitize_text_field(
					wp_unslash(
						$_POST['dk_variable_defaults'][ $attribute ]
					)
				);
			}
		}

		$wc_product->set_default_attributes( $variation_defaults );

		$wc_product->save();
	}
}
