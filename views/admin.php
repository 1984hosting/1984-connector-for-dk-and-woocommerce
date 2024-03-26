<?php

declare(strict_types = 1);

use NineteenEightyFour\NineteenEightyWoo\Config;

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
					<tr>
						<th span="row" class="column-title column-primary">
							<label for="invoice_number_prefix_field">
								<?php esc_html_e( 'Invoice Number Prefix', 'NineteenEightyWoo' ); ?>
							</label>
						</th>
						<td>
							<input
								id="invoice_number_prefix_field"
								name="invoice_number_prefix"
								type="text"
								value="<?php echo esc_attr( Config::get_invoice_number_prefix() ); ?>"
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
								<span class="payment-gateway-title"><?php echo esc_html( $p->title ); ?></span>
							</th>
							<td class="method-id">
								<label for="payment_id_input_<?php echo esc_attr( $p->id ); ?>">
									<?php esc_html_e( 'Method ID', 'NineteenEightyWoo' ); ?>
								</label>
								<input
									id="payment_id_input_<?php echo esc_attr( $p->id ); ?>"
									class="regular-text payment-id"
									name="payment_id"
									type="text"
									value="<?php echo esc_attr( $payment_map->dk_id ); ?>"
									inputmode="numeric"
									pattern="[0-9]+"
									required
								/>
								<p class="validity valid"><?php esc_html_e( 'Valid', 'NineteenEightyWoo' ); ?><span class="dashicons dashicons-yes"></span></p>
								<p class="validity invalid"><?php esc_html_e( 'Needs to be numeric', 'NineteenEightyWoo' ); ?></p>
							</td>
							<td>
								<label for="payment_name_input_<?php echo esc_attr( $p->id ); ?>">
									<?php esc_html_e( 'DK Payment Method Name', 'NineteenEightyWoo' ); ?>
								</label>
								<input
									id="payment_name_input_<?php echo esc_attr( $p->id ); ?>"
									class="regular-text payment-name"
									name="payment_name"
									value="<?php echo esc_attr( $payment_map->dk_name ); ?>"
									type="text"
									required
								/>
								<p class="validity valid"><?php esc_html_e( 'Valid', 'NineteenEightyWoo' ); ?><span class="dashicons dashicons-yes"></span></p>
								<p class="validity invalid"><?php esc_html_e( 'This is a required field', 'NineteenEightyWoo' ); ?></p>
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
			<h2><?php esc_html_e( 'SKU for Shipping', 'NineteenEightyWoo' ); ?></h2>
			<?php if ( true === Config::get_shipping_sku_is_in_dk() ) : ?>
			<p>
				<?php
				echo sprintf(
					// Translators: %1$s stands for a opening and %2$s for a closing <abbr> tag. %3$s and %4$s stand for the opening and closing <strong> tags.
					esc_html( __( 'The %1$sSKU%2$s used for shipping has been set to %3$sSHIPPING%4$s. This is a permanent setting.', 'NineteenEightyWoo' ) ),
					'<abbr title="' . esc_attr( __( 'stock keeping unit', 'NineteenEightyWoo' ) ) . '">',
					'</abbr>',
					'<strong>',
					'</strong>'
				);
				?>
			</p>
			<p>
				<?php
				echo sprintf(
					esc_html(
						// Translators: %1$s stands for a opening and %2$s for the closing <strong> tag.
						__(
							'This should correspond to a Product record in your DK setup with its %1$sItem Code%2$s set to the same value. The 1984 DK Connection plugin creates this automatically when this form is submitted for the first time.',
							'NineteenEightyWoo'
						)
					),
					'<strong>',
					'</strong>'
				);
				?>
			</p>
			<?php else : ?>
			<p><?php esc_html_e( 'In order for shipping to work, a SKU needs to be assigned for shipping costs.', 'NineteenEightyWoo' ); ?></p>
			<p><?php esc_html_e( 'If no product or service under this SKU has been created in DK, a new service item representing shipping will be created with that SKU.', 'NineteenEightyWoo' ); ?></p>
			<table id="dk-record-prefixes-table" class="form-table">
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
								<?php echo esc_html( Config::get_shipping_sku_is_in_dk() ? 'disabled' : '' ); ?>
							/>
						</td>
					</tr>
				</tbody>
			</table>
			<?php endif ?>
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
