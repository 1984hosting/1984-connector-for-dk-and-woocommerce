<?php

declare(strict_types = 1);

namespace NineteenEightyFour\NineteenEightyWoo\Model\Product;

use stdClass;

/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
 * The Categories DTO class for DK
 */
class Categories implements \JsonSerializable {
	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string $ID
	 */
	protected string $ID;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var array $SubCategories
	 */
	protected array $SubCategories;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string $Description
	 */
	protected string $Description;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var bool $IsActive
	 */
	protected bool $IsActive;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 *
	 */
	public function createVariationModelFromDKData(stdClass $categories) {
		// @TODO: Implement this function
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return string
	 */
	public function getID() : string {
		return $this->ID;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param string $ID
	 * @return Categories
	 */
	public function setID(string $ID ) : Categories {
		$this->ID = $ID;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return array
	 */
	public function getSubCategories() : array {
		return $this->SubCategories;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param array $SubCategories
	 * @return Categories
	 */
	public function setSubCategories(array $SubCategories ) : Categories {
		$this->SubCategories = $SubCategories;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return string
	 */
	public function getDescription() : string {
		return $this->Description;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param string $Description
	 * @return Categories
	 */
	public function setDescription(string $Description ) : Categories {
		$this->Description = $Description;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return bool
	 */
	public function isIsActive() : bool {
		return $this->IsActive;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param bool $IsActive
	 * @return Categories
	 */
	public function setIsActive(bool $IsActive ) : Categories {
		$this->IsActive = $IsActive;
		return $this;
	}


	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return string
	 */
	public function jsonSerialize() : string {
		return json_encode( get_object_vars( $this ) );
	}
}
