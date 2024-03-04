<?php

declare(strict_types = 1);

namespace NineteenEightyFour\NinteenEightyWoo;

class Admin {
	/**
	 * Constructor for the Admin interface class
	 *
	 * Initiates any wp-admin related actions, .
	 */
	public function __construct() {
		add_action(
			'admin_menu',
			array(
				__CLASS__,
				'add_menu_page',
			)
		);

		add_action(
			'admin_init',
			array(
				__CLASS__,
				'enqueue_styles_and_scripts',
			)
		);
	}

	/**
	 * Add the admin page to the wp-admin sidebar
	 */
	public static function add_menu_page(): void {
		add_submenu_page(
			'woocommerce',
			__( '1984 dkPlus Connection', 'NinteenEightyWoo' ),
			__( 'dkPlus Connection', 'NinteenEightyWoo' ),
			'manage_options',
			'NinteenEightyWoo',
			array( get_called_class(), 'render_admin_page' )
		);
	}

	public static function render_admin_page() {
		require __DIR__ . '/../views/admin.php';
	}

	public static function enqueue_styles_and_scripts() {
		wp_enqueue_style(
			handle: 'ninteen-eighty-woo',
			src: plugins_url( 'style/ninteen-eighty-woo.css', __DIR__ ),
			ver: '0.1'
		);

		wp_enqueue_script(
			handle: 'ninteen-eighty-woo',
			src: plugins_url( 'js/ninteen-eighty-woo.js', __DIR__ ),
			deps: array(
				'wp-edit-post',
				'wp-element',
				'wp-components',
				'wp-plugins',
				'wp-data',
			),
			ver: '0.1'
		);
	}

	public static function logo_url(): string {
		return plugins_url(
			'style/1984-logo-semitrans.svg',
			__DIR__
		);
	}
}
