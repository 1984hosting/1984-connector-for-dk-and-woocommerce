<?php

namespace Model\Customer;

use JsonSerializable;

class CustomerPropertyModel implements JsonSerializable {
  protected ?string $ID;

  protected ?string $Attribute;

  protected ?string $Option;

  protected ?string $Comment;

  protected ?string $Modified;

  public function getID(): ?string
  {
    return $this->ID;
  }

  public function setID(?string $ID): CustomerPropertyModel
  {
    $this->ID = $ID;
    return $this;
  }

  public function getAttribute(): ?string
  {
    return $this->Attribute;
  }

  public function setAttribute(?string $Attribute): CustomerPropertyModel
  {
    $this->Attribute = $Attribute;
    return $this;
  }

  public function getOption(): ?string
  {
    return $this->Option;
  }

  public function setOption(?string $Option): CustomerPropertyModel
  {
    $this->Option = $Option;
    return $this;
  }

  public function getComment(): ?string
  {
    return $this->Comment;
  }

  public function setComment(?string $Comment): CustomerPropertyModel
  {
    $this->Comment = $Comment;
    return $this;
  }

  public function getModified(): ?string
  {
    return $this->Modified;
  }

  public function setModified(?string $Modified): CustomerPropertyModel
  {
    $this->Modified = $Modified;
    return $this;
  }

  public function jsonSerialize(): string
  {
    return json_encode(get_object_vars($this));
  }
}
