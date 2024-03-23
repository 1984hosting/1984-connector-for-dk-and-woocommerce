<?php

declare(strict_types = 1);

namespace NineteenEightyFour\NineteenEightyWoo\Tests;

use NineteenEightyFour\NineteenEightyWoo\Service\DKApiRequest;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\TestDox;

use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertInstanceOf;
use function PHPUnit\Framework\assertIsInt;

#[TestDox( 'DK API Service' )]
#[Group( 'external-api' )]
final class DKApiRequestTest extends TestCase {
	#[TestDox( 'connects to the DK API using a WP HTTP GET request' )]
	public function testApiGetResult(): void {
		$service  = new DKApiRequest();
		$response = $service->get_result( '/Product/1' );

		assertInstanceOf( 'stdClass', $response->data );
		assertIsInt( $response->response_code );
		assertEquals( 200, $response->response_code );
	}

	#[TestDox( 'connects to the DK API using a WP HTTP POST request' )]
	public function testApiRequestResult(): void {
		$service = new DKApiRequest();
		$sku     = dechex( random_int( 16384, 32768 ) );
		$body    = wp_json_encode(
			array(
				'ItemCode'      => $sku,
				'Description'   => 'Lorem ipsum dolor',
				'Inactive'      => true,
				'PurchasePrice' => 1000,
			)
		);

		$response = $service->request_result( '/Product', $body );

		assertInstanceOf( 'stdClass', $response->data );
		assertIsInt( $response->response_code );
		assertEquals( 200, $response->response_code );
	}
}
