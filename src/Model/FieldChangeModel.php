<?php

declare(strict_types = 1);

namespace NineteenEightyFour\NineteenEightyWoo\Model;

use JsonSerializable;

/**
 * The FieldChangeModel DTO class for DK
 */
class FieldChangeModel implements JsonSerializable {
	/**
	 * @var string
	 */
	protected string $Name;

	/**
	 * @var string
	 */
	protected string $Value;

	public function getName(): string {
		return $this->Name;
	}

	public function setName( string $Name ): FieldChangeModel {
		$this->Name = $Name;
		return $this;
	}

	public function getValue(): string {
		return $this->Value;
	}

	public function setValue( string $Value ): FieldChangeModel {
		$this->Value = $Value;
		return $this;
	}

	public function jsonSerialize(): string {
		return json_encode( get_object_vars( $this ) );
	}
}
