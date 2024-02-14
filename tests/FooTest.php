<?php

declare(strict_types = 1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\TestDox;

#[TestDox( 'The Foo class' )]
final class FooTest extends TestCase {
	#[TestDox( 'echoes out a hello message' )]
	public function testTrue(): void {
		NineteenEightyFour\NinteenEightyWoo\Foo::say_hello();
		$this->expectOutputString( 'Hello!' );
	}
}
