<?php

namespace Model;

use JsonSerializable;

class ChangesModel implements JsonSerializable {
  protected string $Modified;

  protected string $By;

  protected array $Fields;

  public function getModified(): string
  {
    return $this->Modified;
  }

  public function setModified(string $Modified): ChangesModel
  {
    $this->Modified = $Modified;
    return $this;
  }

  public function getBy(): string
  {
    return $this->By;
  }

  public function setBy(string $By): ChangesModel
  {
    $this->By = $By;
    return $this;
  }

  public function getFields(): array
  {
    return $this->Fields;
  }

  public function setFields(array $Fields): ChangesModel
  {
    $this->Fields = $Fields;
    return $this;
  }


  public function jsonSerialize(): string
  {
    return json_encode(get_object_vars($this));
  }
}
