<?php

namespace NineteenEightyFour\NineteenEightyWoo\Model\Customer;

use JsonSerializable;

class CustomerMemoModel implements JsonSerializable {
  protected ?string $PageName;

  protected ?string $PlainText;

  protected ?string $Modified;

  protected int $RecordID = 0;

  public function getPageName(): ?string
  {
    return $this->PageName;
  }

  public function setPageName(?string $PageName): CustomerMemoModel
  {
    $this->PageName = $PageName;
    return $this;
  }

  public function getPlainText(): ?string
  {
    return $this->PlainText;
  }

  public function setPlainText(?string $PlainText): CustomerMemoModel
  {
    $this->PlainText = $PlainText;
    return $this;
  }

  public function getModified(): ?string
  {
    return $this->Modified;
  }

  public function setModified(?string $Modified): CustomerMemoModel
  {
    $this->Modified = $Modified;
    return $this;
  }

  public function getRecordID(): int
  {
    return $this->RecordID;
  }

  public function setRecordID(int $RecordID): CustomerMemoModel
  {
    $this->RecordID = $RecordID;
    return $this;
  }

  public function jsonSerialize(): string
  {
    return json_encode(get_object_vars($this));
  }
}
