<?php

declare(strict_types = 1);

namespace NineteenEightyFour\NineteenEightyWoo\Model\Customer;

use JsonSerializable;

/**
 * The UBL DTO class for DK
 */
class UBL implements JsonSerializable {
	/**
	 * @var string|null $ID
	 */
	protected string|null $ID;

	/**
	 * @var string|null $Prefix
	 */
	protected string|null $Prefix;

	/**
	 * @var bool|null $Enabled
	 */
	protected bool|null $Enabled;

	/**
	 * @var string|null $AccountingCostType
	 */
	protected string|null $AccountingCostType;

	/**
	 * @var string|null $AccountingCost
	 */
	protected string|null $AccountingCost;

	/**
	 * @param $ubl
	 */
	public function createUBLFromDKData( $ubl ): void {
		$this->setID( $ubl->ID ?? null );
		$this->setAccountingCost( $ubl->AccountingCost ?? null );
		$this->setPrefix( $ubl->Prefix ?? null );
		$this->setEnabled( $ubl->Enabled ?? false );
		$this->setAccountingCostType( $ubl->AccountingCostType ?? 0 );
	}

	public function getID(): string {
		return $this->ID;
	}

	/**
	 * @return $this
	 */
	public function setID( string|null $ID ): UBL {
		$this->ID = $ID;
		return $this;
	}

	public function getPrefix(): string {
		return $this->Prefix;
	}

	/**
	 * @return $this
	 */
	public function setPrefix( string|null $Prefix ): UBL {
		$this->Prefix = $Prefix;
		return $this;
	}

	public function isEnabled(): bool {
		return $this->Enabled;
	}

	/**
	 * @return $this
	 */
	public function setEnabled( bool $Enabled ): UBL {
		$this->Enabled = $Enabled;
		return $this;
	}

	public function getAccountingCostType(): string {
		return $this->AccountingCostType;
	}

	/**
	 * @return $this
	 */
	public function setAccountingCostType( string|null $AccountingCostType ): UBL {
		$this->AccountingCostType = $AccountingCostType;
		return $this;
	}

	public function getAccountingCost(): string {
		return $this->AccountingCost;
	}

	/**
	 * @return $this
	 */
	public function setAccountingCost( string|null $AccountingCost ): UBL {
		$this->AccountingCost = $AccountingCost;
		return $this;
	}

	public function jsonSerialize(): string {
		return json_encode( get_object_vars( $this ) );
	}
}
