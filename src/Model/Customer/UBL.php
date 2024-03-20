<?php

namespace Model\Customer;

use JsonSerializable;
class UBL implements JsonSerializable {
  protected string|null $ID;

  protected string|null $Prefix;

  protected bool|null $Enabled;

  protected string|null $AccountingCostType;

  protected string|null $AccountingCost;

  public function createUBLFromDKData($ubl): void
  {
    $this->setID($ubl->ID ?? null);
    $this->setAccountingCost($ubl->AccountingCost ?? null);
    $this->setPrefix($ubl->Prefix ?? null);
    $this->setEnabled($ubl->Enabled ?? false);
    $this->setAccountingCostType($ubl->AccountingCostType ?? 0);
  }
  public function getID(): string
  {
    return $this->ID;
  }

  public function setID(string|null $ID): UBL
  {
    $this->ID = $ID;
    return $this;
  }

  public function getPrefix(): string
  {
    return $this->Prefix;
  }

  public function setPrefix(string|null $Prefix): UBL
  {
    $this->Prefix = $Prefix;
    return $this;
  }

  public function isEnabled(): bool
  {
    return $this->Enabled;
  }

  public function setEnabled(bool $Enabled): UBL
  {
    $this->Enabled = $Enabled;
    return $this;
  }

  public function getAccountingCostType(): string
  {
    return $this->AccountingCostType;
  }

  public function setAccountingCostType(string|null $AccountingCostType): UBL
  {
    $this->AccountingCostType = $AccountingCostType;
    return $this;
  }

  public function getAccountingCost(): string
  {
    return $this->AccountingCost;
  }

  public function setAccountingCost(string|null $AccountingCost): UBL
  {
    $this->AccountingCost = $AccountingCost;
    return $this;
  }


  public function jsonSerialize(): string
  {
    return json_encode(get_object_vars($this));
  }
}
