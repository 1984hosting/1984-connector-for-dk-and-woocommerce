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
	 */
	public function createCustomerConditionFromDKData( $customer_condition ): void {
		$this->setCreditLimit( $customer_condition->CreditLimit ?? 0 );
		$this->setCreditMax( $customer_condition->CreditMax ?? 0 );
		$this->setDisableSale( $customer_condition->DisableSale ?? false );
		$this->setDisableRetail( $customer_condition->DisableRetail ?? false );
	}

	public function getCreditLimit(): float {
		return $this->CreditLimit;
	}

	/**
	 * @return $this
	 */
	public function setCreditLimit( float $CreditLimit ): CustomerCondition {
		$this->CreditLimit = $CreditLimit;
		return $this;
	}

	public function getCreditMax(): float {
		return $this->CreditMax;
	}

	/**
	 * @return $this
	 */
	public function setCreditMax( float $CreditMax ): CustomerCondition {
		$this->CreditMax = $CreditMax;
		return $this;
	}

	public function isDisableSale(): bool {
		return $this->DisableSale;
	}

	/**
	 * @return $this
	 */
	public function setDisableSale( bool $DisableSale ): CustomerCondition {
		$this->DisableSale = $DisableSale;
		return $this;
	}

	public function isDisableRetail(): bool {
		return $this->DisableRetail;
	}

	/**
	 * @return $this
	 */
	public function setDisableRetail( bool $DisableRetail ): CustomerCondition {
		$this->DisableRetail = $DisableRetail;
		return $this;
	}

	public function jsonSerialize(): string {
		return json_encode( get_object_vars( $this ) );
	}
}
