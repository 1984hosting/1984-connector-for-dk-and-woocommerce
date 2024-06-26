<?php

declare(strict_types = 1);

namespace NineteenEightyFour\NineteenEightyWoo\Hooks;

use NineteenEightyFour\NineteenEightyWoo\Config;
use NineteenEightyFour\NineteenEightyWoo\Export\Product;
use NineteenEightyFour\NineteenEightyWoo\Export\SalesPerson;
use NineteenEightyFour\NineteenEightyWoo\Export\Customer;
use stdClass;

/**
 * The NineteenEightyWoo Admin class
 *
 * Handles the wp-admin related functionality for the plugin; loads views,
 * enqueues scripts and stylesheets etc.
 */
class Admin {
	const ASSET_VERSION = '0.3.1';

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

		// Superlobal is not passed into anything.
		// phpcs:ignore WordPress.Security.NonceVerification
		if ( ( isset( $_GET['page'] ) ) && ( '1984-dk-woo' === $_GET['page'] ) ) {
			add_action(
				'admin_init',
				array( __CLASS__, 'enqueue_styles_and_scripts' )
			);
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
		if ( false === Config::get_dk_api_key() ) {
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
		} elseif ( true === Product::is_in_dk( $sku ) ) {
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
		} elseif ( true === SalesPerson::is_in_dk( $number ) ) {
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
		} elseif ( true === Customer::is_in_dk( Config::get_default_kennitala() ) ) {
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
