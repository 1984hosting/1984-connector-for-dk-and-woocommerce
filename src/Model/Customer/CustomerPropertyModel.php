<?php

declare(strict_types = 1);

namespace NineteenEightyFour\NineteenEightyWoo\Model\Customer;

use JsonSerializable;

/**
 * The CustomerPropertyModel DTO class for DK
 */
class CustomerPropertyModel implements JsonSerializable {
	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string|null $ID
	 */
	protected ?string $ID;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string|null $Attribute
	 */
	protected ?string $Attribute;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string|null $Option
	 */
	protected ?string $Option;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string|null $Comment
	 */
	protected ?string $Comment;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string|null $Modified
	 */
	protected ?string $Modified;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function getID(): ?string {
		return $this->ID;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return $this
	 */
	public function setID( ?string $ID ): CustomerPropertyModel {
		$this->ID = $ID;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function getAttribute(): ?string {
		return $this->Attribute;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return $this
	 */
	public function setAttribute( ?string $Attribute ): CustomerPropertyModel {
		$this->Attribute = $Attribute;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function getOption(): ?string {
		return $this->Option;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return $this
	 */
	public function setOption( ?string $Option ): CustomerPropertyModel {
		$this->Option = $Option;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function getComment(): ?string {
		return $this->Comment;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return $this
	 */
	public function setComment( ?string $Comment ): CustomerPropertyModel {
		$this->Comment = $Comment;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function getModified(): ?string {
		return $this->Modified;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return $this
	 */
	public function setModified( ?string $Modified ): CustomerPropertyModel {
		$this->Modified = $Modified;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function jsonSerialize(): string {
		return json_encode( get_object_vars( $this ) );
	}
}
