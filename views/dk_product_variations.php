<?php

declare(strict_types = 1);

use NineteenEightyFour\NineteenEightyWoo\Config;
use NineteenEightyFour\NineteenEightyWoo\Helpers\Product as ProductHelper;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Get the product via conventional means.
$wc_product = wc_get_product();

// Use the WC_Product_Variable class to make sure our development environment
// understands that we are dealing with a variable product and enables syntax
// highlihting for instances of that class.
$wc_variable_product = new WC_Product_Variable( $wc_product );

?>

<div id="dk_variations_tab" class="panel hidden">
	<?php wp_nonce_field( 'set_1984_woo_dk_variations', 'set_1984_woo_dk_variations_nonce' ); ?>

	<div class="inline notice woocommerce-message show_if_variable">
		<img
			class="info-icon"
			src="<?php echo esc_url( WC_ADMIN_IMAGES_FOLDER_URL . '/icons/info.svg' ); ?>"
		/>
		<p>
			<?php
			echo esc_html(
				__(
					"DK's own ‘Product Variations’ feature is required to add or remove product variations that originate in DK.",
					'1984-dk-woo'
				)
			);
			?>
		</p>
	</div>

	<div class="dk-variations-defaults">
		<h3><?php echo esc_html( __( 'Default Attributes', '1984-dk-woo' ) ); ?></h3>
		<div class="dk-variation-default">
			<?php foreach ( $wc_product->get_attributes( 'edit' ) as $key => $attribute ) : ?>
			<label>
				<span>
					<?php echo Config::get_use_attribute_description() ? esc_html( ProductHelper::attribute_label_description( $wc_product, $key ) ) : esc_html( $key ); ?>
				</span>
				<select
					name="dk_variable_defaults[<?php echo esc_attr( $key ); ?>]"
				>
					<option value=""><?php echo esc_html( __( '(None)', '1984-dk-woo' ) ); ?></option>
					<?php foreach ( $attribute->get_options() as $option ) : ?>
					<option
						value="<?php echo esc_attr( $option ); ?>"
						<?php selected( $option === $wc_product->get_default_attributes()[ $key ] ); ?>
					>
						<?php echo Config::get_use_attribute_value_description() ? esc_html( ProductHelper::attribute_value_description( $wc_product, $key, $option ) ) : esc_html( $option ); ?>
					</option>
					<?php endforeach ?>
				</select>
			</label>
			<?php endforeach ?>
		</div>
	</div>

	<div class="dk-variations">
		<?php foreach ( $wc_variable_product->get_children() as $variation_id ) : ?>
			<?php $variation = new WC_Product_Variation( $variation_id ); ?>
			<div
				id="dk-variation-<?php echo esc_attr( $variation_id ); ?>"
				class="dk-variation"
				data-menu-order="<?php echo esc_attr( $variation->get_menu_order() ); ?>"
				style="order: <?php echo esc_attr( $variation->get_menu_order() ); ?>;"
			>
				<h3>
					<?php
					echo esc_html(
						sprintf(
							// Translators: The %d is the variation ID.
							__( 'Variation #%d', '1984-dk-woo' ),
							$variation_id
						)
					);
					?>
				</h3>

				<div class="dk-variation-image">
					<button
						class="add-thumbnail-button"
						title="<?php echo esc_html( __( 'Replace Image', '1984-dk-woo' ) ); ?>"
						data-variation-thumbnail-for="<?php echo esc_html( $variation_id ); ?>"
					>
						<img
							src="<?php echo $variation->get_image_id( 'edit' ) ? esc_url( wp_get_attachment_image_url( $variation->get_image_id( 'edit' ), '1984_dk_woo_variant' ) ) : esc_url( wc_placeholder_img_src() ); ?>"
							alt="<?php echo $variation->get_image_id( 'edit' ) ? esc_attr( get_post_meta( $variation->get_image_id( 'edit' ), '_wp_attachment_image_alt', true ) ) : ''; ?>"
							data-placeholer-src="<?php echo esc_url( wc_placeholder_img_src() ); ?>"
						/>
					</button>
					<button
						class="remove-thumbnail-button button button-small button-secondary"
						data-remove-thumbnail-for="<?php echo esc_html( $variation_id ); ?>"
						<?php disabled( empty( $variation->get_image_id( 'edit' ) ) ); ?>
					>
						<?php echo esc_html( __( 'Remove Image', '1984-dk-woo' ) ); ?>
					</button>
					<input
						type="hidden"
						name="dk_variable_image_id[<?php echo esc_html( $variation_id ); ?>]"
						value="<?php echo esc_attr( $variation->get_image_id( 'edit' ) ); ?>"
						disabled
						data-variation-image-id-for="<?php echo esc_attr( $variation_id ); ?>"
					/>
				</div>

				<div class="dk-variation-fields">
					<ul class="dk-variation-props">
						<?php foreach ( ProductHelper::attributes_with_descriptions( $variation ) as $label => $value ) : ?>
						<li>
							<strong><?php echo esc_html( $label ); ?></strong>
							<span><?php echo esc_html( $value ); ?></span>
						</li>
						<?php endforeach ?>
					</ul>
					<div class="dk-variation-options">
						<div class="dk-variation-checkbox">
							<label>
								<input
									type="checkbox"
									name="dk_variable_price_override[<?php echo esc_attr( $variation_id ); ?>]"
									data-variation-price-checkbox-for="<?php echo esc_attr( $variation_id ); ?>"
									<?php checked( ProductHelper::variation_price_override( $variation ) ); ?>
								/>
								<span>
									<?php echo esc_html( __( 'Override price for this product variation', '1984-dk-woo' ) ); ?>
								</span>
							</label>
						</div>
						<div
							class="dk-variation-fields dk-variation-prices <?php echo ( ! ProductHelper::variation_price_override( $variation ) ) ? 'hidden' : ''; ?>"
							data-variation-price-fields-for="<?php echo esc_attr( $variation_id ); ?>"
						>
							<div class="dk-variation-field">
								<label>
									<span>
										<?php
										echo esc_html(
											sprintf(
												// Translators: The %s is the currency symbol for WooCommerce.
												__( 'Price (%s)', '1984-dk-woo' ),
												get_woocommerce_currency_symbol()
											)
										);
										?>
									</span>
									<input
										type="number"
										name="dk_variable_price[<?php echo esc_attr( $variation_id ); ?>]"
										value="<?php echo esc_attr( $variation->get_regular_price( 'edit' ) ); ?>"
										min="0"
										<?php disabled( ! ProductHelper::variation_price_override( $variation ) ); ?>
									/>
								</label>
							</div>
							<div class="dk-variation-field">
								<label>
									<span>
										<?php
										echo esc_html(
											sprintf(
												// Translators: The %s is the currency symbol for WooCommerce.
												__( 'Sale Price (%s)', '1984-dk-woo' ),
												get_woocommerce_currency_symbol()
											)
										);
										?>
									</span>
									<input
										type="number"
										name="dk_variable_sale_price[<?php echo esc_attr( $variation_id ); ?>]"
										value="<?php echo esc_attr( $variation->get_sale_price( 'edit' ) ); ?>"
										min="0"
										<?php disabled( ! ProductHelper::variation_price_override( $variation ) ); ?>
									/>
								</label>
							</div>
							<div class="dk-variation-field">
								<label>
									<span>
										<?php
										echo esc_html(
											__( 'On sale from', '1984-dk-woo' ),
										);
										?>
									</span>
									<input
										type="date"
										name="dk_variable_on_sale_from[<?php echo esc_attr( $variation_id ); ?>]"
										value="<?php echo $variation->get_date_on_sale_from( 'edit' ) ? esc_attr( $variation->get_date_on_sale_from( 'edit' )->format( 'Y-m-d' ) ) : ''; ?>"
										min="0"
									/>
								</label>
							</div>
							<div class="dk-variation-field">
								<label>
									<span>
										<?php
										echo esc_html(
											__( 'On sale to', '1984-dk-woo' ),
										);
										?>
									</span>
									<input
										type="date"
										name="dk_variable_on_sale_to[<?php echo esc_attr( $variation_id ); ?>]"
										value="<?php echo $variation->get_date_on_sale_to( 'edit' ) ? esc_attr( $variation->get_date_on_sale_to( 'edit' )->format( 'Y-m-d' ) ) : ''; ?>"
										min="0"
									/>
								</label>
							</div>
						</div>
					</div>

					<div class="dk-variation-options">
						<div class="dk-variation-checkbox">
							<label>
								<input
									type="checkbox"
									name="dk_variable_inventory_override[<?php echo esc_attr( $variation_id ); ?>]"
									data-variation-inventory-checkbox-for="<?php echo esc_attr( $variation_id ); ?>"
									<?php checked( ProductHelper::variation_inventory_override( $variation ) ); ?>
								/>
								<span>
									<?php echo esc_html( __( 'Override inventory', '1984-dk-woo' ) ); ?>
								</span>
							</label>
						</div>
						<div
							class="dk-variation-fields dk-variation-inventory <?php echo ( ! ProductHelper::variation_inventory_override( $variation ) ) ? 'hidden' : ''; ?>"
							data-variation-inventory-fields-for="<?php echo esc_attr( $variation_id ); ?>"
						>
							<div class="dk-variation-subcheckbox">
								<label>
									<input
										type="checkbox"
										name="dk_variable_quantity_track_in_wc[<?php echo esc_attr( $variation_id ); ?>]"
										data-variation-inventory-qty-checkbox-for="<?php echo esc_attr( $variation_id ); ?>"
										<?php disabled( ! ProductHelper::variation_inventory_override( $variation ) ); ?>
										<?php checked( ProductHelper::variation_inventory_track_in_wc( $variation ) ); ?>
									/>
									<span>
										<?php echo esc_html( __( 'Set stock quantity', '1984-dk-woo' ) ); ?>
									</span>
								</label>
							</div>
							<div
								class="togglable <?php echo ( ProductHelper::variation_inventory_track_in_wc( $variation ) ) ? '' : 'hidden'; ?>"
							>
								<div class="dk-variation-field">
									<label>
										<span>
											<?php echo esc_html( __( 'Quantity', '1984-dk-woo' ) ); ?>
										</span>
										<input
											type="number"
											name="dk_variable_quantity[<?php echo esc_attr( $variation_id ); ?>]"
											value="<?php echo esc_attr( $variation->get_stock_quantity( 'edit' ) ); ?>"
											<?php disabled( ! ProductHelper::variation_inventory_track_in_wc( $variation ) ); ?>
										/>
									</label>
								</div>
								<fieldset>
									<legend>
										<?php echo esc_html( __( 'Backorders', '1984-dk-woo' ) ); ?>
									</legend>
									<div class="dk-variation-subcheckbox">
										<label>
											<input
												type="radio"
												value="no"
												name="dk_variable_override_allow_backorders_in_wc[<?php echo esc_attr( $variation_id ); ?>]"
												<?php checked( $variation->get_backorders() === 'no' ); ?>
												<?php disabled( ! ProductHelper::variation_inventory_override( $variation ) || ! $variation->get_manage_stock( 'edit' ) ); ?>
											/>
											<span>
												<?php echo esc_html( __( 'Do not allow backorders', '1984-dk-woo' ) ); ?>
											</span>
										</label>
									</div>
									<div class="dk-variation-subcheckbox">
										<label>
											<input
												type="radio"
												value="notify"
												name="dk_variable_override_allow_backorders_in_wc[<?php echo esc_attr( $variation_id ); ?>]"
												<?php checked( $variation->get_backorders() === 'notify' ); ?>
												<?php disabled( ! ProductHelper::variation_inventory_override( $variation ) || ! $variation->get_manage_stock( 'edit' ) ); ?>
											/>
											<span>
												<?php echo esc_html( __( 'Allow backorders, but notify customer', '1984-dk-woo' ) ); ?>
											</span>
										</label>
									</div>
									<div class="dk-variation-subcheckbox">
										<label>
											<input
												type="radio"
												value="yes"
												name="dk_variable_override_allow_backorders_in_wc[<?php echo esc_attr( $variation_id ); ?>]"
												<?php checked( $variation->get_backorders() === 'yes' ); ?>
												<?php disabled( ! ProductHelper::variation_inventory_override( $variation ) || ! $variation->get_manage_stock( 'edit' ) ); ?>
											/>
											<span>
												<?php echo esc_html( __( 'Allow backorders', '1984-dk-woo' ) ); ?>
											</span>
										</label>
									</div>
								</fieldset>
							</div>
						</div>
					</div>

					<div class="dk-variation-textarea">
						<label>
							<span>
								<?php echo esc_html( __( 'Description', '1984-dk-woo' ) ); ?>
							</span>
							<textarea
								name="dk_variable_description[<?php echo esc_attr( $variation_id ); ?>]"
							><?php echo esc_attr( $variation->get_description( 'edit' ) ); ?></textarea>
						</label>
					</div>

					<div class="dk-variation-checkboxes">
						<div class="dk-variation-checkbox">
							<label>
								<input
									type="checkbox"
									class="checkbox"
									name="dk_variable_enabled[<?php echo esc_attr( $variation_id ); ?>]"
									<?php checked( in_array( $variation->get_status( 'edit' ), array( 'publish', false ), true ), true ); ?>
								/>
								<span>
									<?php echo esc_html( __( 'Enabled', '1984-dk-woo' ) ); ?>
								</span>
							</label>
						</div>
						<div class="dk-variation-checkbox">
							<label>
								<input
									type="checkbox"
									class="checkbox variable_is_downloadable"
									name="dk_variable_is_downloadable[<?php echo esc_attr( $variation_id ); ?>]"
									<?php checked( $variation->get_downloadable( 'edit' ), true ); ?>
								/>
								<span>
									<?php echo esc_html( __( 'Downloadable', '1984-dk-woo' ) ); ?>
								</span>
							</label>
						</div>
						<div class="dk-variation-checkbox">
							<label>
								<input
									type="checkbox"
									class="checkbox variable_is_virtual"
									name="dk_variable_is_virtual[<?php echo esc_attr( $variation_id ); ?>]"
									<?php checked( $variation->get_virtual( 'edit' ), true ); ?>
								/>
								<span>
									<?php echo esc_html( __( 'Virtual', '1984-dk-woo' ) ); ?>
								</span>
							</label>
						</div>
					</div>
					<div class="dk-variation-textinput">
						<label>
							<span>
								<?php echo esc_attr( __( 'Menu Order', '1984-dk-woo' ) ); ?>
							</span>
							<input
								class="tiny"
								type="number"
								name="dk_variable_menu_order[<?php echo esc_attr( $variation_id ); ?>]"
								value="<?php echo esc_attr( $variation->get_menu_order() ); ?>"
								data-menu-order-for="<?php echo esc_attr( $variation_id ); ?>"
							/>
						</label>
					</div>
				</div>
			</div>
		<?php endforeach ?>
	</div>
</div>
