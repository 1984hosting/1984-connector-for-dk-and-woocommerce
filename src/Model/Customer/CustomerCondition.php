<?php

declare(strict_types = 1);

namespace NineteenEightyFour\NineteenEightyWoo\Model\Customer;

use JsonSerializable;

/**
 * DTO for Customer Condition, part of DK Customer.
 */
class CustomerCondition implements JsonSerializable {
	/**
	 * @var float $CreditLimit
	 */
	protected float $CreditLimit = 0;

	/**
	 * @var float $CreditMax
	 */
	protected float $CreditMax = 0;

	/**
	 * @var bool $DisableSale
	 */
	protected bool $DisableSale = false;

	/**
	 * @var bool $DisableRetail
	 */
	protected bool $DisableRetail = false;

	/**
	 * @param $customer_condition
	 * @return void
	 */
	public function createCustomerConditionFromDKData( $customer_condition ): void {
		$this->setCreditLimit( $customer_condition->CreditLimit ?? 0 );
		$this->setCreditMax( $customer_condition->CreditMax ?? 0 );
		$this->setDisableSale( $customer_condition->DisableSale ?? false );
		$this->setDisableRetail( $customer_condition->DisableRetail ?? false );
	}

	/**
	 * @return float
	 */
	public function getCreditLimit(): float {
		return $this->CreditLimit;
	}

	/**
	 * @param float $CreditLimit
	 * @return $this
	 */
	public function setCreditLimit( float $CreditLimit ): CustomerCondition {
		$this->CreditLimit = $CreditLimit;
		return $this;
	}

	/**
	 * @return float
	 */
	public function getCreditMax(): float {
		return $this->CreditMax;
	}

	/**
	 * @param float $CreditMax
	 * @return $this
	 */
	public function setCreditMax( float $CreditMax ): CustomerCondition {
		$this->CreditMax = $CreditMax;
		return $this;
	}

	/**
	 * @return bool
	 */
	public function isDisableSale(): bool {
		return $this->DisableSale;
	}

	/**
	 * @param bool $DisableSale
	 * @return $this
	 */
	public function setDisableSale( bool $DisableSale ): CustomerCondition {
		$this->DisableSale = $DisableSale;
		return $this;
	}

	/**
	 * @return bool
	 */
	public function isDisableRetail(): bool {
		return $this->DisableRetail;
	}

	/**
	 * @param bool $DisableRetail
	 * @return $this
	 */
	public function setDisableRetail( bool $DisableRetail ): CustomerCondition {
		$this->DisableRetail = $DisableRetail;
		return $this;
	}

	/**
	 * @return string
	 */
	public function jsonSerialize(): string {
		return json_encode( get_object_vars( $this ) );
	}
}
