<?php

namespace Model\Product;

class Attachments implements \JsonSerializable {
  protected int $ID;

  protected string $Name;

  protected int $Size;

  protected string $Linked;

  protected string $MD5Hash;

  protected bool $ShowOnWeb = true;

  public function __construct() {

  }

  public function getID(): int
  {
    return $this->ID;
  }

  public function setID(int $ID): Attachments
  {
    $this->ID = $ID;
    return $this;
  }

  public function getName(): string
  {
    return $this->Name;
  }

  public function setName(string $Name): Attachments
  {
    $this->Name = $Name;
    return $this;
  }

  public function getSize(): int
  {
    return $this->Size;
  }

  public function setSize(int $Size): Attachments
  {
    $this->Size = $Size;
    return $this;
  }

  public function getLinked(): string
  {
    return $this->Linked;
  }

  public function setLinked(string $Linked): Attachments
  {
    $this->Linked = $Linked;
    return $this;
  }

  public function getMD5Hash(): string
  {
    return $this->MD5Hash;
  }

  public function setMD5Hash(string $MD5Hash): Attachments
  {
    $this->MD5Hash = $MD5Hash;
    return $this;
  }

  public function isShowOnWeb(): bool
  {
    return $this->ShowOnWeb;
  }

  public function setShowOnWeb(bool $ShowOnWeb): Attachments
  {
    $this->ShowOnWeb = $ShowOnWeb;
    return $this;
  }



  public function jsonSerialize(): string
  {
    return json_encode(get_object_vars($this));
  }
}
