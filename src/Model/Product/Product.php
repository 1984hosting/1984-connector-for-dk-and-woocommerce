<?php

declare(strict_types = 1);

namespace NineteenEightyFour\NineteenEightyWoo\Model\Product;

use JsonSerializable;
use stdClass;

/**
 * The Product DTO class for DK
 */
class Product implements JsonSerializable {
	protected string $ItemCode;

	protected string|null $Group;

	protected string $Description;

	protected string|null $Description2;

	protected float $UnitPrice1WithTax;

	protected bool $Inactive;

	protected string $ItemClass;

	protected string $UnitCode;

	protected int $UnitQuantity;

	protected float $NetWeight;

	protected int $UnitVolume;

	protected float $TotalQuantityInWarehouse;

	protected float $PurchasePrice;

	protected string $CurrencyCode;

	protected float $Exchange;

	protected float $UnitPrice1;

	protected float $PurchaseFactor;

	protected float $CostPrice;

	protected float $ProfitRatio1;

	protected float $UnitPrice2;

	protected float $UnitPrice3WithTax;

	protected bool $ShowItemInWebShop;

	protected bool $AllowDiscount;

	protected float $Discount;

	protected float $UnitPrice2WithTax;

	protected float $UnitPrice3;

	protected float $PropositionPrice;

	protected string|null $ExtraDesc1;

	protected string|null $ExtraDesc2;

	protected bool $IsVariation;

	protected string $OriginCountry;

	protected float $TaxPercent;

	protected string $SalesTaxCode;

	protected string $SalesLedgerCode;

	protected string $PurchaseTaxCode;

	protected string $PurchaseLedgerCode;

	protected bool $AllowNegativeInventory;

	protected float $MinimumStock;

	protected float $MaximumStock;

	protected float $DefaultPurchaseQuantity;

	protected bool $SkipInPurchaseOrderSuggestions;

	protected int $DeliveryTime;

	protected float $DiscountQuantity;

	protected float $MaxDiscountAllowed;

	protected float $DefaultSaleQuantity;

	protected int $CostMethod;

	protected \stdClass|null $PosProperties;

	protected bool $HasAttachments;

	protected array $Attachments;

	protected bool $HasBarcodes;

	protected array $Barcodes;

	protected bool $HasCurrencyPrices;

	protected bool $HasUnits;

	protected bool $HasAlternative;

	protected array $Categories;

	protected array $Warehouses;

	protected array $CurrencyPrices;

	protected array $Units;

	protected array $Alternative;

	protected array $Changes;

	protected array $Memos;

	protected array $Vendors;

	public function __construct() {
	}

	public function createProductFromDkData( \stdClass $product ): void {
		$this->setItemCode( $product->ItemCode );
		$this->setDescription( $product->Description ?? '' );
		$this->setDescription2( $product->Description2 ?? null );
		$this->setInactive( $product->Inactive );
		$this->setItemClass( $product->ItemClass ?? 'StockItem' );
		$this->setUnitCode( $product->UnitCode ?? 'stk' );
		$this->setGroup( $product->Group ?? null );
		$this->setUnitQuantity( $product->UnitQuantity ?? 0 );
		$this->setNetWeight( $product->NetWeight ?? 0 );
		$this->setUnitVolume( $product->UnitVolume ?? 0 );
		$this->setTotalQuantityInWarehouse( $product->TotalQuantityInWarehouse ?? 0 );
		$this->setPurchasePrice( $product->PurchasePrice ?? 0 );
		$this->setCurrencyCode( $product->CurrencyCode ?? 'ISK' );
		$this->setExchange( $product->Exchange ?? 0 );
		$this->setUnitPrice1( $product->UnitPrice1 ?? 0 );
		$this->setPurchaseFactor( $product->Purchasefactor ?? 0 );
		$this->setCostPrice( $product->CostPrice ?? 0 );
		$this->setProfitRatio1( $product->ProfitRatio1 ?? 0 );
		$this->setUnitPrice1WithTax( $product->UnitPrice1WithTax ?? 0 );
		$this->setUnitPrice2( $product->UnitPrice2 ?? 0 );
		$this->setUnitPrice2WithTax( $product->UnitPrice2WithTax ?? 0 );
		$this->setUnitPrice3( $product->UnitPrice3 ?? 0 );
		$this->setUnitPrice3WithTax( $product->UnitPrice3WithTax ?? 0 );
		$this->setShowItemInWebShop( $product->ShowItemInWebShop ?? false );
		$this->setAllowDiscount( $product->AllowDiscount ?? false );
		$this->setDiscount( $product->Discount ?? 0 );
		$this->setPropositionPrice( $product->PropositionPrice ?? 0 );
		$this->setIsVariation( $product->IsVariation ?? false );
		$this->setTaxPercent( $product->TaxPercent ?? 24 );
		$this->setSalesTaxCode( $product->SalesTaxCode ?? 'u1' );
		$this->setSalesLedgerCode( $product->SalesLegerCode ?? 's123' );
		$this->setPurchaseTaxCode( $product->PurchaseTaxCode ?? 'i3' );
		$this->setPurchaseLedgerCode( $product->PurchaseLedgerCode ?? 'i001' );
		$this->setAllowNegativeInventory( $product->AllowNegativeInventiry ?? true );
		$this->setMinimumStock( $product->MinimumStock ?? 0 );
		$this->setMaximumStock( $product->MaximumStock ?? 0 );
		$this->setDefaultPurchaseQuantity( $product->DefaultPurchaseQuantity ?? 0 );
		$this->setSkipInPurchaseOrderSuggestions( $product->SkipInPurchaseOrderSuggestions ?? false );
		$this->setDeliveryTime( $product->DeliveryTime ?? 0 );
		$this->setDiscountQuantity( $product->DiscountQuantity ?? 0 );
		$this->setMaxDiscountAllowed( $product->MaxDiscountAllowed ?? 0 );
		$this->setDefaultSaleQuantity( $product->DefaultSaleQuantity ?? 1 );
		$this->setCostMethod( $product->CostMethod ?? 2 );
		$this->setPosProperties( $product->PosProperties ?? null );
		$this->setHasAttachments( $product->HasAttachments ?? false );
		$this->setHasBarcodes( $product->HasBarcodes ?? false );
		$this->setHasCurrencyPrices( $product->HasCurrencyPrices ?? false );
		$this->setHasUnits( $product->HasUnits ?? false );
		$this->setHasAlternative( $product->HasAlternative ?? false );
		$this->setBarcodes( $product->Barcodes ?? [] );
		$this->setAttachments( $product->Attachments ?? [] );
		$this->setCategories( $product->Categories ?? [] );
		$this->setWarehouses( $product->Warehouses ?? [] );
		$this->setCurrencyPrices( $product->CurrencyPrices ?? [] );
		$this->setUnits( $product->Units ?? [] );
		$this->setAlternative( $product->Alternative ?? [] );
		$this->setChanges( $product->Changes ?? [] );
		$this->setMemos( $product->Memos ?? [] );
		$this->setVendors( $product->Vendors = [] );
	}

	public function createProductFromWooCommerceData( \stdClass $product ): void {
		// @TODO: This has to be re-written to represent WooCommerce data!

		$this->setItemCode( $product->item_code );
		$this->setGroup( $product->group_id );
		$this->setDescription( $product->description );
		$this->setDescription2( $product->description_two ?? null );
		$this->setInactive( $product->inactive ?? true );
		$this->setUnitPrice1( $product->unit_price_one ?? 0 );
		$this->setUnitPrice1WithTax( $product->unit_price_one_with_tax ?? 0 );
		$this->setUnitPrice2( $product->unit_price_two ?? 0 );
		$this->setUnitPrice2WithTax( $product->unit_price_two_with_tax ?? 0 );
		$this->setUnitPrice3( $product->unit_price_three ?? 0 );
		$this->setUnitPrice3WithTax( $product->unit_price_three_with_tax ?? 0 );
		$this->setItemClass( $product->item_class ?? 'StockItem' );
		$this->setUnitCode( $product->unit_code ?? 'stk' );
		$this->setUnitQuantity( $product->unit_quantity ?? 0 );
		$this->setNetWeight( $product->net_weight ?? 0 );
		$this->setUnitVolume( $product->unit_volume ?? 0 );
		$this->setTotalQuantityInWarehouse( $product->total_quantity_in_warehouse ?? 0 );
		$this->setPurchasePrice( $product->purchase_price ?? 0 );
		$this->setCurrencyCode( $product->currency_code ?? 'EUR' );
		$this->setExchange( $product->exchange ?? 0 );
		$this->setPurchaseFactor( $product->purchase_factor ?? 0 );
		$this->setCostPrice( $product->cost_price ?? 0 );
		$this->setProfitRatio1( $product->profit_ratio_one ?? 0 );
		$this->setShowItemInWebShop( $product->show_in_web_shop ?? true );
		$this->setAllowDiscount( $product->allow_discount ?? true );
		$this->setDiscount( $product->discount ?? 0 );
		$this->setPropositionPrice( $product->proposition_price ?? 0 );
		$this->setExtraDesc1( $product->extra_desc_one ?? null );
		$this->setExtraDesc2( $product->extra_desc_two ?? null );
		$this->setIsVariation( $product->is_variation ?? false );
		$this->setOriginCountry( $product->origin_country ?? 'Ísland' );
		$this->setTaxPercent( $product->tax_percent ?? 24 );
		$this->setSalesTaxCode( $product->sales_tax_code ?? 'u1' );
		$this->setSalesLedgerCode( $product->sales_ledger_code ?? 's123' );
		$this->setPurchaseTaxCode( $product->purchase_tax_code ?? 'i3' );
		$this->setPurchaseLedgerCode( $product->purcase_ledger_code ?? 'i001' );
		$this->setAllowNegativeInventory( $product->allow_negative_inventory ?? true );
		$this->setMinimumStock( $product->minimum_stock ?? 0 );
		$this->setMaximumStock( $product->maximum_stock ?? 0 );
		$this->setDefaultPurchaseQuantity( $product->default_purchase_quantity ?? 0 );
		$this->setSkipInPurchaseOrderSuggestions( $product->skip_in_purchase_order_suggestions ?? false );
		$this->setDeliveryTime( $product->delivery_time ?? 0 );
		$this->setMaxDiscountAllowed( $product->max_discount_allowed ?? 0 );
		$this->setDefaultSaleQuantity( $product->default_sale_quantity ?? 1 );
		$this->setCostMethod( $product->cost_method ?? 2 );
		$this->setPosProperties( $product->pos_properties ?? null );
		$this->setHasAttachments( $product->has_attachments ?? false );
		$this->setHasBarcodes( $product->has_barcodes ?? false );
		$this->setHasCurrencyPrices( $product->has_currency_prices ?? false );
		$this->setHasUnits( $product->has_units ?? false );
		$this->setHasAlternative( $product->has_alternative ?? false );
	}

	/**
	 * @return void
	 */
	public function toWCProductSimple() {
		// @TODO Create WooCommerce Product Simple Object from this Object
		// @author aldavigdis
	}

	public function getItemCode(): string {
		return $this->ItemCode;
	}

	public function setItemCode( string $ItemCode ): Product {
		$this->ItemCode = $ItemCode;
		return $this;
	}

	public function getGroup(): string|null {
		return $this->Group;
	}

	public function setGroup( string|null $Group ): Product {
		$this->Group = $Group;
		return $this;
	}

	public function getDescription(): string {
		return $this->Description;
	}

	public function setDescription( string $Description ): Product {
		$this->Description = $Description;
		return $this;
	}

	public function getDescription2(): string {
		return $this->Description2;
	}

	public function setDescription2( string|null $Description2 ): Product {
		$this->Description2 = $Description2;
		return $this;
	}

	public function getUnitPrice1WithTax(): float {
		return $this->UnitPrice1WithTax;
	}

	public function setUnitPrice1WithTax( float $UnitPrice1WithTax ): Product {
		$this->UnitPrice1WithTax = $UnitPrice1WithTax;
		return $this;
	}

	public function isInactive(): bool {
		return $this->Inactive;
	}

	public function setInactive( bool $Inactive ): Product {
		$this->Inactive = $Inactive;
		return $this;
	}

	public function getItemClass(): string {
		return $this->ItemClass;
	}

	public function setItemClass( string $ItemClass ): Product {
		$this->ItemClass = $ItemClass;
		return $this;
	}

	public function getUnitCode(): string {
		return $this->UnitCode;
	}

	public function setUnitCode( string $UnitCode ): Product {
		$this->UnitCode = $UnitCode;
		return $this;
	}

	public function getUnitQuantity(): int {
		return $this->UnitQuantity;
	}

	public function setUnitQuantity( int $UnitQuantity ): Product {
		$this->UnitQuantity = $UnitQuantity;
		return $this;
	}

	public function getNetWeight(): float {
		return $this->NetWeight;
	}

	public function setNetWeight( float $NetWeight ): Product {
		$this->NetWeight = $NetWeight;
		return $this;
	}

	public function getUnitVolume(): int {
		return $this->UnitVolume;
	}

	public function setUnitVolume( int $UnitVolume ): Product {
		$this->UnitVolume = $UnitVolume;
		return $this;
	}

	public function getTotalQuantityInWarehouse(): float {
		return $this->TotalQuantityInWarehouse;
	}

	public function setTotalQuantityInWarehouse( float $TotalQuantityInWarehouse ): Product {
		$this->TotalQuantityInWarehouse = $TotalQuantityInWarehouse;
		return $this;
	}

	public function getPurchasePrice(): float {
		return $this->PurchasePrice;
	}

	public function setPurchasePrice( float $PurchasePrice ): Product {
		$this->PurchasePrice = $PurchasePrice;
		return $this;
	}

	public function getCurrencyCode(): string {
		return $this->CurrencyCode;
	}

	public function setCurrencyCode( string $CurrencyCode ): Product {
		$this->CurrencyCode = $CurrencyCode;
		return $this;
	}

	public function getExchange(): float {
		return $this->Exchange;
	}

	public function setExchange( float $Exchange ): Product {
		$this->Exchange = $Exchange;
		return $this;
	}

	public function getUnitPrice1(): float {
		return $this->UnitPrice1;
	}

	public function setUnitPrice1( float $UnitPrice1 ): Product {
		$this->UnitPrice1 = $UnitPrice1;
		return $this;
	}

	public function getPurchaseFactor(): float {
		return $this->PurchaseFactor;
	}

	public function setPurchaseFactor( float $PurchaseFactor ): Product {
		$this->PurchaseFactor = $PurchaseFactor;
		return $this;
	}

	public function getCostPrice(): float {
		return $this->CostPrice;
	}

	public function setCostPrice( float $CostPrice ): Product {
		$this->CostPrice = $CostPrice;
		return $this;
	}

	public function getProfitRatio1(): float {
		return $this->ProfitRatio1;
	}

	public function setProfitRatio1( float $ProfitRatio1 ): Product {
		$this->ProfitRatio1 = $ProfitRatio1;
		return $this;
	}

	public function getUnitPrice2(): float {
		return $this->UnitPrice2;
	}

	public function setUnitPrice2( float $UnitPrice2 ): Product {
		$this->UnitPrice2 = $UnitPrice2;
		return $this;
	}

	public function getUnitPrice3WithTax(): float {
		return $this->UnitPrice3WithTax;
	}

	public function setUnitPrice3WithTax( float $UnitPrice3WithTax ): Product {
		$this->UnitPrice3WithTax = $UnitPrice3WithTax;
		return $this;
	}

	public function isShowItemInWebShop(): bool {
		return $this->ShowItemInWebShop;
	}

	public function setShowItemInWebShop( bool $ShowItemInWebShop ): Product {
		$this->ShowItemInWebShop = $ShowItemInWebShop;
		return $this;
	}

	public function isAllowDiscount(): bool {
		return $this->AllowDiscount;
	}

	public function setAllowDiscount( bool $AllowDiscount ): Product {
		$this->AllowDiscount = $AllowDiscount;
		return $this;
	}

	public function getDiscount(): float {
		return $this->Discount;
	}

	public function setDiscount( float $Discount ): Product {
		$this->Discount = $Discount;
		return $this;
	}

	public function getUnitPrice2WithTax(): float {
		return $this->UnitPrice2WithTax;
	}

	public function setUnitPrice2WithTax( float $UnitPrice2WithTax ): Product {
		$this->UnitPrice2WithTax = $UnitPrice2WithTax;
		return $this;
	}

	public function getUnitPrice3(): float {
		return $this->UnitPrice3;
	}

	public function setUnitPrice3( float $UnitPrice3 ): Product {
		$this->UnitPrice3 = $UnitPrice3;
		return $this;
	}

	public function getPropositionPrice(): float {
		return $this->PropositionPrice;
	}

	public function setPropositionPrice( float $PropositionPrice ): Product {
		$this->PropositionPrice = $PropositionPrice;
		return $this;
	}

	public function getExtraDesc1(): string|null {
		return $this->ExtraDesc1;
	}

	public function setExtraDesc1( string|null $ExtraDesc1 ): Product {
		$this->ExtraDesc1 = $ExtraDesc1;
		return $this;
	}

	public function getExtraDesc2(): string|null {
		return $this->ExtraDesc2;
	}

	public function setExtraDesc2( string|null $ExtraDesc2 ): Product {
		$this->ExtraDesc2 = $ExtraDesc2;
		return $this;
	}

	public function isIsVariation(): bool {
		return $this->IsVariation;
	}

	public function setIsVariation( bool $IsVariation ): Product {
		$this->IsVariation = $IsVariation;
		return $this;
	}

	public function getOriginCountry(): string {
		return $this->OriginCountry;
	}

	public function setOriginCountry( string $OriginCountry ): Product {
		$this->OriginCountry = $OriginCountry;
		return $this;
	}

	public function getTaxPercent(): float {
		return $this->TaxPercent;
	}

	public function setTaxPercent( float $TaxPercent ): Product {
		$this->TaxPercent = $TaxPercent;
		return $this;
	}

	public function getSalesTaxCode(): string {
		return $this->SalesTaxCode;
	}

	public function setSalesTaxCode( string $SalesTaxCode ): Product {
		$this->SalesTaxCode = $SalesTaxCode;
		return $this;
	}

	public function getSalesLedgerCode(): string {
		return $this->SalesLedgerCode;
	}

	public function setSalesLedgerCode( string $SalesLedgerCode ): Product {
		$this->SalesLedgerCode = $SalesLedgerCode;
		return $this;
	}

	public function getPurchaseTaxCode(): string {
		return $this->PurchaseTaxCode;
	}

	public function setPurchaseTaxCode( string $PurchaseTaxCode ): Product {
		$this->PurchaseTaxCode = $PurchaseTaxCode;
		return $this;
	}

	public function getPurchaseLedgerCode(): string {
		return $this->PurchaseLedgerCode;
	}

	public function setPurchaseLedgerCode( string $PurchaseLedgerCode ): Product {
		$this->PurchaseLedgerCode = $PurchaseLedgerCode;
		return $this;
	}

	public function isAllowNegativeInventory(): bool {
		return $this->AllowNegativeInventory;
	}

	public function setAllowNegativeInventory( bool $AllowNegativeInventory ): Product {
		$this->AllowNegativeInventory = $AllowNegativeInventory;
		return $this;
	}

	public function getMinimumStock(): float {
		return $this->MinimumStock;
	}

	public function setMinimumStock( float $MinimumStock ): Product {
		$this->MinimumStock = $MinimumStock;
		return $this;
	}

	public function getMaximumStock(): float {
		return $this->MaximumStock;
	}

	public function setMaximumStock( float $MaximumStock ): Product {
		$this->MaximumStock = $MaximumStock;
		return $this;
	}

	public function getDefaultPurchaseQuantity(): float {
		return $this->DefaultPurchaseQuantity;
	}

	public function setDefaultPurchaseQuantity( float $DefaultPurchaseQuantity ): Product {
		$this->DefaultPurchaseQuantity = $DefaultPurchaseQuantity;
		return $this;
	}

	public function isSkipInPurchaseOrderSuggestions(): bool {
		return $this->SkipInPurchaseOrderSuggestions;
	}

	public function setSkipInPurchaseOrderSuggestions( bool $SkipInPurchaseOrderSuggestions ): Product {
		$this->SkipInPurchaseOrderSuggestions = $SkipInPurchaseOrderSuggestions;
		return $this;
	}

	public function getDeliveryTime(): int {
		return $this->DeliveryTime;
	}

	public function setDeliveryTime( int $DeliveryTime ): Product {
		$this->DeliveryTime = $DeliveryTime;
		return $this;
	}

	public function getDiscountQuantity(): float {
		return $this->DiscountQuantity;
	}

	public function setDiscountQuantity( float $DiscountQuantity ): Product {
		$this->DiscountQuantity = $DiscountQuantity;
		return $this;
	}

	public function getMaxDiscountAllowed(): float {
		return $this->MaxDiscountAllowed;
	}

	public function setMaxDiscountAllowed( float $MaxDiscountAllowed ): Product {
		$this->MaxDiscountAllowed = $MaxDiscountAllowed;
		return $this;
	}

	public function getDefaultSaleQuantity(): float {
		return $this->DefaultSaleQuantity;
	}

	public function setDefaultSaleQuantity( float $DefaultSaleQuantity ): Product {
		$this->DefaultSaleQuantity = $DefaultSaleQuantity;
		return $this;
	}

	public function getCostMethod(): int {
		return $this->CostMethod;
	}

	public function setCostMethod( int $CostMethod ): Product {
		$this->CostMethod = $CostMethod;
		return $this;
	}

	/**
	 * @return stdClass|null
	 */
	public function getPosProperties(): \stdClass|null {
		return $this->PosProperties;
	}

	/**
	 * @param stdClass|null $PosProperties
	 */
	public function setPosProperties( \stdClass|null $PosProperties ): Product {
		if ( is_null( $PosProperties ) ) {
			$obj                   = new \stdClass();
			$obj->IsIncludedItem   = false;
			$obj->HasIncludedItems = false;
			$this->PosProperties   = $obj;
		} else {
			$this->PosProperties = $PosProperties;
		}

		return $this;
	}

	public function isHasAttachments(): bool {
		return $this->HasAttachments;
	}

	public function setHasAttachments( bool $HasAttachments ): Product {
		$this->HasAttachments = $HasAttachments;
		return $this;
	}

	public function getAttachments(): array {
		return $this->Attachments;
	}

	public function setAttachments( array $Attachments ): Product {
		$this->Attachments = $Attachments;
		return $this;
	}

	public function getBarcodes(): array {
		return $this->Barcodes;
	}

	public function setBarcodes( array $Barcodes ): Product {
		$this->Barcodes = $Barcodes;
		return $this;
	}

	public function isHasBarcodes(): bool {
		return $this->HasBarcodes;
	}

	public function setHasBarcodes( bool $HasBarcodes ): Product {
		$this->HasBarcodes = $HasBarcodes;
		return $this;
	}

	public function isHasCurrencyPrices(): bool {
		return $this->HasCurrencyPrices;
	}

	public function setHasCurrencyPrices( bool $HasCurrencyPrices ): Product {
		$this->HasCurrencyPrices = $HasCurrencyPrices;
		return $this;
	}

	public function isHasUnits(): bool {
		return $this->HasUnits;
	}

	public function setHasUnits( bool $HasUnits ): Product {
		$this->HasUnits = $HasUnits;
		return $this;
	}

	public function isHasAlternative(): bool {
		return $this->HasAlternative;
	}

	public function setHasAlternative( bool $HasAlternative ): Product {
		$this->HasAlternative = $HasAlternative;
		return $this;
	}

	public function getCategories(): array {
		return $this->Categories;
	}

	public function setCategories( array $Categories ): Product {
		$this->Categories = $Categories;
		return $this;
	}

	public function setCategory( \stdClass $category ): Product {
		$this->Categories[] = $category;
		return $this;
	}

	public function getWarehouses(): array {
		return $this->Warehouses;
	}

	public function setWarehouses( array $Warehouses ): Product {
		$this->Warehouses = $Warehouses;
		return $this;
	}

	public function setWarehouse( \stdClass $warehouse ): Product {
		$this->Warehouses[] = $warehouse;
		return $this;
	}

	public function getCurrencyPrices(): array {
		return $this->CurrencyPrices;
	}

	public function setCurrencyPrices( array $CurrencyPrices ): Product {
		$this->CurrencyPrices = $CurrencyPrices;
		return $this;
	}

	public function setCurrencyPrice( \stdClass $currency_price ): Product {
		$this->CurrencyPrices[] = $currency_price;
		return $this;
	}

	public function getUnits(): array {
		return $this->Units;
	}

	public function setUnits( array $Units ): Product {
		$this->Units = $Units;
		return $this;
	}

	public function setUnit( \stdClass $unit ): Product {
		$this->Units[] = $unit;
		return $this;
	}

	public function getAlternative(): array {
		return $this->Alternative;
	}

	public function setAlternative( array $Alternative ): Product {
		$this->Alternative = $Alternative;
		return $this;
	}

	public function setAlternativeItem( \stdClass $alternative ): Product {
		$this->Alternative[] = $alternative;
		return $this;
	}

	public function getChanges(): array {
		return $this->Changes;
	}

	public function setChanges( array $Changes ): Product {
		$this->Changes = $Changes;
		return $this;
	}

	public function setChange( \stdClass $change ): Product {
		$this->Changes[] = $change;
		return $this;
	}

	public function getMemos(): array {
		return $this->Memos;
	}

	public function setMemos( array $Memos ): Product {
		$this->Memos = $Memos;
		return $this;
	}

	public function setMemo( \stdClass $memo ): Product {
		$this->Memos[] = $memo;
		return $this;
	}

	public function getVendors(): array {
		return $this->Vendors;
	}

	public function setVendors( array $Vendors ): Product {
		$this->Vendors = $Vendors;
		return $this;
	}

	public function setVendor( \stdClass $vendor ): Product {
		$this->Vendors[] = $vendor;
		return $this;
	}

	public function jsonSerialize(): string {
		return json_encode( get_object_vars( $this ) );
	}
}
