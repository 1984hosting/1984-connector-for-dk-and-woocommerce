<?php

declare(strict_types = 1);

namespace NineteenEightyFour\NineteenEightyWoo\Tests;

use NineteenEightyFour\NineteenEightyWoo\Config;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\TestDox;

use function PHPUnit\Framework\assertEquals;

#[TestDox( 'The Config class' )]
final class ConfigTest extends TestCase {
	#[TestDox( 'gets the DK API key configuration value from ENV variables' )]
	public function testGetDkApiKeyFromENV(): void {
		assertEquals( 36, strlen( getenv( 'DK_API_KEY' ) ) );
		assertEquals( getenv( 'DK_API_KEY' ), Config::get_dk_api_key() );
	}

	#[TestDox( 'gets the DK API key configuration value from the wp_options table if not set in an environment variable' )]
	public function testGetDkApiKeyFromWpOptions(): void {
		$original_key = getenv( 'DK_API_KEY' );
		$new_key      = '0c72b041-f8fc-473b-b745-00fed510ea0f';

		assertEquals( $original_key, Config::get_dk_api_key() );

		putenv( 'DK_API_KEY' );
		update_option( '1984_woo_dk_api_key', $new_key );

		assertEquals( $new_key, Config::get_dk_api_key() );
	}
}
