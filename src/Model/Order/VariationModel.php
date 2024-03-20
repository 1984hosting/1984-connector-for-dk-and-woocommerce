<?php
namespace NineteenEightyFour\NineteenEightyWoo\Model\Order;

use JsonSerializable;
use stdClass;

class VariationModel implements JsonSerializable {
  protected string|null $Code;

  protected string|null $Code2;

  protected string|null $Description;

  protected string|null $Description2;

  protected float $Quantity = 0;

  protected float $QuantityOnBackOrders = 0;

  public function createVariationModelFromDKData(stdClass $variation) : void {
    $this->setCode($variation->Code ?? null);
    $this->setCode2($variation->Code2 ?? null);
    $this->setDescription($variation->Description ?? null);
    $this->setDescription2($variation->Description2 ?? null);
    $this->setQuantity($variation->Quantity ?? 0);
    $this->setQuantityOnBackOrders($variation->QuantityOnBackOrders ?? 0);
  }

  public function getCode(): ?string
  {
    return $this->Code;
  }

  public function setCode(?string $Code): VariationModel
  {
    $this->Code = $Code;
    return $this;
  }

  public function getCode2(): ?string
  {
    return $this->Code2;
  }

  public function setCode2(?string $Code2): VariationModel
  {
    $this->Code2 = $Code2;
    return $this;
  }

  public function getDescription(): ?string
  {
    return $this->Description;
  }

  public function setDescription(?string $Description): VariationModel
  {
    $this->Description = $Description;
    return $this;
  }

  public function getDescription2(): ?string
  {
    return $this->Description2;
  }

  public function setDescription2(?string $Description2): VariationModel
  {
    $this->Description2 = $Description2;
    return $this;
  }

  public function getQuantity(): float
  {
    return $this->Quantity;
  }

  public function setQuantity(float $Quantity): VariationModel
  {
    $this->Quantity = $Quantity;
    return $this;
  }

  public function getQuantityOnBackOrders(): float
  {
    return $this->QuantityOnBackOrders;
  }

  public function setQuantityOnBackOrders(float $QuantityOnBackOrders): VariationModel
  {
    $this->QuantityOnBackOrders = $QuantityOnBackOrders;
    return $this;
  }



  public function jsonSerialize(): string
  {
    return json_encode(get_object_vars($this));
  }
}
