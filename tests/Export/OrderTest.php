<?php

declare(strict_types = 1);

namespace NineteenEightyFour\NineteenEightyWoo\Tests\Export;

use NineteenEightyFour\NineteenEightyWoo\Export\Order;
use WC_Order;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\TestDox;
use function PHPUnit\Framework\assertTrue;

#[TestDox( 'The order exporter' )]
#[Group( 'depends-on-woo' )]
final class OrderTest extends TestCase {
	#[TestDox( 'Is able to use WooCommerce classes' )]
	public function testWooClasses(): void {
		assertTrue( class_exists('WC_Order') );
	}
}
