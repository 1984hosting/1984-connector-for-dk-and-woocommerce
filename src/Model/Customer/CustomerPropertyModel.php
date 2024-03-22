<?php

declare(strict_types = 1);

namespace NineteenEightyFour\NineteenEightyWoo\Model\Customer;

use JsonSerializable;

/**
 * The CustomerPropertyModel DTO class for DK
 */
class CustomerPropertyModel implements JsonSerializable {
	/**
	 * @var string|null $ID
	 */
	protected ?string $ID;

	/**
	 * @var string|null $Attribute
	 */
	protected ?string $Attribute;

	/**
	 * @var string|null $Option
	 */
	protected ?string $Option;

	/**
	 * @var string|null $Comment
	 */
	protected ?string $Comment;

	/**
	 * @var string|null $Modified
	 */
	protected ?string $Modified;

	public function getID(): ?string {
		return $this->ID;
	}

	/**
	 * @return $this
	 */
	public function setID( ?string $ID ): CustomerPropertyModel {
		$this->ID = $ID;
		return $this;
	}

	public function getAttribute(): ?string {
		return $this->Attribute;
	}

	/**
	 * @return $this
	 */
	public function setAttribute( ?string $Attribute ): CustomerPropertyModel {
		$this->Attribute = $Attribute;
		return $this;
	}

	public function getOption(): ?string {
		return $this->Option;
	}

	/**
	 * @return $this
	 */
	public function setOption( ?string $Option ): CustomerPropertyModel {
		$this->Option = $Option;
		return $this;
	}

	public function getComment(): ?string {
		return $this->Comment;
	}

	/**
	 * @return $this
	 */
	public function setComment( ?string $Comment ): CustomerPropertyModel {
		$this->Comment = $Comment;
		return $this;
	}

	public function getModified(): ?string {
		return $this->Modified;
	}

	/**
	 * @return $this
	 */
	public function setModified( ?string $Modified ): CustomerPropertyModel {
		$this->Modified = $Modified;
		return $this;
	}

	public function jsonSerialize(): string {
		return json_encode( get_object_vars( $this ) );
	}
}
