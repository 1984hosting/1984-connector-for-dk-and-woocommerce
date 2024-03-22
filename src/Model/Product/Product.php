<?php

declare(strict_types = 1);

namespace NineteenEightyFour\NineteenEightyWoo\Model\Product;

use JsonSerializable;
use stdClass;

/**
 * The Product DTO class for DK
 */
class Product implements JsonSerializable {
	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string
	 */
	protected string $ItemCode;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string|null
	 */
	protected string|null $Group;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string
	 */
	protected string $Description;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string|null
	 */
	protected string|null $Description2;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var float
	 */
	protected float $UnitPrice1WithTax;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var bool
	 */
	protected bool $Inactive;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string
	 */
	protected string $ItemClass;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string
	 */
	protected string $UnitCode;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var int
	 */
	protected int $UnitQuantity;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var float
	 */
	protected float $NetWeight;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var int
	 */
	protected int $UnitVolume;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var float
	 */
	protected float $TotalQuantityInWarehouse;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var float
	 */
	protected float $PurchasePrice;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string
	 */
	protected string $CurrencyCode;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var float
	 */
	protected float $Exchange;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var float
	 */
	protected float $UnitPrice1;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var float
	 */
	protected float $PurchaseFactor;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var float
	 */
	protected float $CostPrice;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var float
	 */
	protected float $ProfitRatio1;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var float
	 */
	protected float $UnitPrice2;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var float
	 */
	protected float $UnitPrice3WithTax;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var bool
	 */
	protected bool $ShowItemInWebShop;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var bool
	 */
	protected bool $AllowDiscount;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var float
	 */
	protected float $Discount;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var float
	 */
	protected float $UnitPrice2WithTax;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var float
	 */
	protected float $UnitPrice3;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var float
	 */
	protected float $PropositionPrice;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string|null
	 */
	protected string|null $ExtraDesc1;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string|null
	 */
	protected string|null $ExtraDesc2;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var bool
	 */
	protected bool $IsVariation;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string
	 */
	protected string $OriginCountry;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var float
	 */
	protected float $TaxPercent;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string
	 */
	protected string $SalesTaxCode;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string
	 */
	protected string $SalesLedgerCode;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string
	 */
	protected string $PurchaseTaxCode;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string
	 */
	protected string $PurchaseLedgerCode;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var bool
	 */
	protected bool $AllowNegativeInventory;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var float
	 */
	protected float $MinimumStock;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var float
	 */
	protected float $MaximumStock;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var float
	 */
	protected float $DefaultPurchaseQuantity;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var bool
	 */
	protected bool $SkipInPurchaseOrderSuggestions;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var int
	 */
	protected int $DeliveryTime;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var float
	 */
	protected float $DiscountQuantity;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var float
	 */
	protected float $MaxDiscountAllowed;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var float
	 */
	protected float $DefaultSaleQuantity;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var int
	 */
	protected int $CostMethod;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var stdClass|null
	 */
	protected \stdClass|null $PosProperties;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var bool
	 */
	protected bool $HasAttachments;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var array
	 */
	protected array $Attachments;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var bool
	 */
	protected bool $HasBarcodes;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var array
	 */
	protected array $Barcodes;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var bool
	 */
	protected bool $HasCurrencyPrices;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var bool
	 */
	protected bool $HasUnits;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var bool
	 */
	protected bool $HasAlternative;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var array
	 */
	protected array $Categories;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var array
	 */
	protected array $Warehouses;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var array
	 */
	protected array $CurrencyPrices;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var array
	 */
	protected array $Units;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var array
	 */
	protected array $Alternative;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var array
	 */
	protected array $Changes;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var array
	 */
	protected array $Memos;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var array
	 */
	protected array $Vendors;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function __construct() {
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
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

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
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
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return void
	 */
	public function toWCProductSimple() {
		// @TODO Create WooCommerce Product Simple Object from this Object
		// @author aldavigdis
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function getItemCode(): string {
		return $this->ItemCode;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function setItemCode( string $ItemCode ): Product {
		$this->ItemCode = $ItemCode;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function getGroup(): string|null {
		return $this->Group;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function setGroup( string|null $Group ): Product {
		$this->Group = $Group;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function getDescription(): string {
		return $this->Description;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function setDescription( string $Description ): Product {
		$this->Description = $Description;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function getDescription2(): string {
		return $this->Description2;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function setDescription2( string|null $Description2 ): Product {
		$this->Description2 = $Description2;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function getUnitPrice1WithTax(): float {
		return $this->UnitPrice1WithTax;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function setUnitPrice1WithTax( float $UnitPrice1WithTax ): Product {
		$this->UnitPrice1WithTax = $UnitPrice1WithTax;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function isInactive(): bool {
		return $this->Inactive;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function setInactive( bool $Inactive ): Product {
		$this->Inactive = $Inactive;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function getItemClass(): string {
		return $this->ItemClass;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function setItemClass( string $ItemClass ): Product {
		$this->ItemClass = $ItemClass;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function getUnitCode(): string {
		return $this->UnitCode;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function setUnitCode( string $UnitCode ): Product {
		$this->UnitCode = $UnitCode;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function getUnitQuantity(): int {
		return $this->UnitQuantity;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function setUnitQuantity( int $UnitQuantity ): Product {
		$this->UnitQuantity = $UnitQuantity;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function getNetWeight(): float {
		return $this->NetWeight;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function setNetWeight( float $NetWeight ): Product {
		$this->NetWeight = $NetWeight;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function getUnitVolume(): int {
		return $this->UnitVolume;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function setUnitVolume( int $UnitVolume ): Product {
		$this->UnitVolume = $UnitVolume;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function getTotalQuantityInWarehouse(): float {
		return $this->TotalQuantityInWarehouse;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function setTotalQuantityInWarehouse( float $TotalQuantityInWarehouse ): Product {
		$this->TotalQuantityInWarehouse = $TotalQuantityInWarehouse;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function getPurchasePrice(): float {
		return $this->PurchasePrice;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function setPurchasePrice( float $PurchasePrice ): Product {
		$this->PurchasePrice = $PurchasePrice;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function getCurrencyCode(): string {
		return $this->CurrencyCode;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function setCurrencyCode( string $CurrencyCode ): Product {
		$this->CurrencyCode = $CurrencyCode;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function getExchange(): float {
		return $this->Exchange;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function setExchange( float $Exchange ): Product {
		$this->Exchange = $Exchange;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function getUnitPrice1(): float {
		return $this->UnitPrice1;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function setUnitPrice1( float $UnitPrice1 ): Product {
		$this->UnitPrice1 = $UnitPrice1;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function getPurchaseFactor(): float {
		return $this->PurchaseFactor;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function setPurchaseFactor( float $PurchaseFactor ): Product {
		$this->PurchaseFactor = $PurchaseFactor;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function getCostPrice(): float {
		return $this->CostPrice;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function setCostPrice( float $CostPrice ): Product {
		$this->CostPrice = $CostPrice;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function getProfitRatio1(): float {
		return $this->ProfitRatio1;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function setProfitRatio1( float $ProfitRatio1 ): Product {
		$this->ProfitRatio1 = $ProfitRatio1;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function getUnitPrice2(): float {
		return $this->UnitPrice2;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function setUnitPrice2( float $UnitPrice2 ): Product {
		$this->UnitPrice2 = $UnitPrice2;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function getUnitPrice3WithTax(): float {
		return $this->UnitPrice3WithTax;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function setUnitPrice3WithTax( float $UnitPrice3WithTax ): Product {
		$this->UnitPrice3WithTax = $UnitPrice3WithTax;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function isShowItemInWebShop(): bool {
		return $this->ShowItemInWebShop;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function setShowItemInWebShop( bool $ShowItemInWebShop ): Product {
		$this->ShowItemInWebShop = $ShowItemInWebShop;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function isAllowDiscount(): bool {
		return $this->AllowDiscount;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function setAllowDiscount( bool $AllowDiscount ): Product {
		$this->AllowDiscount = $AllowDiscount;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function getDiscount(): float {
		return $this->Discount;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function setDiscount( float $Discount ): Product {
		$this->Discount = $Discount;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function getUnitPrice2WithTax(): float {
		return $this->UnitPrice2WithTax;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function setUnitPrice2WithTax( float $UnitPrice2WithTax ): Product {
		$this->UnitPrice2WithTax = $UnitPrice2WithTax;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function getUnitPrice3(): float {
		return $this->UnitPrice3;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function setUnitPrice3( float $UnitPrice3 ): Product {
		$this->UnitPrice3 = $UnitPrice3;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function getPropositionPrice(): float {
		return $this->PropositionPrice;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function setPropositionPrice( float $PropositionPrice ): Product {
		$this->PropositionPrice = $PropositionPrice;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function getExtraDesc1(): string|null {
		return $this->ExtraDesc1;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function setExtraDesc1( string|null $ExtraDesc1 ): Product {
		$this->ExtraDesc1 = $ExtraDesc1;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function getExtraDesc2(): string|null {
		return $this->ExtraDesc2;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function setExtraDesc2( string|null $ExtraDesc2 ): Product {
		$this->ExtraDesc2 = $ExtraDesc2;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function isIsVariation(): bool {
		return $this->IsVariation;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function setIsVariation( bool $IsVariation ): Product {
		$this->IsVariation = $IsVariation;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function getOriginCountry(): string {
		return $this->OriginCountry;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function setOriginCountry( string $OriginCountry ): Product {
		$this->OriginCountry = $OriginCountry;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function getTaxPercent(): float {
		return $this->TaxPercent;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function setTaxPercent( float $TaxPercent ): Product {
		$this->TaxPercent = $TaxPercent;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function getSalesTaxCode(): string {
		return $this->SalesTaxCode;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function setSalesTaxCode( string $SalesTaxCode ): Product {
		$this->SalesTaxCode = $SalesTaxCode;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function getSalesLedgerCode(): string {
		return $this->SalesLedgerCode;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function setSalesLedgerCode( string $SalesLedgerCode ): Product {
		$this->SalesLedgerCode = $SalesLedgerCode;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function getPurchaseTaxCode(): string {
		return $this->PurchaseTaxCode;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function setPurchaseTaxCode( string $PurchaseTaxCode ): Product {
		$this->PurchaseTaxCode = $PurchaseTaxCode;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function getPurchaseLedgerCode(): string {
		return $this->PurchaseLedgerCode;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function setPurchaseLedgerCode( string $PurchaseLedgerCode ): Product {
		$this->PurchaseLedgerCode = $PurchaseLedgerCode;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function isAllowNegativeInventory(): bool {
		return $this->AllowNegativeInventory;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function setAllowNegativeInventory( bool $AllowNegativeInventory ): Product {
		$this->AllowNegativeInventory = $AllowNegativeInventory;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function getMinimumStock(): float {
		return $this->MinimumStock;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function setMinimumStock( float $MinimumStock ): Product {
		$this->MinimumStock = $MinimumStock;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function getMaximumStock(): float {
		return $this->MaximumStock;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function setMaximumStock( float $MaximumStock ): Product {
		$this->MaximumStock = $MaximumStock;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function getDefaultPurchaseQuantity(): float {
		return $this->DefaultPurchaseQuantity;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function setDefaultPurchaseQuantity( float $DefaultPurchaseQuantity ): Product {
		$this->DefaultPurchaseQuantity = $DefaultPurchaseQuantity;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function isSkipInPurchaseOrderSuggestions(): bool {
		return $this->SkipInPurchaseOrderSuggestions;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function setSkipInPurchaseOrderSuggestions( bool $SkipInPurchaseOrderSuggestions ): Product {
		$this->SkipInPurchaseOrderSuggestions = $SkipInPurchaseOrderSuggestions;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function getDeliveryTime(): int {
		return $this->DeliveryTime;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function setDeliveryTime( int $DeliveryTime ): Product {
		$this->DeliveryTime = $DeliveryTime;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function getDiscountQuantity(): float {
		return $this->DiscountQuantity;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function setDiscountQuantity( float $DiscountQuantity ): Product {
		$this->DiscountQuantity = $DiscountQuantity;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function getMaxDiscountAllowed(): float {
		return $this->MaxDiscountAllowed;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function setMaxDiscountAllowed( float $MaxDiscountAllowed ): Product {
		$this->MaxDiscountAllowed = $MaxDiscountAllowed;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function getDefaultSaleQuantity(): float {
		return $this->DefaultSaleQuantity;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function setDefaultSaleQuantity( float $DefaultSaleQuantity ): Product {
		$this->DefaultSaleQuantity = $DefaultSaleQuantity;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function getCostMethod(): int {
		return $this->CostMethod;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function setCostMethod( int $CostMethod ): Product {
		$this->CostMethod = $CostMethod;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return stdClass|null
	 */
	public function getPosProperties(): \stdClass|null {
		return $this->PosProperties;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
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

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function isHasAttachments(): bool {
		return $this->HasAttachments;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function setHasAttachments( bool $HasAttachments ): Product {
		$this->HasAttachments = $HasAttachments;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function getAttachments(): array {
		return $this->Attachments;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function setAttachments( array $Attachments ): Product {
		$this->Attachments = $Attachments;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function getBarcodes(): array {
		return $this->Barcodes;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function setBarcodes( array $Barcodes ): Product {
		$this->Barcodes = $Barcodes;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function isHasBarcodes(): bool {
		return $this->HasBarcodes;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function setHasBarcodes( bool $HasBarcodes ): Product {
		$this->HasBarcodes = $HasBarcodes;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function isHasCurrencyPrices(): bool {
		return $this->HasCurrencyPrices;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function setHasCurrencyPrices( bool $HasCurrencyPrices ): Product {
		$this->HasCurrencyPrices = $HasCurrencyPrices;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function isHasUnits(): bool {
		return $this->HasUnits;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function setHasUnits( bool $HasUnits ): Product {
		$this->HasUnits = $HasUnits;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function isHasAlternative(): bool {
		return $this->HasAlternative;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function setHasAlternative( bool $HasAlternative ): Product {
		$this->HasAlternative = $HasAlternative;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function getCategories(): array {
		return $this->Categories;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function setCategories( array $Categories ): Product {
		$this->Categories = $Categories;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function setCategory( \stdClass $category ): Product {
		$this->Categories[] = $category;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function getWarehouses(): array {
		return $this->Warehouses;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function setWarehouses( array $Warehouses ): Product {
		$this->Warehouses = $Warehouses;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function setWarehouse( \stdClass $warehouse ): Product {
		$this->Warehouses[] = $warehouse;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function getCurrencyPrices(): array {
		return $this->CurrencyPrices;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function setCurrencyPrices( array $CurrencyPrices ): Product {
		$this->CurrencyPrices = $CurrencyPrices;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function setCurrencyPrice( \stdClass $currency_price ): Product {
		$this->CurrencyPrices[] = $currency_price;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function getUnits(): array {
		return $this->Units;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function setUnits( array $Units ): Product {
		$this->Units = $Units;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function setUnit( \stdClass $unit ): Product {
		$this->Units[] = $unit;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function getAlternative(): array {
		return $this->Alternative;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function setAlternative( array $Alternative ): Product {
		$this->Alternative = $Alternative;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function setAlternativeItem( \stdClass $alternative ): Product {
		$this->Alternative[] = $alternative;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function getChanges(): array {
		return $this->Changes;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function setChanges( array $Changes ): Product {
		$this->Changes = $Changes;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function setChange( \stdClass $change ): Product {
		$this->Changes[] = $change;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function getMemos(): array {
		return $this->Memos;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function setMemos( array $Memos ): Product {
		$this->Memos = $Memos;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function setMemo( \stdClass $memo ): Product {
		$this->Memos[] = $memo;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function getVendors(): array {
		return $this->Vendors;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function setVendors( array $Vendors ): Product {
		$this->Vendors = $Vendors;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function setVendor( \stdClass $vendor ): Product {
		$this->Vendors[] = $vendor;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function jsonSerialize(): string {
		return json_encode( get_object_vars( $this ) );
	}
}
