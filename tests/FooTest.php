<?php

declare(strict_types = 1);

namespace NineteenEightyFour\NinteenEightyWoo\Tests;

use NineteenEightyFour\NinteenEightyWoo\Foo;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\TestDox;

#[TestDox( 'The Foo class' )]
final class FooTest extends TestCase {
	#[TestDox( 'echoes out a hello message' )]
	public function testTrue(): void {
		Foo::say_hello();
		$this->expectOutputString( 'Hello!' );
	}
}
