<?php
namespace Model\Customer;

use JsonSerializable;
use stdClass;

class CustomerGroup implements JsonSerializable {
  protected ?string $Modified;

  protected int $ID = 0;

  protected ?string $Number;

  protected ?string $Description;

  public function createCustomerGroupFromDKData(stdClass $customer_group) : CustomerGroup {
    $this->setDescription($customer_group->Description ?? null);
    $this->setID($customer_group->ID ?? 0);
    $this->setNumber($customer_group->Number ?? null);
    $this->setDescription($customer_group->Description ?? null);
    return $this;
  }

  public function getModified(): string
  {
    return $this->Modified;
  }

  public function setModified(string $Modified): CustomerGroup
  {
    $this->Modified = $Modified;
    return $this;
  }

  public function getID(): int
  {
    return $this->ID;
  }

  public function setID(int $ID): CustomerGroup
  {
    $this->ID = $ID;
    return $this;
  }

  public function getNumber(): string
  {
    return $this->Number;
  }

  public function setNumber(string $Number): CustomerGroup
  {
    $this->Number = $Number;
    return $this;
  }

  public function getDescription(): string
  {
    return $this->Description;
  }

  public function setDescription(string $Description): CustomerGroup
  {
    $this->Description = $Description;
    return $this;
  }

  public function jsonSerialize(): string
  {
    return json_encode(get_object_vars($this));
  }
}
