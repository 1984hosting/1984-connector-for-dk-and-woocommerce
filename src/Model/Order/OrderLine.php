<?php
namespace Model\Order;

use JsonSerializable;
use stdClass;

class OrderLine implements JsonSerializable {
  protected int $SequenceNumber = 0;

  protected string $ItemCode;

  protected string|null $Text;

  protected string|null $Text2;

  protected string|null $Warehouse;

  protected string|null $Location;

  protected float $Quantity = 0;

  protected float $QuantityDelivered = 0;

  protected float $UnitPrice;

  protected float $UnitPriceWithTax;

  protected string|null $UnitCode;

  protected float $Discount = 0;

  protected float $DiscountPercent = 0;

  protected float $TotalAmount = 0;

  protected float $TotalAmountWithTax = 0;

  protected string|null $BarCode;

  protected string|null $Reference;

  protected string|null $EDIOrderNumber;

  protected int $UNDOrderNumber = 0;

  protected string|null $Memo;

  protected array $Variations = [];

  public function createOrderLineFromDKData(stdClass $OrderLine) {
    $this->setSequenceNumber($OrderLine->SequenceNumber ?? 0);
    $this->setItemCode($OrderLine->ItemCode ?? null);
    $this->setText($OrderLine->Text ?? null);
    $this->setText2($OrderLine->Text2 ?? null);
    $this->setWarehouse($OrderLine->Warehouse ?? null);
    $this->setLocation($OrderLine->Location ?? null);
    $this->setQuantity($OrderLine->Quantity ?? 0);
    $this->setQuantityDelivered($OrderLine->QuantityDelivered ?? 0);
    $this->setUnitPrice($OrderLine->UnitPrice ?? 0);
    $this->setUnitPriceWithTax($OrderLine->UnitPriceWithTax ?? 0);
    $this->setUnitCode($OrderLine->UnitCode ?? null);
    $this->setDiscount($OrderLine->Discount ?? 0);
    $this->setDiscountPercent($OrderLine->DiscountPercent ?? 0);
    $this->setTotalAmount($OrderLine->TotalAmount ?? 0);
    $this->setTotalAmountWithTax($OrderLine->TotalAmountWithTax ?? 0);
    $this->setBarCode($OrderLine->BarCode ?? null);
    $this->setReference($OrderLine->Reference ?? null);
    $this->setEDIOrderNumber($OrderLine->EDIOrderNumber ?? null);
    $this->setUNDOrderNumber($OrderLine->UNDOrderNumber ?? 0);
    $this->setVariations($OrderLine->Variations ?? []);
  }

  public function getSequenceNumber(): int
  {
    return $this->SequenceNumber;
  }

  public function setSequenceNumber(int $SequenceNumber): OrderLine
  {
    $this->SequenceNumber = $SequenceNumber;
    return $this;
  }

  public function getItemCode(): string
  {
    return $this->ItemCode;
  }

  public function setItemCode(string $ItemCode): OrderLine
  {
    $this->ItemCode = $ItemCode;
    return $this;
  }

  public function getText(): ?string
  {
    return $this->Text;
  }

  public function setText(?string $Text): OrderLine
  {
    $this->Text = $Text;
    return $this;
  }

  public function getText2(): ?string
  {
    return $this->Text2;
  }

  public function setText2(?string $Text2): OrderLine
  {
    $this->Text2 = $Text2;
    return $this;
  }

  public function getWarehouse(): ?string
  {
    return $this->Warehouse;
  }

  public function setWarehouse(?string $Warehouse): OrderLine
  {
    $this->Warehouse = $Warehouse;
    return $this;
  }

  public function getLocation(): ?string
  {
    return $this->Location;
  }

  public function setLocation(?string $Location): OrderLine
  {
    $this->Location = $Location;
    return $this;
  }

  public function getQuantity(): float
  {
    return $this->Quantity;
  }

  public function setQuantity(float $Quantity): OrderLine
  {
    $this->Quantity = $Quantity;
    return $this;
  }

  public function getQuantityDelivered(): float
  {
    return $this->QuantityDelivered;
  }

  public function setQuantityDelivered(float $QuantityDelivered): OrderLine
  {
    $this->QuantityDelivered = $QuantityDelivered;
    return $this;
  }

  public function getUnitPrice(): float
  {
    return $this->UnitPrice;
  }

  public function setUnitPrice(float $UnitPrice): OrderLine
  {
    $this->UnitPrice = $UnitPrice;
    return $this;
  }

  public function getUnitPriceWithTax(): float
  {
    return $this->UnitPriceWithTax;
  }

  public function setUnitPriceWithTax(float $UnitPriceWithTax): OrderLine
  {
    $this->UnitPriceWithTax = $UnitPriceWithTax;
    return $this;
  }

  public function getUnitCode(): ?string
  {
    return $this->UnitCode;
  }

  public function setUnitCode(?string $UnitCode): OrderLine
  {
    $this->UnitCode = $UnitCode;
    return $this;
  }

  public function getDiscount(): float
  {
    return $this->Discount;
  }

  public function setDiscount(float $Discount): OrderLine
  {
    $this->Discount = $Discount;
    return $this;
  }

  public function getDiscountPercent(): float
  {
    return $this->DiscountPercent;
  }

  public function setDiscountPercent(float $DiscountPercent): OrderLine
  {
    $this->DiscountPercent = $DiscountPercent;
    return $this;
  }

  public function getTotalAmount(): float
  {
    return $this->TotalAmount;
  }

  public function setTotalAmount(float $TotalAmount): OrderLine
  {
    $this->TotalAmount = $TotalAmount;
    return $this;
  }

  public function getTotalAmountWithTax(): float
  {
    return $this->TotalAmountWithTax;
  }

  public function setTotalAmountWithTax(float $TotalAmountWithTax): OrderLine
  {
    $this->TotalAmountWithTax = $TotalAmountWithTax;
    return $this;
  }

  public function getBarCode(): ?string
  {
    return $this->BarCode;
  }

  public function setBarCode(?string $BarCode): OrderLine
  {
    $this->BarCode = $BarCode;
    return $this;
  }

  public function getReference(): ?string
  {
    return $this->Reference;
  }

  public function setReference(?string $Reference): OrderLine
  {
    $this->Reference = $Reference;
    return $this;
  }

  public function getEDIOrderNumber(): ?string
  {
    return $this->EDIOrderNumber;
  }

  public function setEDIOrderNumber(?string $EDIOrderNumber): OrderLine
  {
    $this->EDIOrderNumber = $EDIOrderNumber;
    return $this;
  }

  public function getUNDOrderNumber(): int
  {
    return $this->UNDOrderNumber;
  }

  public function setUNDOrderNumber(int $UNDOrderNumber): OrderLine
  {
    $this->UNDOrderNumber = $UNDOrderNumber;
    return $this;
  }

  public function getMemo(): ?string
  {
    return $this->Memo;
  }

  public function setMemo(?string $Memo): OrderLine
  {
    $this->Memo = $Memo;
    return $this;
  }

  public function getVariations(): array
  {
    return $this->Variations;
  }

  public function setVariations(array $Variations): OrderLine
  {
    foreach($Variations as $variation) {
      $obj = new VariationModel();
      $obj->createVariationModelFromDKData($variation);
      $this->Variations[] = $obj;
    }
    return $this;
  }



  public function jsonSerialize(): string
  {
    return json_encode(get_object_vars($this));
  }
}
