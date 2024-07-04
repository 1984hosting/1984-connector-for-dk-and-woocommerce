<?php

declare(strict_types = 1);

use NineteenEightyFour\NineteenEightyWoo\Hooks\Admin;
use NineteenEightyFour\NineteenEightyWoo\Config;
use NineteenEightyFour\NineteenEightyWoo\Import\SalesPayments;
use NineteenEightyFour\NineteenEightyWoo\Hooks\KennitalaField;

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
			<p>
				<?php
				esc_html_e(
					'For creating an API key, we recommend creating a separate user with full priveleges, not connected to an actual employee in dkPlus and then generating an API key for that user under ‘Tokens’ in that user’s Settings page.',
					'1984-dk-woo'
				);
				?>
			</p>
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
			<h2><?php esc_html_e( 'Products', '1984-dk-woo' ); ?></h2>
			<h3><?php esc_html_e( 'Product Sync Defaults', '1984-dk-woo' ); ?></h3>
			<p><?php esc_html_e( 'This is where you set the default options for syncing your WooCommerce products. For example, if you do not want to overwrite the prices or names of your current WooCommerce products by default, or only fetch products labelled as ‘for online store’ you can do it here.', '1984-dk-woo' ); ?></p>
			<table id="dk-product-defaults-table" class="form-table">
				<tbody>
					<tr>
						<th scope="row" class="column-title column-primary">
						</th>
						<td>
							<input
								id="product_price_sync_field"
								name="product_price_sync"
								type="checkbox"
								<?php echo esc_attr( Config::get_product_price_sync() ? 'checked' : '' ); ?>
							/>
							<label for="product_price_sync_field">
								<?php esc_html_e( 'Sync Product Prices', '1984-dk-woo' ); ?>
							</label>
							<p class="description">
								<?php esc_html_e( 'If enabled, product prices and sales periods are synced by default between DK and WooCommerce. This can be overriden on a per-product basis. Prices based on foreign currency conversion are only synced ‘downstream’ from DK and into WooCommerce.', '1984-dk-woo' ); ?>
							</p>
						</td>
					</tr>
					<tr>
						<th scope="row" class="column-title column-primary">
						</th>
						<td>
							<input
								id="product_quantity_sync_field"
								name="product_quantity_sync"
								type="checkbox"
								<?php echo esc_attr( Config::get_product_quantity_sync() ? 'checked' : '' ); ?>
							/>
							<label for="product_quantity_sync_field">
								<?php esc_html_e( 'Sync Stock Status and Quantity from DK', '1984-dk-woo' ); ?>
							</label>
							<p class="description">
								<?php esc_html_e( 'If enabled, product stock status and quantity is synced between DK and WooCommerce by default. This can be overridden on a per-product basis. Note that stock status and quantity sync only works ‘downstream’ from DK and into WooCommerce, but not ‘upstream’ due to limitations in DK.', '1984-dk-woo' ); ?>
							</p>
						</td>
					</tr>
					<tr>
						<th scope="row" class="column-title column-primary">
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
					<tr>
						<th scope="row" class="column-title column-primary">
						</th>
						<td>
							<input
								id="import_nonweb_products_field"
								name="import_nonweb_products"
								type="checkbox"
								<?php echo esc_attr( Config::get_import_nonweb_products() ? 'checked' : '' ); ?>
							/>
							<label for="import_nonweb_products_field">
								<?php esc_html_e( 'Import New Non-Web Products from DK as Drafts', '1984-dk-woo' ); ?>
							</label>
							<p class="description">
								<?php esc_html_e( 'If enabled, products that are not labelled for online sales are imported into WooCommerce as drafts. Changing their status to ‘Published’ labels them for online sale in DK. This is not recommended if you have a lot of products in DK.', '1984-dk-woo' ); ?>
							</p>
						</td>
					</tr>
					<tr>
						<th scope="row" class="column-title column-primary">
						</th>
						<td>
							<input
								id="delete_inactive_products_field"
								name="delete_inactive_products"
								type="checkbox"
								<?php echo esc_attr( Config::get_delete_inactive_products() ? 'checked' : '' ); ?>
							/>
							<label for="delete_inactive_products_field">
								<?php esc_html_e( 'Delete Inactive Products from WooCommerce', '1984-dk-woo' ); ?>
							</label>
							<p class="description">
								<?php esc_html_e( 'If enabled, products that have been made inactive in DK are automatically deleted from WooCommerce.', '1984-dk-woo' ); ?>
							</p>
						</td>
					</tr>
				</tbody>
			</table>
			<h3><?php esc_html_e( 'Ledger Codes', '1984-dk-woo' ); ?></h3>
			<p>
				<?php
				esc_html_e(
					'When a product is created and published in WooCommerce, a corrsponding product record is created in DK. Setting the values below correctly makes sure that this happens without discrepancies or errors.',
					'1984-dk-woo'
				);
				?>
			</p>
			<table id="dk-ledger-codes-table" class="form-table dk-ledger-codes-table">
				<thead>
					<tr>
						<th></th>
						<th id="dk-ledger-codes-table-th-sale" scope="col"><?php esc_html_e( 'Sale', '1984-dk-woo' ); ?></th>
						<th id="dk-ledger-codes-table-th-purchase" scope="col"><?php esc_html_e( 'Purchase', '1984-dk-woo' ); ?></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<th scope="row" class="column-title column-primary">
							<?php esc_html_e( 'Products with Standard tax rate', '1984-dk-woo' ); ?>
						</th>
						<td>
							<label id="ledger_code_standard_field_label" for="ledger_code_standard_field">
								<?php esc_html_e( 'Sale Booking Category', '1984-dk-woo' ); ?>
							</label>
							<input
								aria-labelledby="dk-ledger-codes-table-th-sale"
								id="ledger_code_standard_field"
								name="ledger_code_standard"
								type="text"
								value="<?php echo esc_attr( Config::get_ledger_code( 'standard' ) ); ?>"
							/>
						</td>
						<td>
							<label id="ledger_code_standard_purchase_label" for="ledger_code_standard_purchase_field">
								<?php esc_html_e( 'Purchase Booking Category', '1984-dk-woo' ); ?>
							</label>
							<input
								aria-labelledby="dk-ledger-codes-table-th-sale-purchase"
								id="ledger_code_standard_purchase_field"
								name="ledger_code_standard_purchase"
								type="text"
								value="<?php echo esc_attr( Config::get_ledger_code( 'standard_purchase' ) ); ?>"
							/>
						</td>
					</tr>
					<tr>
						<th scope="row" class="column-title column-primary">
							<?php esc_html_e( 'Products With Reduced Tax Rate', '1984-dk-woo' ); ?>
						</th>
						<td>
							<label for="ledger_code_reduced_field">
								<?php esc_html_e( 'Sale Booking Category', '1984-dk-woo' ); ?>
							</label>
							<input
								id="ledger_code_reduced_field"
								name="ledger_code_reduced"
								type="text"
								value="<?php echo esc_attr( Config::get_ledger_code( 'reduced' ) ); ?>"
							/>
						</td>
						<td>
							<label for="ledger_code_reduced_purchase_field">
								<?php esc_html_e( 'Purchase Booking Category', '1984-dk-woo' ); ?>
							</label>
							<input
								id="ledger_code_reduced_purchase_field"
								name="ledger_code_reduced_purchase"
								type="text"
								value="<?php echo esc_attr( Config::get_ledger_code( 'reduced_purchase' ) ); ?>"
							/>
						</td>
					</tr>
				</tbody>
			</table>

		</section>

		<section class="section">
			<h2><?php esc_html_e( 'Invoices', '1984-dk-woo' ); ?></h2>
			<table id="dk-invoices-table" class="form-table">
				<tbody>
				<tr>
						<th scope="row" class="column-title column-primary">
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
							<?php $info_for_default_kennitala = Admin::info_for_default_kennitala(); ?>
							<p class="infotext <?php echo esc_attr( $info_for_default_kennitala->css_class ); ?>">
								<span class="dashicons <?php echo esc_attr( $info_for_default_kennitala->dashicon ); ?>"></span>
								<?php echo esc_html( $info_for_default_kennitala->text ); ?>
							</p>
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
						<th scope="row" class="column-title column-primary">
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
						<th scope="row" class="column-title column-primary">
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
						<th scope="row" class="column-title column-primary">
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
						<th scope="row" class="column-title column-primary">
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
						<th scope="row" class="column-title column-primary">
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
									'If this is enabled, a checkbox is added to the checkout form, that the customer needs to tick in order to have a kennitala assigned to their invoice, or the invoice will be treated like one with out a kennitala.',
									'1984-dk-woo'
								);
								?>
							</p>
						</td>
					</tr>
					<tr>
						<th scope="row" class="column-title column-primary">
						</th>
						<td>
							<input
								id="make_credit_invoice_field"
								name="make_credit_invoice"
								type="checkbox"
								<?php echo esc_attr( Config::get_make_credit_invoice() ? 'checked' : '' ); ?>
							/>
							<label for="make_credit_invoice_field">
								<?php
								esc_html_e(
									'Create a credit invoice when an order is labelled as refunded',
									'1984-dk-woo'
								);
								?>
							</label>
							<p class="description">
								<?php
								esc_html_e(
									'If enabled, a credit invoice is automatically created when an order is labelled as refunded in WooCommerce.',
									'1984-dk-woo'
								);
								?>
							</p>
						</td>
					</tr>
				</tbody>
			</table>
			<h3><?php esc_html_e( 'Service SKUs', '1984-dk-woo' ); ?></h3>
			<p>
				<?php
				esc_html_e(
					'DK treats shipping and other costs as line items on invoices. In order for them to work, you need to assign a SKU to each of the following services.',
					'1984-dk-woo'
				);
				?>
			</p>
			<table id="dk-service-sku-table" class="form-table">
				<tbody>
					<tr>
						<th scope="row" class="column-title column-primary">
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
							<?php $info_for_shipping_sku = Admin::info_for_service_sku( Config::get_shipping_sku() ); ?>
							<p class="infotext <?php echo esc_attr( $info_for_shipping_sku->css_class ); ?>">
								<span class="dashicons <?php echo esc_attr( $info_for_shipping_sku->dashicon ); ?>"></span>
								<?php echo esc_html( $info_for_shipping_sku->text ); ?>
							</p>
						</td>
					</tr>
					<tr>
						<th scope="row" class="column-title column-primary">
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
							<?php $info_for_cost_sku = Admin::info_for_service_sku( Config::get_cost_sku() ); ?>
							<p class="infotext <?php echo esc_attr( $info_for_cost_sku->css_class ); ?>">
								<span class="dashicons <?php echo esc_attr( $info_for_cost_sku->dashicon ); ?>"></span>
								<?php echo esc_html( $info_for_cost_sku->text ); ?>
							</p>
						</td>
					</tr>
					<tr>
						<th scope="row" class="column-title column-primary">
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
							<?php $info_for_sales_person = Admin::info_for_sales_person( Config::get_default_sales_person_number() ); ?>
							<p class="infotext <?php echo esc_attr( $info_for_sales_person->css_class ); ?>">
								<span class="dashicons <?php echo esc_attr( $info_for_sales_person->dashicon ); ?>"></span>
								<?php echo esc_html( $info_for_sales_person->text ); ?>
							</p>
						</td>
					</tr>
				</tbody>
			</table>
		</section>

		<section class="section">
			<h2><?php esc_html_e( 'Customers', '1984-dk-woo' ); ?></h2>
			<table id="customers-table" class="form-table dk-ledger-codes-table">
				<tbody>
					<tr>
						<th scope="row" class="column-title column-primary">
							<label for="domestic_customer_ledger_code_field">
								<?php esc_html_e( 'Ledger Code for Domestic Customers', '1984-dk-woo' ); ?>
							</label>
						</th>
						<td>
							<input
								id="domestic_customer_ledger_code_field"
								name="domestic_customer_ledger_code"
								type="text"
								value="<?php echo esc_attr( Config::get_domestic_customer_ledger_code() ); ?>"
							/>
						</td>
					</tr>
					<tr>
						<th scope="row" class="column-title column-primary">
							<label for="international_customer_ledger_code_field">
								<?php esc_html_e( 'Ledger Code for International Customers', '1984-dk-woo' ); ?>
							</label>
						</th>
						<td>
							<input
								id="international_customer_ledger_code_field"
								name="international_customer_ledger_code"
								type="text"
								value="<?php echo esc_attr( Config::get_international_customer_ledger_code() ); ?>"
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
				<thead>
					<tr>
						<th scope="col"></th>
						<th scope="col">Method ID in DK</th>
						<th scope="col">Payment Mode in DK</th>
						<th scope="col">Payment Terms in DK</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ( ( new WC_Payment_Gateways() )->payment_gateways as $p ) : ?>
						<?php
						if ( 'no' === $p->enabled ) {
							continue;
						}
						$payment_map = Config::get_payment_mapping( $p->id );
						?>
						<tr data-gateway-id="<?php echo esc_attr( $p->id ); ?>">
							<th scope="row" class="column-title column-primary">
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
											<?php echo esc_attr( Config::payment_mapping_matches( $p->id, $dk_method->dk_id ) ? 'selected=true' : '' ); ?>
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
									<option></option>
									<?php foreach ( SalesPayments::get_payment_modes() as $payment_mode ) : ?>
										<option
											value="<?php echo esc_attr( $payment_mode ); ?>"
											<?php echo esc_attr( Config::payment_mode_matches( $p->id, $payment_mode ) ? 'selected=true' : '' ); ?>
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
							<td>
								<select
									id="payment_term_input"
									name="payment_term"
								>
									<option></option>
									<?php foreach ( SalesPayments::get_payment_terms() as $payment_term ) : ?>
										<option
											value="<?php echo esc_attr( $payment_term ); ?>"
											<?php echo esc_attr( Config::payment_term_matches( $p->id, $payment_term ) ? 'selected=true' : '' ); ?>
										>
											<?php echo esc_attr( SalesPayments::get_payment_term_name( $payment_term ) ); ?>
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
				'The 1984 Connector for DK and WooCommerce is developed, maintained and supported on goodwill basis by 1984 Hosting as free software without any guarantees or obligations and is not affiliated with or supported by DK hugbúnaður ehf.',
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
