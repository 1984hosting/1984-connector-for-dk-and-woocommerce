<?php

declare(strict_types = 1);

use NineteenEightyFour\NineteenEightyWoo\Config;
use NineteenEightyFour\NineteenEightyWoo\Import\SalesPayments;
use NineteenEightyFour\NineteenEightyWoo\Hooks\KennitalaField;

$wc_payment_gateways = new WC_Payment_Gateways();

?>
<div
	class="wrap nineteen-eighty-woo-wrap"
	id="nineteen-eighty-woo-wrap"
>
	<form id="nineteen-eighty-woo-settings-form" class="type-form" novalidate>
		<h1 class="wp-heading-inline">
			<?php esc_html_e( '1984 DK Connection', 'NineteenEightyWoo' ); ?>
		</h1>
		<section class="section">
			<h2><?php esc_html_e( 'Authentication', 'NineteenEightyWoo' ); ?></h2>
			<table id="api-key-form-table" class="form-table">
				<tbody>
					<tr>
						<th scope="row">
							<label for="nineteen-eighty-woo-key-input">
								<?php esc_html_e( 'API Key', 'NineteenEightyWoo' ); ?>
							</label>
						</th>
						<td>
							<input
								id="nineteen-eighty-woo-key-input"
								class="regular-text api-key-input"
								name="api_key"
								type="text"
								value="<?php echo esc_attr( Config::get_dk_api_key() ); ?>"
								pattern="<?php echo esc_attr( Config::DK_API_KEY_REGEX ); ?>"
								required
							/>

							<p class="validity valid"><?php esc_html_e( 'Valid', 'NineteenEightyWoo' ); ?><span class="dashicons dashicons-yes"></span></p>
							<p class="validity invalid"><?php esc_html_e( 'This is a required field', 'NineteenEightyWoo' ); ?></p>

							<p class="description">
								<?php
								esc_html_e(
									'The API key is provided by DK for use with the DK API. Do not share this key with anyone.',
									'NineteenEightyWoo'
								)
								?>
							</p>
						</td>
					</tr>
				</tbody>
			</table>
		</section>

		<section class="section">
			<h2><?php esc_html_e( 'DK Record Prefixes', 'NineteenEightyWoo' ); ?></h2>
			<p><?php esc_html_e( 'If you wish to use a different prefixes for your customer and order numbers, you can choose them here. You can even leave them empty if you like. This will not work retroactively.', 'NineteenEightyWoo' ); ?></p>
			<table id="dk-record-prefixes-table" class="form-table">
				<tbody>
					<tr>
						<th span="row" class="column-title column-primary">
							<label for="customer_number_prefix_field">
								<?php esc_html_e( 'Customer Number Prefix', 'NineteenEightyWoo' ); ?>
							</label>
						</th>
						<td>
							<input
								id="customer_number_prefix_field"
								name="customer_number_prefix"
								type="text"
								value="<?php echo esc_attr( Config::get_customer_number_prefix() ); ?>"
							/>
						</td>
					</tr>
				</tbody>
			</table>
		</section>

		<section class="section">
			<h2><?php esc_html_e( 'WooCommerce Payment Gateways and DK Payment Methods IDs', 'NineteenEightyWoo' ); ?></h2>
			<p><?php esc_html_e( 'Please enter the Payment Method ID and Name for each payment gateway as it appears in DK:', 'NineteenEightyWoo' ); ?></p>
			<table id="payment-gateway-id-map-table" class="form-table">
				<tbody>
					<?php foreach ( $wc_payment_gateways->payment_gateways as $p ) : ?>
						<?php
						if ( 'no' === $p->enabled ) {
							continue;
						}
						$payment_map = Config::get_payment_mapping( $p->id );
						?>
						<tr data-gateway-id="<?php echo esc_attr( $p->id ); ?>">
							<th span="row" class="column-title column-primary">
								<label
									for="payment_id_input_<?php echo esc_attr( $p->id ); ?>"
									class="payment-gateway-title"
								>
									<?php echo esc_html( $p->title ); ?>
								</label>
							</th>
							<td class="method-id">
								<select
									id="payment_id_input_<?php echo esc_attr( $p->id ); ?>"
									name="payment_id"
								>
									<option></option>
									<?php foreach ( SalesPayments::get_methods() as $dk_method ) : ?>
										<option
											value="<?php echo esc_attr( $dk_method->dk_id ); ?>"
											<?php echo esc_attr( Config::payment_mapping_matches( $p->id, $dk_method->dk_id ) ? 'selected="true"' : '' ); ?>
										>
											<?php echo esc_attr( $dk_method->dk_name ); ?> (<?php echo esc_attr( $dk_method->dk_id ); ?>)
										</option>
									<?php endforeach ?>
								</select>
							</td>
						</tr>
					<?php endforeach ?>
				</tbody>
			</table>

			<p>
				<?php
				echo sprintf(
					// Translators: %1$s stands for the opening and %2$s <a> tag in a hyperlink to the WooCommerce Payment Settings page.
					esc_html( __( 'The payment gateways themselves are handled by your WooCommerce Settings, under %1$sthe Payments Section%2$s.', 'NineteenEightyWoo' ) ),
					'<a href="' . esc_url( admin_url( '?page=wc-settings&tab=checkout ' ) ) . '">',
					'</a>'
				);
				?>
			</p>
		</section>

		<section class="section">
			<h2><?php esc_html_e( 'SKUs for Services', 'NineteenEightyWoo' ); ?></h2>
			<p>
				<?php
				esc_html_e(
					'DK treats costs and coupon discounts as line items on invoices. In order for them to work, you need to assign a SKU to each of the following services. If any of them does not exsist, then it will be created in DK.',
					'NineteenEightyWoo'
				);
				?>
			</p>
			<table id="dk-service-sku-table" class="form-table">
				<tbody>
					<tr>
						<th span="row" class="column-title column-primary">
							<label for="shipping_sku_field">
								<?php esc_html_e( 'Shipping SKU', 'NineteenEightyWoo' ); ?>
							</label>
						</th>
						<td>
							<input
								id="shipping_sku_field"
								name="shipping_sku"
								type="text"
								value="<?php echo esc_attr( Config::get_shipping_sku() ); ?>"
							/>
						</td>
					</tr>
					<tr>
						<th span="row" class="column-title column-primary">
							<label for="coupon_sku_field">
								<?php esc_html_e( 'Coupon SKU', 'NineteenEightyWoo' ); ?>
							</label>
						</th>
						<td>
							<input
								id="coupon_sku_field"
								name="coupon_sku"
								type="text"
								value="<?php echo esc_attr( Config::get_coupon_sku() ); ?>"
							/>
						</td>
					</tr>
					<tr>
						<th span="row" class="column-title column-primary">
							<label for="cost_sku_field">
								<?php esc_html_e( 'Cost SKU', 'NineteenEightyWoo' ); ?>
							</label>
						</th>
						<td>
							<input
								id="cost_sku_field"
								name="cost_sku"
								type="text"
								value="<?php echo esc_attr( Config::get_cost_sku() ); ?>"
							/>
						</td>
					</tr>
				</tbody>
			</table>
		</section>

		<section class="section">
			<h2><?php esc_html_e( 'Kennitala Support and Orders Without Kennitala', 'NineteenEightyWoo' ); ?></h2>
			<table id="dk-kennitala-table" class="form-table">
				<tbody>
					<tr>
						<th span="row" class="column-title column-primary">
							<label for="default_kennitala_field">
								Default Kennitala
							</label>
						</th>
						<td>
							<input
								id="default_kennitala_field"
								name="default_kennitala"
								type="text"
								value="<?php echo esc_attr( KennitalaField::format_kennitala( Config::get_default_kennitala() ) ); ?>"
							/>
							<p class="description">
								<?php
								esc_html_e(
									'The default kennitala is used for identifying customers that don\'t have or supply a kennitala during checkout. This should correspond with a DK customer record called “Various Customers” etc.',
									'NineteenEightyWoo'
								)
								?>
							</p>
						</td>
					</tr>
					<tr>
						<th span="row" class="column-title column-primary">
						</th>
						<td>
							<input
								id="enable_kennitala_field"
								name="enable_kennitala"
								type="checkbox"
								<?php echo esc_attr( Config::get_kennitala_classic_field_enabled() ? 'checked' : '' ); ?>
							/>
							<label for="enable_kennitala_field">
								<?php
								esc_html_e(
									'Enable Kennitala Field in the “Classic” Shortcode Based Checkout Page',
									'NineteenEightyWoo'
								);
								?>
							</label>
						</td>
					</tr>
					<?php if ( function_exists( '__experimental_woocommerce_blocks_register_checkout_field' ) ): ?>
					<tr>
						<th span="row" class="column-title column-primary">
						</th>
						<td>
							<input
								id="enable_kennitala_in_block_field"
								name="enable_kennitala_in_block"
								type="checkbox"
								<?php echo esc_attr( Config::get_kennitala_block_field_enabled() ? 'checked' : '' ); ?>
							/>
							<label for="enable_kennitala_in_block_field">
								<?php
								esc_html_e(
									'Enable Kennitala Field in the Block Based Checkout Page (Experimental)',
									'NineteenEightyWoo'
								);
								?>
							</label>
							<p class="description">
								<?php
								esc_html_e(
									'WooCommerce 8.7 introduces a new block based checkout page. This uses a different approach to adding new fields, but it is still considered to be experimental by WooCommerce as of now. For instance, this does not automatically populate the field with the customer\'s kennitala, so it needs to be re-entered for each checkout.',
									'NineteenEightyWoo'
								)
								?>
							</p>
						</td>
					</tr>
					<?php endif ?>
				</tbody>
			</table>
		</section>


		<div class="submit-container">
			<div id="nineteen-eighty-woo-settings-error" class="hidden" aria-live="polite">
				<p>
					<?php
					echo sprintf(
						// Translators: The %1$s and %2$s indicate an opening and closing <strong> tag.
						esc_html( __( '%1$sError:%2$s Please check if all the information was entered correctly and try again.', 'NineteenEightyWoo' ) ),
						'<strong>',
						'</strong>'
					);
					?>
				</p>
			</div>
			<img
				id="nineteen-eighty-woo-settings-loader"
				class="loader hidden"
				src="<?php echo esc_url( get_admin_url() . 'images/wpspin_light-2x.gif' ); ?>"
				width="32"
				height="32"
			/>
			<input
				type="submit"
				value="<?php esc_attr_e( 'Save', 'NineteenEightyWoo' ); ?>"
				class="button button-primary button-hero"
				id="nineteen-eighty-woo-settings-submit"
			/>
		</div>
	</form>

	<div id="ninteen-eighty-four-logo-container">
		<p>
			<?php
			esc_html_e(
				'The 1984 DK Connection Plugin for WooCommerce is developed, maintained and supported on goodwill basis by 1984 Hosting as free software without any guarantees or obligations and is not affiliated with or supported by DK hugbúnaður ehf.',
				'NineteenEightyWoo'
			);
			?>
		</p>
		<img
			alt="<?php esc_attr_e( 'Ninteen-Eighty-Four', 'NineteenEightyWoo' ); ?>"
			src="<?php echo esc_attr( NineteenEightyFour\NineteenEightyWoo\Admin::logo_url() ); ?>"
		/>
	</div>
</div>
