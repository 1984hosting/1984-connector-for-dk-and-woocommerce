<?php

declare(strict_types = 1);

namespace NineteenEightyFour\NineteenEightyWoo\Hooks;

use NineteenEightyFour\NineteenEightyWoo\Config;
use NineteenEightyFour\NineteenEightyWoo\Export\Product;
use NineteenEightyFour\NineteenEightyWoo\Export\SalesPerson;
use NineteenEightyFour\NineteenEightyWoo\Export\Customer;
use stdClass;
use WC_Order;

/**
 * The NineteenEightyWoo Admin class
 *
 * Handles the wp-admin related functionality for the plugin; loads views,
 * enqueues scripts and stylesheets etc.
 */
class Admin {
	const ASSET_VERSION = '0.4.6';
	const PLUGIN_SLUG   = '1984-connector-for-dk-and-woocommerce';

	/**
	 * Constructor for the Admin interface class
	 *
	 * Nonce verification is disabled here as we are not processing the GET
	 * superglobals beyond checking if they are set to a certain value.
	 *
	 * Initiates any wp-admin related actions, .
	 */
	public function __construct() {
		add_action( 'init', array( __CLASS__, 'load_textdomain' ) );

		add_action( 'admin_menu', array( __CLASS__, 'add_menu_page' ) );

		add_action(
			'admin_init',
			array( __CLASS__, 'enqueue_styles_and_scripts' )
		);

		add_filter(
			'woocommerce_shop_order_list_table_columns',
			array( __CLASS__, 'add_dk_invoice_column' ),
			10
		);

		add_filter(
			'manage_edit-shop_order_columns',
			array( __CLASS__, 'add_dk_invoice_column' ),
			10
		);

		add_action(
			'woocommerce_shop_order_list_table_custom_column',
			array( __CLASS__, 'dk_invoice_column' ),
			10,
			2
		);

		add_action(
			'manage_shop_order_posts_custom_column',
			array( __CLASS__, 'dk_invoice_column' ),
			10,
			2
		);

		add_action(
			'add_meta_boxes',
			array( __CLASS__, 'add_dk_invoice_metabox' )
		);

		add_filter(
			'plugin_row_meta',
			array( __CLASS__, 'add_links_to_plugins_list' ),
			10,
			3
		);

		add_action(
			'after_plugin_row_meta',
			array( __CLASS__, 'add_notice_to_plugins_list' ),
			10,
			2
		);
	}

	/**
	 * Render our disclaimer int he wp-admin plugin list.
	 *
	 * Echoes out the plugin disclaimer. Used by the `after_plugin_row_meta` action.
	 *
	 * @param ?string $_unused Not used. This one is only defined for hook compatibility.
	 * @param array   $plugin_data The plugin data as passed by the after_plugin_row_meta hook.
	 */
	public static function add_notice_to_plugins_list(
		?string $_unused,
		array $plugin_data
	): void {
		if ( $plugin_data['slug'] === self::PLUGIN_SLUG ) {
			echo wp_kses( self::plugin_list_notice(), array( 'p' ) );
		}
	}

	/**
	 * The HTML formatted disclaimer to display in the wp-admin plugins list
	 */
	public static function plugin_list_notice(): string {
		$text = __(
			'Note: The 1984 Connector for DK and WooCommerce is developed, maintained and supported on goodwill basis by 1984 Hosting as free software without any guarantees or obligations and is not affiliated with or supported by DK hugbúnaður ehf.',
			'1984-dk-woo'
		);

		return "<p>$text</p>";
	}

	/**
	 * Add settings and community support links to the plugin overview in wp-admin
	 *
	 * @param array   $plugin_meta The plugin meta as provided by the plugin_row_meta filter.
	 * @param ?string $_unused Unused parameter.
	 * @param array   $plugin_data The plugin data as provided by the plugin_row_meta filter.
	 *
	 * @return array The updated $plugin meta.
	 */
	public static function add_links_to_plugins_list(
		array $plugin_meta,
		?string $_unused,
		array $plugin_data,
	): array {
		if ( $plugin_data['slug'] === self::PLUGIN_SLUG ) {
			$plugin_meta['Settings']          = self::settings_link();
			$plugin_meta['Community Support'] = self::community_link();
		}
		return $plugin_meta;
	}

	/**
	 * Get the URL for the plugin settings page
	 */
	private static function settings_url(): string {
		return get_admin_url( path: '?page=1984-dk-woo' );
	}

	/**
	 * Get the HTML hyperlink for the plugin settings page
	 *
	 * Used in the plugin overview page in wp-admin.
	 */
	private static function settings_link(): string {
		$url = self::settings_url();
		return "<a href=\"$url\">Settings</a>";
	}

	/**
	 * Get the URL for our community tab on WordPress.org
	 */
	private static function community_url(): string {
		$slug = self::PLUGIN_SLUG;
		return "https://wordpress.org/support/plugin/$slug/";
	}

	/**
	 * Format the HTML hyperlink to our community tab on WordPress.org
	 */
	private static function community_link(): string {
		$url  = self::community_url();
		$text = __( 'Community Support', '1984-dk-woo' );

		return "<a href=\"$url\" target=\"_blank\">$text</a>";
	}

	/**
	 * Add the invoice metabox to the order editor
	 */
	public static function add_dk_invoice_metabox(): void {
		add_meta_box(
			'nineteen-eighty-woo-dk-invoice-metabox',
			__( 'DK Invoice', '1984-dk-woo' ),
			array( __CLASS__, 'render_dk_invoice_metabox' ),
			'woocommerce_page_wc-orders',
			context: 'side',
			priority: 'high'
		);

		add_meta_box(
			'nineteen-eighty-woo-dk-invoice-metabox',
			__( 'DK Invoice', '1984-dk-woo' ),
			array( __CLASS__, 'render_dk_invoice_metabox' ),
			'shop_order',
			context: 'side',
			priority: 'high'
		);
	}

	/**
	 * Render the order invoice metabox
	 */
	public static function render_dk_invoice_metabox(): void {
		require dirname( __DIR__, 2 ) . '/views/dk_invoice_metabox.php';
	}

	/**
	 * Filter for adding the DK invoice column to the orders table
	 *
	 * @param array $columns The current set of columns.
	 *
	 * @return array The columns array with dk_invoice_id added.
	 */
	public static function add_dk_invoice_column( array $columns ): array {
		$first = array_slice( $columns, 0, 2, true );
		$last  = array_slice( $columns, 2, null, true );
		return array_merge(
			$first,
			array(
				'dk_invoice_id' => esc_html__( 'DK Invoice', '1984-dk-woo' ),
			),
			$last
		);
	}

	/**
	 * Action for the DK Invoice column in the orders table
	 *
	 * @param string       $column_name The column name (dk_invoice_id in our case).
	 * @param WC_Order|int $wc_order The WooCommerce order.
	 */
	public static function dk_invoice_column(
		string $column_name,
		WC_Order|int $wc_order
	): void {
		if ( is_int( $wc_order ) ) {
			$wc_order = wc_get_order( $wc_order );
		}
		if ( $column_name === 'dk_invoice_id' ) {
			$invoice_number = $wc_order->get_meta(
				'1984_woo_dk_invoice_number',
				true,
				'view'
			);

			$credit_invoice_number = $wc_order->get_meta(
				'1984_woo_dk_credit_invoice_number',
				true,
				'view'
			);

			$invoice_creation_error = $wc_order->get_meta(
				'1984_dk_woo_invoice_creation_error',
				true,
				'view'
			);

			if ( ! empty( $invoice_number ) ) {
				echo '<span class="dashicons dashicons-yes debit_invoice"></span> ';
				echo '<span class="debit_invoice">';
				echo esc_html( $invoice_number );
				echo '</span>';
				if ( ! empty( $credit_invoice_number ) ) {
					echo ' / ';
					echo '<span class="credit_invoice">';
					echo esc_html( $credit_invoice_number );
					echo '</span>';
				}
				return;
			}

			if ( ! empty( $invoice_creation_error ) ) {
				echo '<span class="dashicons dashicons-no invoice_error"></span> ';
				echo '<span class="invoice_error">';
				esc_html_e( 'Error', '1984-dk-woo' );
				echo '</span>';
				return;
			}
		}
	}

	/**
	 * Load the plugin text domain
	 */
	public static function load_textdomain(): void {
		$plugin_path = dirname( dirname( plugin_basename( __FILE__ ) ) );
		load_plugin_textdomain(
			domain: '1984-dk-woo',
			plugin_rel_path: $plugin_path . '/../languages'
		);
	}

	/**
	 * Add the admin page to the wp-admin sidebar
	 */
	public static function add_menu_page(): void {
		add_submenu_page(
			'woocommerce',
			__( 'Connector for DK', '1984-dk-woo' ),
			__( 'Connector for DK', '1984-dk-woo' ),
			'manage_options',
			'1984-dk-woo',
			array( __CLASS__, 'render_admin_page' )
		);
	}

	/**
	 * Render the admin page
	 *
	 * This includes our admin page
	 */
	public static function render_admin_page(): void {
		require dirname( __DIR__, 2 ) . '/views/admin.php';
	}

	/**
	 * Add the stylesheets and JS
	 */
	public static function enqueue_styles_and_scripts(): void {
		wp_enqueue_style(
			handle: 'nineteen-eighty-woo',
			src: plugins_url( 'style/admin.css', dirname( __DIR__ ) ),
			ver: self::ASSET_VERSION
		);

		wp_enqueue_style(
			handle: 'nineteen-eighty-woo-product',
			src: plugins_url( 'style/products.css', dirname( __DIR__ ) ),
			ver: self::ASSET_VERSION
		);

		wp_enqueue_script(
			'nineteen-eighty-woo-admin',
			plugins_url( 'js/admin.js', dirname( __DIR__ ) ),
			array( 'wp-api', 'wp-data', 'wp-i18n' ),
			self::ASSET_VERSION,
			false,
		);

		wp_enqueue_script(
			'nineteen-eighty-woo-products',
			plugins_url( 'js/products.js', dirname( __DIR__ ) ),
			array( 'wp-api', 'wp-i18n' ),
			self::ASSET_VERSION,
			false,
		);

		wp_enqueue_script(
			'nineteen-eighty-woo-order',
			plugins_url( 'js/order.js', dirname( __DIR__ ) ),
			array( 'wp-api', 'wp-data', 'wp-i18n' ),
			self::ASSET_VERSION,
			false,
		);

		wp_set_script_translations(
			'nineteen-eighty-woo-products',
			'1984-dk-woo',
			dirname( plugin_dir_path( __FILE__ ), 2 ) . '/languages'
		);
	}

	/**
	 * The url for the 1984 logo
	 *
	 * @param string $asset_version The asset version to use, to invalidate cache on update.
	 *
	 * @return string The full URL for the SVG version of the 1984 logo.
	 */
	public static function logo_url(
		string $asset_version = self::ASSET_VERSION
	): string {
		return plugins_url(
			'style/1984-logo-semitrans.svg?v=' . $asset_version,
			dirname( __DIR__ )
		);
	}

	/**
	 * Generate text and attributes for service SKU info text
	 *
	 * Checks if a SKU exists in DK and generates an object containing the
	 * attributes `text`, `class` and `dashicon` for displaying below the
	 * relevant text input in the admin form.
	 *
	 * @param string $sku The SKU to check for in DK.
	 *
	 * @return stdClass{
	 *     'text': string,
	 *     'class': string,
	 *     'dashicon': string
	 * }
	 */
	public static function info_for_service_sku( string $sku ): stdClass {
		if ( ! Config::get_dk_api_key() ) {
			$text = sprintf(
				// Translators: The %s stands for the relevant SKU.
				__(
					'Please make sure that a product with the Product Code ‘%s’ exsists in DK before saving.',
					'1984-dk-woo'
				),
				esc_html( $sku )
			);

			$class    = 'info';
			$dashicon = 'dashicons-info';
		} elseif ( Product::is_in_dk( $sku ) === true ) {
			$text = sprintf(
				// Translators: The %s stands for the relevant SKU.
				__(
					'The Item Code ‘%s’ was found in DK.',
					'1984-dk-woo'
				),
				esc_html( $sku )
			);

			$class    = 'ok';
			$dashicon = 'dashicons-yes';
		} else {
			$text = sprintf(
				// Translators: The %s stands for the relevant SKU.
				__(
					'The Item Code ‘%s’ was not found in DK.',
					'1984-dk-woo'
				),
				esc_html( $sku )
			);

			$class    = 'error';
			$dashicon = 'dashicons-no';
		}

		return (object) array(
			'css_class' => $class,
			'dashicon'  => $dashicon,
			'text'      => $text,
		);
	}

	/**
	 * Generate text and attributes for the default sales person info text
	 *
	 * Checks if a sales person exsist with a specific number and generates an
	 * object containing the information as properties for displaying below the
	 * relevant text input in the admin form.
	 *
	 * @param string $number The sales person number to check for in DK.
	 *
	 * @return stdClass{
	 *     'text': string,
	 *     'class': string,
	 *     'dashicon': string
	 * }
	 */
	public static function info_for_sales_person( string $number ): stdClass {
		if ( empty( Config::get_dk_api_key() ) ) {
			$text = sprintf(
				// Translators: The %s stands for the relevant sales person number.
				__(
					'Please make sure that a sales person with the number ‘%s’ exsists in DK before saving.',
					'1984-dk-woo'
				),
				esc_html( $number )
			);

			$class    = 'info';
			$dashicon = 'dashicons-info';
		} elseif ( SalesPerson::is_in_dk( $number ) === true ) {
			$text = sprintf(
				// Translators: The %s stands for the relevant sales person number.
				__(
					'A sales person with the number ‘%s’ was found in DK.',
					'1984-dk-woo'
				),
				esc_html( $number )
			);

			$class    = 'ok';
			$dashicon = 'dashicons-yes';
		} else {
			$text = sprintf(
				// Translators: The %s stands for the relevant sales person number.
				__(
					'A sales person with the number ‘%s’ was not found in DK.',
					'1984-dk-woo'
				),
				esc_html( $number )
			);

			$class    = 'error';
			$dashicon = 'dashicons-no';
		}

		return (object) array(
			'css_class' => $class,
			'dashicon'  => $dashicon,
			'text'      => $text,
		);
	}

	/**
	 * Generate text and attributes for the default kennitala infor text
	 *
	 * Checks if a customer record exsist using the default kennitala and
	 * generates an object containing the information as properties for
	 * displaying below the relevant text input in the admin form.
	 *
	 * @return stdClass{
	 *     'text': string,
	 *     'class': string,
	 *     'dashicon': string
	 * }
	 */
	public static function info_for_default_kennitala(): stdClass {
		if ( empty( Config::get_dk_api_key() ) ) {
			$text = sprintf(
				// Translators: The %s stands for the kennitala.
				__(
					'Please make sure that a customer record with the kennitala ‘%s’ exsists in DK before you continue.',
					'1984-dk-woo'
				),
				esc_html( Config::get_default_kennitala() )
			);

			$class    = 'info';
			$dashicon = 'dashicons-info';
		} elseif ( Customer::is_in_dk( Config::get_default_kennitala() ) === true ) {
			$text = sprintf(
				// Translators: The %s stands for the kennitala.
				__(
					'A customer record with the kennitala ‘%s’ was found in DK.',
					'1984-dk-woo'
				),
				esc_html( Config::get_default_kennitala() )
			);

			$class    = 'ok';
			$dashicon = 'dashicons-yes';
		} else {
			$text = sprintf(
				// Translators: The %s stands for the kennitala.
				__(
					'A custmer record with the kennitala ‘%s’ was not found in DK.',
					'1984-dk-woo'
				),
				esc_html( Config::get_default_kennitala() )
			);

			$class    = 'error';
			$dashicon = 'dashicons-no';
		}

		return (object) array(
			'css_class' => $class,
			'dashicon'  => $dashicon,
			'text'      => $text,
		);
	}
}
