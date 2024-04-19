<?php

declare(strict_types = 1);

namespace NineteenEightyFour\NineteenEightyWoo;

/**
 * The NineteenEightyWoo Admin class
 *
 * Handles the wp-admin related functionality for the plugin; loads views,
 * enqueues scripts and stylesheets etc.
 */
class Admin {
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
		load_plugin_textdomain(
			domain: '1984-dk-woo',
			plugin_rel_path: '1984-dk-woo/languages'
		);
	}

	/**
	 * Add the admin page to the wp-admin sidebar
	 */
	public static function add_menu_page(): void {
		add_submenu_page(
			'woocommerce',
			__( '1984 DK Connection', '1984-dk-woo' ),
			__( 'DK Connection', '1984-dk-woo' ),
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
		require __DIR__ . '/../views/admin.php';
	}

	/**
	 * Add the stylesheets and JS
	 */
	public static function enqueue_styles_and_scripts(): void {
		wp_enqueue_style(
			handle: 'nineteen-eighty-woo',
			src: plugins_url( 'style/admin.css', __DIR__ ),
			ver: '0.1.5'
		);

		wp_enqueue_script(
			'nineteen-eighty-woo',
			plugins_url( 'js/admin.js', __DIR__ ),
			array( 'wp-api', 'wp-data' ),
			'0.1.5',
			false,
		);
	}

	/**
	 * The url for the 1984 logo
	 *
	 * @return string The full URL for the SVG version of the 1984 logo.
	 */
	public static function logo_url( string $asset_version = '0.1' ): string {
		return plugins_url(
			'style/1984-logo-semitrans.svg?v=' . $asset_version,
			__DIR__
		);
	}
}
