<?php

declare(strict_types = 1);

namespace NineteenEightyFour\NineteenEightyWoo\Hooks;

use NineteenEightyFour\NineteenEightyWoo\Config;
use NineteenEightyFour\NineteenEightyWoo\Export\Product;
use NineteenEightyFour\NineteenEightyWoo\Export\SalesPerson;
use NineteenEightyFour\NineteenEightyWoo\Export\Customer;
use NineteenEightyFour\NineteenEightyWoo\Helpers\Product as ProductHelper;
use stdClass;
use WC_Order;
use WP_Screen;

/**
 * The NineteenEightyWoo Admin class
 *
 * Handles the wp-admin related functionality for the plugin; loads views,
 * enqueues scripts and stylesheets etc.
 */
class Admin {
	const ASSET_VERSION = '0.4.0';

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

		if (
			(
				isset( $_GET['page'] )
			)
			&&
			(
				// Superlobal is not passed into anything.
				// phpcs:ignore WordPress.Security.NonceVerification
				$_GET['page'] === '1984-dk-woo' || $_GET['page'] === 'wc-orders'
			)
		) {
			add_action(
				'admin_init',
				array( __CLASS__, 'enqueue_styles_and_scripts' )
			);
		}

		add_action(
			'current_screen',
			array( __CLASS__, 'enqueue_products_styles_and_scripts' ),
			10
		);

		add_filter(
			'woocommerce_shop_order_list_table_columns',
			array( __CLASS__, 'add_dk_invoice_column' ),
			10
		);

		add_action(
			'woocommerce_shop_order_list_table_custom_column',
			array( __CLASS__, 'dk_invoice_column' ),
			10,
			2
		);

		if ( Config::get_product_convertion_to_variation_enabled() ) {
			add_filter(
				'bulk_actions-edit-product',
				array( __CLASS__, 'register_product_to_variant_bulk_action' ),
				10
			);

			add_filter(
				'handle_bulk_actions-edit-product',
				array( __CLASS__, 'handle_product_to_variant_bulk_action' ),
				10,
				3
			);
		}
	}

	public static function add_dk_invoice_column( array $columns ): array {
		$first = array_slice( $columns, 0, 2, true );
		$last  = array_slice( $columns, 2, null, true );
		return array_merge(
			$first,
			array( 'dk_invoice_id' => esc_html__( 'DK Invoice', '1984-dk-woo' ) ),
			$last
		);
	}

	public static function dk_invoice_column(
		string $column_name,
		WC_Order $wc_order
	) {
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
				echo 'Error';
				echo '</span>';
				return;
			}
		}
	}

	/**
	 * Enqueue the styles and scripts for the products screen
	 *
	 * @param WP_Screen $current_screen The current screen. In our case it
	 *                  should be the `edit-product` screen.
	 */
	public static function enqueue_products_styles_and_scripts(
		WP_Screen $current_screen
	): void {
		if (
			current_user_can( 'edit_others_posts' ) &&
			$current_screen->id === 'edit-product'
		) {

			wp_enqueue_script(
				'nineteen-eighty-woo',
				plugins_url( 'js/products.js', dirname( __DIR__ ) ),
				array( 'wp-api', 'wp-data' ),
				self::ASSET_VERSION,
				false,
			);
		}
	}

	/**
	 * Register the product-to-variant bulk action
	 *
	 * This enables a bulk action that changes products into variants of
	 * another. This is useful when fetching products from DK as there is no
	 * orhodox way for managing variant products in DK.
	 *
	 * @param array $bulk_actions The current bulk actions.
	 *
	 * @return array The modified array, with `convert_to_variant` added to it.
	 */
	public static function register_product_to_variant_bulk_action(
		array $bulk_actions
	): array {
		if ( current_user_can( 'edit_others_posts' ) ) {
			$bulk_actions['convert_to_variant'] = __(
				'Convert to Product Variant',
				'1984-dk-woo'
			);
		}

		return $bulk_actions;
	}

	/**
	 * The handler for the product-to-variant bulk action
	 *
	 * @param string $sendback The original redirect URL.
	 * @param string $doaction The name of the action; in our case `convert_to_variant`.
	 * @param array  $post_ids An array containing the IDs for the posts/products selected.
	 *
	 * @return string The URL to redirect to.
	 */
	public static function handle_product_to_variant_bulk_action(
		string $sendback,
		string $doaction,
		array $post_ids
	): string {
		if ( ! current_user_can( 'edit_others_posts' ) ) {
			return $sendback;
		}

		if ( $doaction !== 'convert_to_variant' ) {
			return $sendback;
		}

		// Nonce check is handled by the WP Core.
		// phpcs:ignore WordPress.Security.NonceVerification
		if ( ! isset( $_GET['action_post_id'] ) ) {
			return $sendback;
		}

		$parent_id = intval(
			sanitize_text_field(
				// Nonce check is handled by the WP Core.
				// phpcs:ignore WordPress.Security.NonceVerification
				wp_unslash( $_GET['action_post_id'] )
			)
		);

		if ( ! wc_get_product( $parent_id ) ) {
			return $sendback;
		}

		foreach ( $post_ids as $p ) {
			ProductHelper::convert_to_variant( $p, $parent_id );
		}

		return add_query_arg(
			'bulk_emailed_posts',
			count( $post_ids ),
			$sendback
		);
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
			handle: 'nineteen-eighty-woo-products',
			src: plugins_url( 'style/products.css', dirname( __DIR__ ) ),
			ver: self::ASSET_VERSION
		);

		wp_enqueue_script(
			'nineteen-eighty-woo',
			plugins_url( 'js/admin.js', dirname( __DIR__ ) ),
			array( 'wp-api', 'wp-data' ),
			self::ASSET_VERSION,
			false,
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
