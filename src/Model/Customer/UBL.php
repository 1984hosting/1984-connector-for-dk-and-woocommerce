<?php

declare(strict_types = 1);

namespace NineteenEightyFour\NineteenEightyWoo\Model\Customer;

use JsonSerializable;

/**
 * The UBL DTO class for DK
 */
class UBL implements JsonSerializable {
	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string|null $ID
	 */
	protected string|null $ID;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string|null $Prefix
	 */
	protected string|null $Prefix;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var bool|null $Enabled
	 */
	protected bool|null $Enabled;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string|null $AccountingCostType
	 */
	protected string|null $AccountingCostType;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string|null $AccountingCost
	 */
	protected string|null $AccountingCost;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param $ubl
	 * @return void
	 */
	public function createUBLFromDKData( $ubl ) :void {
		$this->setID( $ubl->ID ?? null );
		$this->setAccountingCost( $ubl->AccountingCost ?? null );
		$this->setPrefix( $ubl->Prefix ?? null );
		$this->setEnabled( $ubl->Enabled ?? false );
		$this->setAccountingCostType( $ubl->AccountingCostType ?? 0 );
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return string
	 */
	public function getID() :string {
		return $this->ID;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param string|null $ID
	 * @return $this
	 */
	public function setID( string|null $ID ) :UBL {
		$this->ID = $ID;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return string
	 */
	public function getPrefix() :string {
		return $this->Prefix;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param string|null $Prefix
	 * @return $this
	 */
	public function setPrefix( string|null $Prefix ) :UBL {
		$this->Prefix = $Prefix;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return bool
	 */
	public function isEnabled() :bool {
		return $this->Enabled;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param bool $Enabled
	 * @return $this
	 */
	public function setEnabled( bool $Enabled ) :UBL {
		$this->Enabled = $Enabled;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return string
	 */
	public function getAccountingCostType() :string {
		return $this->AccountingCostType;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param string|null $AccountingCostType
	 * @return $this
	 */
	public function setAccountingCostType( string|null $AccountingCostType ) :UBL {
		$this->AccountingCostType = $AccountingCostType;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return string
	 */
	public function getAccountingCost() :string {
		return $this->AccountingCost;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param string|null $AccountingCost
	 * @return $this
	 */
	public function setAccountingCost( string|null $AccountingCost ) :UBL {
		$this->AccountingCost = $AccountingCost;
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
