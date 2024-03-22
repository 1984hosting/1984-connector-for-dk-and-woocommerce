<?php

declare(strict_types = 1);

namespace NineteenEightyFour\NineteenEightyWoo\Model\Product;

use stdClass;

/**
 * The Categories DTO class for DK
 */
class Categories implements \JsonSerializable {
	/**
	 * @var string $ID
	 */
	protected string $ID;

	/**
	 * @var array $SubCategories
	 */
	protected array $SubCategories;

	/**
	 * @var string $Description
	 */
	protected string $Description;

	/**
	 * @var bool $IsActive
	 */
	protected bool $IsActive;

	/**
	 *
	 */
	public function createVariationModelFromDKData(stdClass $categories) {
		// @TODO: Implement this function
	}

	/**
	 * @return string
	 */
	public function getID(): string {
		return $this->ID;
	}

	/**
	 * @param string $ID
	 * @return Categories
	 */
	public function setID(string $ID ): Categories {
		$this->ID = $ID;
		return $this;
	}

	/**
	 * @return array
	 */
	public function getSubCategories(): array {
		return $this->SubCategories;
	}

	/**
	 * @param array $SubCategories
	 * @return Categories
	 */
	public function setSubCategories(array $SubCategories ): Categories {
		$this->SubCategories = $SubCategories;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getDescription(): string {
		return $this->Description;
	}

	/**
	 * @param string $Description
	 * @return Categories
	 */
	public function setDescription(string $Description ): Categories {
		$this->Description = $Description;
		return $this;
	}

	/**
	 * @return bool
	 */
	public function isIsActive(): bool {
		return $this->IsActive;
	}

	/**
	 * @param bool $IsActive
	 * @return Categories
	 */
	public function setIsActive(bool $IsActive ): Categories {
		$this->IsActive = $IsActive;
		return $this;
	}


	/**
	 * @return string
	 */
	public function jsonSerialize(): string {
		return json_encode( get_object_vars( $this ) );
	}
}
