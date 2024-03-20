<?php
namespace NineteenEightyFour\NineteenEightyWoo\Model\Product;

class Categories implements \JsonSerializable {

  protected string $ID;

  protected array $SubCategories;

  protected string $Description;

  protected bool $IsActive;

  public function __construct() {

  }

  public function getID(): string
  {
    return $this->ID;
  }

  public function setID(string $ID): Categories
  {
    $this->ID = $ID;
    return $this;
  }

  public function getSubCategories(): array
  {
    return $this->SubCategories;
  }

  public function setSubCategories(array $SubCategories): Categories
  {
    $this->SubCategories = $SubCategories;
    return $this;
  }

  public function getDescription(): string
  {
    return $this->Description;
  }

  public function setDescription(string $Description): Categories
  {
    $this->Description = $Description;
    return $this;
  }

  public function isIsActive(): bool
  {
    return $this->IsActive;
  }

  public function setIsActive(bool $IsActive): Categories
  {
    $this->IsActive = $IsActive;
    return $this;
  }



  public function jsonSerialize(): string
  {
    return json_encode(get_object_vars($this));
  }
}
