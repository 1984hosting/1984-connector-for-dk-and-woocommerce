<?php

declare(strict_types = 1);

namespace NineteenEightyFour\NineteenEightyWoo\Model;

use JsonSerializable;

/**
 * The FieldChangeModel DTO class for DK
 */
class FieldChangeModel implements JsonSerializable {
	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string
	 */
	protected string $Name;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string
	 */
	protected string $Value;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function getName(): string {
		return $this->Name;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function setName( string $Name ): FieldChangeModel {
		$this->Name = $Name;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function getValue(): string {
		return $this->Value;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function setValue( string $Value ): FieldChangeModel {
		$this->Value = $Value;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function jsonSerialize(): string {
		return json_encode( get_object_vars( $this ) );
	}
}
