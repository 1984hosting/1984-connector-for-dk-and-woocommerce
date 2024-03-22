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

	public function createVariationModelFromDKData( stdClass $categories ) {
		// @TODO: Implement this function
	}

	public function getID(): string {
		return $this->ID;
	}

	public function setID( string $ID ): Categories {
		$this->ID = $ID;
		return $this;
	}

	public function getSubCategories(): array {
		return $this->SubCategories;
	}

	public function setSubCategories( array $SubCategories ): Categories {
		$this->SubCategories = $SubCategories;
		return $this;
	}

	public function getDescription(): string {
		return $this->Description;
	}

	public function setDescription( string $Description ): Categories {
		$this->Description = $Description;
		return $this;
	}

	public function isIsActive(): bool {
		return $this->IsActive;
	}

	public function setIsActive( bool $IsActive ): Categories {
		$this->IsActive = $IsActive;
		return $this;
	}


	public function jsonSerialize(): string {
		return json_encode( get_object_vars( $this ) );
	}
}
