<?php

declare(strict_types = 1);

namespace NineteenEightyFour\NineteenEightyWoo\Tests\Export;

use NineteenEightyFour\NineteenEightyWoo\Export\Product as ExportProduct;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\TestDox;
use WC_Product;

use function PHPUnit\Framework\assertEquals;

#[TestDox( 'The product exporter' )]
final class ProductTest extends TestCase {
	#[TestDox( 'Translates product post status to the correct state in DK' )]
	public function testPostStatus(): void {
		$wc_product = new WC_Product();
		$wc_product->set_sku( 'test123' );
		$wc_product->set_status( 'draft' );
		$wc_product->set_name( 'Test Product' );
		$wc_product->set_price( 300 );
		$wc_product->set_sale_price( 200 );
		$wc_product->set_date_on_sale_from( '2024-04-01' );
		$wc_product->set_date_on_sale_to( '2024-04-20' );
		$wc_product->set_description(
			'This is the description for a test product.'
		);

		$wc_product->update_meta_data( '1984_woo_dk_price_sync', true );
		$wc_product->update_meta_data( '1984_woo_dk_stock_sync', true );

		$wc_product->save();

		$draft_object = ExportProduct::to_dk_product_body( $wc_product, true );

		assertEquals( true, $draft_object['Inactive'] );
		assertEquals( true, $draft_object['ShowItemInWebShop'] );

		$wc_product->set_status( 'publish' );
		$wc_product->save();

		$published_object = ExportProduct::to_dk_product_body( $wc_product, true );

		assertEquals( false, $published_object['Inactive'] );
		assertEquals( true, $published_object['ShowItemInWebShop'] );
	}
}
