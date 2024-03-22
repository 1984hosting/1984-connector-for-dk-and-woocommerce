<?php

declare(strict_types = 1);

namespace NineteenEightyFour\NineteenEightyWoo\Model\Customer;

use JsonSerializable;

/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
 * DTO for Customer Condition, part of DK Customer.
 */
class CustomerCondition implements JsonSerializable {
	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var float $CreditLimit
	 */
	protected float $CreditLimit = 0;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var float $CreditMax
	 */
	protected float $CreditMax = 0;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var bool $DisableSale
	 */
	protected bool $DisableSale = false;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var bool $DisableRetail
	 */
	protected bool $DisableRetail = false;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param $customer_condition
	 * @return void
	 */
	public function createCustomerConditionFromDKData( $customer_condition ) : void {
		$this->setCreditLimit( $customer_condition->CreditLimit ?? 0 );
		$this->setCreditMax( $customer_condition->CreditMax ?? 0 );
		$this->setDisableSale( $customer_condition->DisableSale ?? false );
		$this->setDisableRetail( $customer_condition->DisableRetail ?? false );
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return float
	 */
	public function getCreditLimit() : float {
		return $this->CreditLimit;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param float $CreditLimit
	 * @return $this
	 */
	public function setCreditLimit( float $CreditLimit ) : CustomerCondition {
		$this->CreditLimit = $CreditLimit;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return float
	 */
	public function getCreditMax() : float {
		return $this->CreditMax;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param float $CreditMax
	 * @return $this
	 */
	public function setCreditMax( float $CreditMax ) : CustomerCondition {
		$this->CreditMax = $CreditMax;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return bool
	 */
	public function isDisableSale() : bool {
		return $this->DisableSale;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param bool $DisableSale
	 * @return $this
	 */
	public function setDisableSale( bool $DisableSale ) : CustomerCondition {
		$this->DisableSale = $DisableSale;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return bool
	 */
	public function isDisableRetail() : bool {
		return $this->DisableRetail;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param bool $DisableRetail
	 * @return $this
	 */
	public function setDisableRetail( bool $DisableRetail ) : CustomerCondition {
		$this->DisableRetail = $DisableRetail;
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
