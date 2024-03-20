<?php

declare(strict_types = 1);

namespace NineteenEightyFour\NineteenEightyWoo\Model\Customer;

use JsonSerializable;
use stdClass;

class CustomerGroup implements JsonSerializable {
	/**
	 * @var string|null $Modified
	 */
	protected ?string $Modified;

	/**
	 * @var int $ID
	 */
	protected int $ID = 0;

	/**
	 * @var string|null $Number
	 */
	protected ?string $Number;

	/**
	 * @var string|null $Description
	 */
	protected ?string $Description;

	/**
	 * @param stdClass $customer_group
	 * @return $this
	 */
	public function createCustomerGroupFromDKData( stdClass $customer_group ): CustomerGroup {
		$this->setModified( $customer_group->Modified ?? null );
		$this->setID( $customer_group->ID ?? 0 );
		$this->setNumber( $customer_group->Number ?? null );
		$this->setDescription( $customer_group->Description ?? null );
		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getModified(): ?string {
		return $this->Modified;
	}

	/**
	 * @param string|null $Modified
	 * @return $this
	 */
	public function setModified( ?string $Modified ): CustomerGroup {
		$this->Modified = $Modified;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getID(): int {
		return $this->ID;
	}

	/**
	 * @param int $ID
	 * @return $this
	 */
	public function setID( int $ID ): CustomerGroup {
		$this->ID = $ID;
		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getNumber(): ?string {
		return $this->Number;
	}

	/**
	 * @param string|null $Number
	 * @return $this
	 */
	public function setNumber( ?string $Number ): CustomerGroup {
		$this->Number = $Number;
		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getDescription(): ?string {
		return $this->Description;
	}

	/**
	 * @param string|null $Description
	 * @return $this
	 */
	public function setDescription( ?string $Description ): CustomerGroup {
		$this->Description = $Description;
		return $this;
	}

	/**
	 * @return string
	 */
	public function jsonSerialize(): string {
		return json_encode( get_object_vars( $this ) );
	}
}
