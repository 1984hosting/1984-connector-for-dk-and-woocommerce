<?php

declare(strict_types = 1);

namespace NineteenEightyFour\NineteenEightyWoo\Tests\Import;

use NineteenEightyFour\NineteenEightyWoo\Import\Products as ImportProducts;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\TestDox;
use WC_Product;

use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertFalse;
use function PHPUnit\Framework\assertNotEquals;

#[TestDox( 'The products importer' )]
final class ProductsTest extends TestCase {
	const EXAMPLE_INACTIVE_DK_PRODUCT_JSON = <<<'JSON'
	{
		"RecordID": 999,
		"ItemCode": "inactive",
		"Description": "Inactive Product",
		"Inactive": true,
		"RecordCreated": "2024-04-02T01:17:08",
		"RecordModified": "2024-04-22T13:14:22",
		"ObjectDate": "2024-04-23T06:21:50.1950222+00:00",
		"ItemClass": 0,
		"UnitQuantity": 1.0,
		"NetWeight": 10.0,
		"UnitVolume": 0.0,
		"TotalQuantityInWarehouse": 585.0,
		"PurchasePrice": 0.0,
		"CurrencyCode": "ISK",
		"Exchange": 1.0,
		"UnitPrice1": 161.290322580645,
		"Purchasefactor": 0.0,
		"CostPrice": 0.0,
		"ProfitRatio1": 0.0,
		"UnitPrice1WithTax": 200.0,
		"ShowItemInWebShop": true,
		"AllowDiscount": true,
		"Discount": 0.0,
		"PropositionPrice": 0.0,
		"PropositionDateFrom": "2024-03-01T00:00:00+00:00",
		"PropositionDateTo": "2024-04-10T00:00:00Z",
		"ExtraDesc1": "This is an inactive product.",
		"ExtraDesc2": "",
		"IsVariation": false,
		"TaxPercent": 24.0,
		"SalesTaxCode": "u1",
		"SalesLedgerCode": "s002",
		"PurchaseTaxCode": "i3",
		"PurchaseLedgerCode": "i001",
		"AllowNegativeInventiry": false,
		"MinimumStock": 0.0,
		"MaximumStock": 0.0,
		"DefaultPurchaseQuantity": 0.0,
		"DeliveryTime": 0,
		"DiscountQuantity": 0.0,
		"MaxDiscountAllowed": 0.0,
		"DefaultSaleQuantity": 0.0,
		"CostMethod": 2
	}
	JSON;

	const EXAMPLE_UPDATED_DK_PRODUCT_JSON = <<<'JSON'
	{
		"RecordID": 111,
		"ItemCode": "inactive",
		"Description": "Product to update",
		"Inactive": true,
		"RecordCreated": "2024-04-02T01:17:08",
		"RecordModified": "2024-04-22T13:14:22",
		"ObjectDate": "2024-04-23T06:21:50.1950222+00:00",
		"ItemClass": 0,
		"UnitQuantity": 1.0,
		"NetWeight": 10.0,
		"UnitVolume": 0.0,
		"TotalQuantityInWarehouse": 585.0,
		"PurchasePrice": 0.0,
		"CurrencyCode": "ISK",
		"Exchange": 1.0,
		"UnitPrice1": 161.290322580645,
		"Purchasefactor": 0.0,
		"CostPrice": 0.0,
		"ProfitRatio1": 0.0,
		"UnitPrice1WithTax": 200.0,
		"ShowItemInWebShop": true,
		"AllowDiscount": true,
		"Discount": 0.0,
		"PropositionPrice": 0.0,
		"PropositionDateFrom": "2024-03-01T00:00:00+00:00",
		"PropositionDateTo": "2024-04-10T00:00:00Z",
		"ExtraDesc1": "This is an inactive product.",
		"ExtraDesc2": "",
		"IsVariation": false,
		"TaxPercent": 24.0,
		"SalesTaxCode": "u1",
		"SalesLedgerCode": "s002",
		"PurchaseTaxCode": "i3",
		"PurchaseLedgerCode": "i001",
		"AllowNegativeInventiry": false,
		"MinimumStock": 0.0,
		"MaximumStock": 0.0,
		"DefaultPurchaseQuantity": 0.0,
		"DeliveryTime": 0,
		"DiscountQuantity": 0.0,
		"MaxDiscountAllowed": 0.0,
		"DefaultSaleQuantity": 0.0,
		"CostMethod": 2
	}
	JSON;

	const EXAMPLE_DELETED_DK_PRODUCT_JSON = <<<'JSON'
	{
		"RecordID": 666,
		"ItemCode": "deleted",
		"Description": "Product to Delete",
		"Inactive": false,
		"Deleted": true,
		"RecordCreated": "2024-04-02T01:17:08",
		"RecordModified": "2024-04-22T13:14:22",
		"ObjectDate": "2024-04-23T06:21:50.1950222+00:00",
		"ItemClass": 0,
		"UnitQuantity": 1.0,
		"NetWeight": 10.0,
		"UnitVolume": 0.0,
		"TotalQuantityInWarehouse": 585.0,
		"PurchasePrice": 0.0,
		"CurrencyCode": "ISK",
		"Exchange": 1.0,
		"UnitPrice1": 161.290322580645,
		"Purchasefactor": 0.0,
		"CostPrice": 0.0,
		"ProfitRatio1": 0.0,
		"UnitPrice1WithTax": 200.0,
		"ShowItemInWebShop": true,
		"AllowDiscount": true,
		"Discount": 0.0,
		"PropositionPrice": 0.0,
		"PropositionDateFrom": "2024-03-01T00:00:00+00:00",
		"PropositionDateTo": "2024-04-10T00:00:00Z",
		"ExtraDesc1": "This is a deleted product.",
		"ExtraDesc2": "",
		"IsVariation": false,
		"TaxPercent": 24.0,
		"SalesTaxCode": "u1",
		"SalesLedgerCode": "s002",
		"PurchaseTaxCode": "i3",
		"PurchaseLedgerCode": "i001",
		"AllowNegativeInventiry": false,
		"MinimumStock": 0.0,
		"MaximumStock": 0.0,
		"DefaultPurchaseQuantity": 0.0,
		"DeliveryTime": 0,
		"DiscountQuantity": 0.0,
		"MaxDiscountAllowed": 0.0,
		"DefaultSaleQuantity": 0.0,
		"CostMethod": 2
	}
	JSON;

	#[TestDox( 'Trashes products that have been deleted on the DK side' )]
	public function testImportDeletedProductAsTrashed(): void {
		$wc_product = new WC_Product();
		$wc_product->set_sku( 'deleted' );
		$wc_product->save();

		$product_json_object = json_decode(
			self::EXAMPLE_DELETED_DK_PRODUCT_JSON
		);

		$product_id = ImportProducts::save_from_dk(
			$product_json_object->ItemCode,
			$product_json_object
		);

		assertFalse( $product_id );
	}
}
