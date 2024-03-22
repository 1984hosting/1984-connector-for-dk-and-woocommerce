<?php

declare(strict_types = 1);

namespace NineteenEightyFour\NineteenEightyWoo\Model\Order;

use JsonSerializable;
use stdClass;

/**
 * The VariationModel DTO class for DK
 */
class VariationModel implements JsonSerializable {
	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string|null $Code
	 */
	protected string|null $Code;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string|null $Code2
	 */
	protected string|null $Code2;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string|null $Description
	 */
	protected string|null $Description;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string|null $Description2
	 */
	protected string|null $Description2;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var float $Quantity
	 */
	protected float $Quantity = 0;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var float $QuantityOnBackOrders
	 */
	protected float $QuantityOnBackOrders = 0;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param stdClass $variation
	 * @return void
	 */
	public function createVariationModelFromDKData(stdClass $variation ) :void {
		$this->setCode( $variation->Code ?? null );
		$this->setCode2( $variation->Code2 ?? null );
		$this->setDescription( $variation->Description ?? null );
		$this->setDescription2( $variation->Description2 ?? null );
		$this->setQuantity( $variation->Quantity ?? 0 );
		$this->setQuantityOnBackOrders( $variation->QuantityOnBackOrders ?? 0 );
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return string|null
	 */
	public function getCode() :?string {
		return $this->Code;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param string|null $Code
	 * @return VariationModel
	 */
	public function setCode(?string $Code ) :VariationModel {
		$this->Code = $Code;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return string|null
	 */
	public function getCode2() :?string {
		return $this->Code2;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param string|null $Code2
	 * @return VariationModel
	 */
	public function setCode2(?string $Code2 ) :VariationModel {
		$this->Code2 = $Code2;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return string|null
	 */
	public function getDescription() :?string {
		return $this->Description;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param string|null $Description
	 * @return VariationModel
	 */
	public function setDescription(?string $Description ) :VariationModel {
		$this->Description = $Description;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return string|null
	 */
	public function getDescription2() :?string {
		return $this->Description2;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param string|null $Description2
	 * @return VariationModel
	 */
	public function setDescription2(?string $Description2 ) :VariationModel {
		$this->Description2 = $Description2;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return float
	 */
	public function getQuantity() :float {
		return $this->Quantity;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param float $Quantity
	 * @return VariationModel
	 */
	public function setQuantity(float $Quantity ) :VariationModel {
		$this->Quantity = $Quantity;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return float
	 */
	public function getQuantityOnBackOrders() :float {
		return $this->QuantityOnBackOrders;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param float $QuantityOnBackOrders
	 * @return VariationModel
	 */
	public function setQuantityOnBackOrders(float $QuantityOnBackOrders ) :VariationModel {
		$this->QuantityOnBackOrders = $QuantityOnBackOrders;
		return $this;
	}


	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return string
	 */
	public function jsonSerialize() :string {
		return json_encode( get_object_vars( $this ) );
	}
}
