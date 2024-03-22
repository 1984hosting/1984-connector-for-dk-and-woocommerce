<?php

declare(strict_types = 1);

namespace NineteenEightyFour\NineteenEightyWoo\Model\Customer;

use JsonSerializable;

/**
 * DTO for CustomerSendTo, part of DK Customer.
 */
class CustomerSendTo implements JsonSerializable {
	protected bool $Printer;

	protected bool $ClaimToPrinter;

	protected bool $Email;

	protected bool $EDIInvoice;

	protected bool $PublishingSystem;

	/**
	 * @param $send_to
	 */
	public function createSendToFromDKData( $send_to ): void {
		$this->setPrinter( $send_to->Printer ?? false );
		$this->setClaimToPrinter( $send_to->ClaimToPrinter ?? false );
		$this->setEmail( $send_to->Email ?? false );
		$this->setEDIInvoice( $send_to->EDIInvoice ?? false );
		$this->setPublishingSystem( $send_to->PublishingSystem ?? false );
	}

	public function isPrinter(): bool {
		return $this->Printer;
	}

	/**
	 * @return $this
	 */
	public function setPrinter( bool $Printer ): CustomerSendTo {
		$this->Printer = $Printer;
		return $this;
	}

	public function isClaimToPrinter(): bool {
		return $this->ClaimToPrinter;
	}

	/**
	 * @return $this
	 */
	public function setClaimToPrinter( bool $ClaimToPrinter ): CustomerSendTo {
		$this->ClaimToPrinter = $ClaimToPrinter;
		return $this;
	}

	public function isEmail(): bool {
		return $this->Email;
	}

	/**
	 * @return $this
	 */
	public function setEmail( bool $Email ): CustomerSendTo {
		$this->Email = $Email;
		return $this;
	}

	public function isEDIInvoice(): bool {
		return $this->EDIInvoice;
	}

	/**
	 * @return $this
	 */
	public function setEDIInvoice( bool $EDIInvoice ): CustomerSendTo {
		$this->EDIInvoice = $EDIInvoice;
		return $this;
	}

	public function isPublishingSystem(): bool {
		return $this->PublishingSystem;
	}

	/**
	 * @return $this
	 */
	public function setPublishingSystem( bool $PublishingSystem ): CustomerSendTo {
		$this->PublishingSystem = $PublishingSystem;
		return $this;
	}

	public function jsonSerialize(): string {
		return json_encode( get_object_vars( $this ) );
	}
}
