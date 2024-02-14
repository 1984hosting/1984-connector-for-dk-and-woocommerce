<?php

declare(strict_types = 1);

namespace NineteenEightyFour\NinteenEightyWoo;

/**
 * The Foo class
 *
 * This is for testing the PSR-4 autoloading mechanism.
 **/
class Foo {
	/**
	 * Say hello
	 */
	public static function say_hello(): void {
		esc_html_e( 'Hello!', 'NinteenEightyWoo' );
	}
}
