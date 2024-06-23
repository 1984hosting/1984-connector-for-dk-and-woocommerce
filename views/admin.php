<?php

declare(strict_types = 1);

use NineteenEightyFour\NineteenEightyWoo\Hooks\Admin;
use NineteenEightyFour\NineteenEightyWoo\Config;
use NineteenEightyFour\NineteenEightyWoo\Import\SalesPayments;
use NineteenEightyFour\NineteenEightyWoo\Hooks\KennitalaField;

$wc_payment_gateways = new WC_Payment_Gateways();

?>
<div
	class="wrap nineteen-eighty-woo-wrap"
	id="nineteen-eighty-woo-wrap"
>
	<form
		id="nineteen-eighty-woo-settings-form"
		class="type-form"
		novalidate
	>
		<h1 class="wp-heading-inline">
			<?php esc_html_e( '1984 Connector for DK and WooCommerce', '1984-dk-woo' ); ?>
		</h1>
		<section class="section">
			<h2><?php esc_html_e( 'Authentication', '1984-dk-woo' ); ?></h2>
			<table id="api-key-form-table" class="form-table">
				<tbody>
					<tr>
						<th scope="row">
							<label for="nineteen-eighty-woo-key-input">
								<?php esc_html_e( 'API Key', '1984-dk-woo' ); ?>
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

							<p class="validity valid"><?php esc_html_e( 'Valid', '1984-dk-woo' ); ?><span class="dashicons dashicons-yes"></span></p>
							<p class="validity invalid"><?php esc_html_e( 'This is a required field', '1984-dk-woo' ); ?></p>

							<p class="description">
								<?php
								esc_html_e(
									'The API key is provided by DK for use with the DK API. Do not share this key with anyone.',
									'1984-dk-woo'
								)
								?>
							</p>
						</td>
					</tr>
				</tbody>
			</table>
		</section>

		<section class="section">
			<h2><?php esc_html_e( 'Product Sync Defaults', '1984-dk-woo' ); ?></h2>
			<p><?php esc_html_e( 'This is where you set the default options for syncing your WooCommerce products. For example, if you do not want to overwrite the prices or names of your current WooCommerce products by default you do it here.', '1984-dk-woo' ); ?></p>
			<table id="dk-product-defaults-table" class="form-table">
				<tbody>
					<tr>
						<th span="row" class="column-title column-primary">
						</th>
						<td>
							<input
								id="product_price_sync_field"
								name="product_price_sync"
								type="checkbox"
								<?php echo esc_attr( Config::get_product_price_sync() ? 'checked' : '' ); ?>
							/>
							<label for="product_price_sync_field">
								<?php esc_html_e( 'Sync Product Prices with DK', '1984-dk-woo' ); ?>
							</label>
							<p class="description">
								<?php esc_html_e( 'If enabled, product prices and sales periods are synced by default between DK and WooCommerce. This can be overriden on a per-product basis. Prices based on foreign currency conversion are only synced ‘downstream’ from DK and into WooCommerce.', '1984-dk-woo' ); ?>
							</p>
						</td>
					</tr>
					<tr>
						<th span="row" class="column-title column-primary">
						</th>
						<td>
							<input
								id="product_quantity_sync_field"
								name="product_quantity_sync"
								type="checkbox"
								<?php echo esc_attr( Config::get_product_quantity_sync() ? 'checked' : '' ); ?>
							/>
							<label for="product_quantity_sync_field">
								<?php esc_html_e( 'Sync Stock Status and Quantity with DK', '1984-dk-woo' ); ?>
							</label>
							<p class="description">
								<?php esc_html_e( 'If enabled, product stock status and quantity is synced between DK and WooCommerce by default. This can be overridden on a per-product basis. Note that stock status and quantity sync only works ‘downstream’ from DK and into WooCommerce, but not ‘upstream’ due to limitations in DK.', '1984-dk-woo' ); ?>
							</p>
						</td>
					</tr>
					<tr>
						<th span="row" class="column-title column-primary">
						</th>
						<td>
							<input
								id="product_name_sync_field"
								name="product_name_sync"
								type="checkbox"
								<?php echo esc_attr( Config::get_product_name_sync() ? 'checked' : '' ); ?>
							/>
							<label for="product_name_sync_field">
								<?php esc_html_e( 'Sync Product Names with DK', '1984-dk-woo' ); ?>
							</label>
							<p class="description">
								<?php esc_html_e( 'If enabled, product names are synced between DK and WooCommerce. Disable this if you would like to be able to use separate product names in your WooCommerce shop from the ones in DK.', '1984-dk-woo' ); ?>
							</p>
						</td>
					</tr>
				</tbody>
			</table>
		</section>

		<section class="section">
			<h2><?php esc_html_e( 'Invoices', '1984-dk-woo' ); ?></h2>
			<p>
				<?php
				esc_html_e(
					'DK treats shipping and other costs as line items on invoices. In order for them to work, you need to assign a SKU to each of the following services.',
					'1984-dk-woo'
				);
				?>
			</p>
			<table id="dk-invoices-table" class="form-table">
				<tbody>
				<tr>
						<th span="row" class="column-title column-primary">
							<label for="default_kennitala_field">
								<?php
								esc_html_e(
									'Default Customer Kennitala',
									'1984-dk-woo'
								);
								?>
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
									'The default kennitala is used for guest customers that don\'t have or supply a kennitala during checkout. This should correspond with a DK customer record called “Various Customers” etc.',
									'1984-dk-woo'
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
									'Enable Kennitala Field in the Checkout Form',
									'1984-dk-woo'
								);
								?>
							</label>
						</td>
					</tr>
					<tr>
						<th span="row" class="column-title column-primary">
						</th>
						<td>
							<input
								id="customer_requests_kennitala_invoice_field"
								name="customer_requests_kennitala_invoice"
								type="checkbox"
								<?php echo esc_attr( Config::get_customer_requests_kennitala_invoice() ? 'checked' : '' ); ?>
							/>
							<label for="customer_requests_kennitala_invoice_field">
								<?php
								esc_html_e(
									'Customers Need to Request to have a Kennitala on Invoices',
									'1984-dk-woo'
								);
								?>
							</label>
							<p class="description">
								<?php
								esc_html_e(
									'If this is enabled, a checkbox is added to the checkout form, that the customer needs to tick in order to have a kennitala assigned to their invoice.',
									'1984-dk-woo'
								);
								?>
							</p>
						</td>
					</tr>
					<tr>
						<th span="row" class="column-title column-primary">
						</th>
						<td>
							<input
								id="make_invoice_if_kennitala_is_set_field"
								name="make_invoice_if_kennitala_is_set"
								type="checkbox"
								<?php echo esc_attr( Config::get_make_invoice_if_kennitala_is_set() ? 'checked' : '' ); ?>
							/>
							<label for="make_invoice_if_kennitala_is_set_field">
								<?php
								esc_html_e(
									'Create Invoices Automatically for Orders With a Kennitala',
									'1984-dk-woo'
								);
								?>
							</label>
							<p class="description">
								<?php
								esc_html_e(
									'When a customer requests to have a kennitala assigned to an invoice, a customer record is created in DK if it does not already exist, using the billing information supplied for the order.',
									'1984-dk-woo'
								);
								?>
							</p>
						</td>
					</tr>
					<tr>
						<th span="row" class="column-title column-primary">
						</th>
						<td>
							<input
								id="make_invoice_if_kennitala_is_missing_field"
								name="make_invoice_if_kennitala_is_missing"
								type="checkbox"
								<?php echo esc_attr( Config::get_make_invoice_if_kennitala_is_missing() ? 'checked' : '' ); ?>
							/>
							<label for="make_invoice_if_kennitala_is_missing_field">
								<?php
								esc_html_e(
									'Create Invoices Automatically for Orders Without a Kennitala',
									'1984-dk-woo'
								);
								?>
							</label>
							<p class="description">
								<?php
								esc_html_e(
									'If this is enabled, orders without a kennitala will be assigned the ‘Default Customer Kennitala’.',
									'1984-dk-woo'
								);
								?>
							</p>
						</td>
					</tr>
					<tr>
						<th span="row" class="column-title column-primary">
						</th>
						<td>
							<input
								id="email_invoice_field"
								name="email_invoice"
								type="checkbox"
								<?php echo esc_attr( Config::get_email_invoice() ? 'checked' : '' ); ?>
							/>
							<label for="email_invoice_field">
								<?php
								esc_html_e(
									'Send Invoices Automatically via Email',
									'1984-dk-woo'
								);
								?>
							</label>
							<p class="description">
								<?php
								esc_html_e(
									'If enabled, an email containing the invoice will be sent to the customer automatically after checkout.',
									'1984-dk-woo'
								);
								?>
							</p>
						</td>
					</tr>
					<tr>
						<th span="row" class="column-title column-primary">
							<label for="shipping_sku_field">
								<?php esc_html_e( 'Shipping SKU', '1984-dk-woo' ); ?>
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
							<label for="cost_sku_field">
								<?php esc_html_e( 'Cost SKU', '1984-dk-woo' ); ?>
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
					<tr>
						<th span="row" class="column-title column-primary">
							<label for="default_sales_person_field">
								<?php esc_html_e( 'Default Sales Person Number', '1984-dk-woo' ); ?>
							</label>
						</th>
						<td>
							<input
								id="default_sales_person_field"
								name="default_sales_person"
								type="text"
								value="<?php echo esc_attr( Config::get_default_sales_person_number() ); ?>"
							/>
						</td>
					</tr>

				</tbody>
			</table>
		</section>

		<section class="section">
			<h2><?php esc_html_e( 'Payment Gateways', '1984-dk-woo' ); ?></h2>
			<p><?php esc_html_e( 'Please select the payment method name for each payment gateway as it appears in DK as well as the payment mode:', '1984-dk-woo' ); ?></p>
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
							<td>
								<select
									id="payment_mode_input_<?php echo esc_attr( $p->id ); ?>"
									name="payment_mode"
								>
									<?php foreach ( SalesPayments::DK_PAYMENT_MODES as $payment_mode ) : ?>
										<option
											value="<?php echo esc_attr( $payment_mode ); ?>"
											<?php echo esc_attr( Config::payment_mode_matches( $p->id, $payment_mode ) ? 'selected="true"' : '' ); ?>
										>
											<?php
											echo esc_attr(
												SalesPayments::get_payment_mode_name(
													$payment_mode
												)
											);
											?>
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
					esc_html( __( 'The payment gateways themselves are handled by your WooCommerce Settings, under %1$sthe Payments Section%2$s.', '1984-dk-woo' ) ),
					'<a href="' . esc_url( admin_url( '?page=wc-settings&tab=checkout ' ) ) . '">',
					'</a>'
				);
				?>
			</p>
		</section>

		<section class="section">
			<h2><?php esc_html_e( 'Ledger Codes', '1984-dk-woo' ); ?></h2>
			<p>
				<?php
				esc_html_e(
					'This only applies to products created in WooCommerce and is managed in DK after creation.',
					'1984-dk-woo'
				);
				?>
			</p>
			<table id="dk-ledger-codes-table" class="form-table">
				<tbody>
					<tr>
						<th span="row" class="column-title column-primary">
							<label for="ledger_code_standard_field">
								<?php esc_html_e( 'Products With Standard Tax Rate', '1984-dk-woo' ); ?>
							</label>
						</th>
						<td>
							<input
								id="ledger_code_standard_field"
								name="ledger_code_standard"
								type="text"
								value="<?php echo esc_attr( Config::get_ledger_code( 'standard' ) ); ?>"
							/>
						</td>
					</tr>
					<tr>
						<th span="row" class="column-title column-primary">
							<label for="ledger_code_reduced_field">
								<?php esc_html_e( 'Products With Reduced Tax Rate', '1984-dk-woo' ); ?>
							</label>
						</th>
						<td>
							<input
								id="ledger_code_reduced_field"
								name="ledger_code_reduced"
								type="text"
								value="<?php echo esc_attr( Config::get_ledger_code( 'reduced' ) ); ?>"
							/>
						</td>
					</tr>
				</tbody>
			</table>
		</section>

		<div class="submit-container">
			<div id="nineteen-eighty-woo-settings-error" class="hidden" aria-live="polite">
				<p>
					<?php
					echo sprintf(
						// Translators: The %1$s and %2$s indicate an opening and closing <strong> tag.
						esc_html( __( '%1$sError:%2$s Please check if all the information was entered correctly and try again.', '1984-dk-woo' ) ),
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
				value="<?php esc_attr_e( 'Save', '1984-dk-woo' ); ?>"
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
				'1984-dk-woo'
			);
			?>
		</p>
		<img
			alt="<?php esc_attr_e( 'Ninteen-Eighty-Four', '1984-dk-woo' ); ?>"
			src="<?php echo esc_attr( Admin::logo_url() ); ?>"
		/>
	</div>
</div>
