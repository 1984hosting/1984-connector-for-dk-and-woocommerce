<?php

declare(strict_types = 1);

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

<div id="dk_variations_tab" class="panel hidden" style="width: 80%; float: left;">
	<div class="inline notice woocommerce-message show_if_variable">
		<img class="info-icon" src="<?php echo esc_url( WC_ADMIN_IMAGES_FOLDER_URL . '/icons/info.svg' ); ?>" />
		<p style="margin-left: 1em;">
			With the 1984 Connector for DK and WooCommerce enabled, it is not possible to edit or delete product variations directly via WooCommerce. You will need to use DK's own ‘Product Variations’ feature to remove or edit variations. This may not be enabled by default as a feature in your DK installation.
		</p>
	</div>

	<div class="dk-variations" style="margin-top: 2em;">
		<?php foreach ( $wc_variable_product->get_available_variations( 'objects' ) as $i => $variation ) : ?>
		<div class="dk-variation" style="padding: 1em 2em; border-top: 1px solid #eee;">
			<h3 style="margin: 0em; margin-bottom: 0.5em;">#<?php echo esc_html( $variation->get_id() ); ?> - <?php echo esc_html( implode( ', ', $variation->get_attributes() ) ); ?></h3>

			<div style="margin-top: 1em;">
				<img
					src="<?php echo $variation->get_image_id( 'edit' ) ? esc_url( wp_get_attachment_thumb_url( $variation->get_image_id( 'edit' ) ) ) : esc_url( wc_placeholder_img_src() ); ?>"
					width="100px"
					height="100px"
					style="border: 1px solid #ccc; border-radius: 4px;"
				/>
			</div>

			<div>
				<ul>
					<li>
						<strong>Stock quantity:</strong>
						<?php echo esc_html( $variation->get_stock_quantity( 'edit' ) ); ?>
					</li>
				</ul>
			</div>

			<div class="" style="display: flex;">
				<div class="" style="margin-right: 2em;">
					<label>
						<input
							type="checkbox"
							class="checkbox"
							name="dk_variable_enabled[<?php echo esc_attr( $variation->get_id() ); ?>]"
							<?php checked( in_array( $variation->get_status( 'edit' ), array( 'publish', false ), true ), true ); ?>
						/>
						Enalbed
					</label>
				</div>
				<div class="" style="margin-right: 2em;">
					<label>
						<input
							type="checkbox"
							class="checkbox variable_is_downloadable"
							name="dk_variable_is_downloadable[<?php echo esc_attr( $i ); ?>]"
							<?php checked( $variation->get_downloadable( 'edit' ), true ); ?>
						/>
						Downloadable
					</label>
				</div>
				<div class="" style="margin-right: 2em;">
					<label>
						<input
							type="checkbox"
							class="checkbox variable_is_virtual"
							name="dk_variable_is_virtual[<?php echo esc_attr( $i ); ?>]"
							<?php checked( $variation->get_virtual( 'edit' ), true ); ?>
						/>
						Virtual
					</label>
				</div>
			</div>

			<div>
				<?php
				woocommerce_wp_textarea_input(
					array(
						'id'            => "variable_description{$i}",
						'name'          => "variable_description[{$i}]",
						'value'         => $variation->get_description( 'edit' ),
						'label'         => 'Description (not synced with DK)',
						'desc_tip'      => true,
						'description'   => 'Enter an optional description for this variation.',
						'wrapper_class' => 'form-row form-row-full',
					)
				);
				?>
			</div>
		</div>
		<?php endforeach ?>
	</div>
</div>
