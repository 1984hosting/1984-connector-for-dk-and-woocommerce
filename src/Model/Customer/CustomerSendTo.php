<?php

declare(strict_types = 1);

namespace NineteenEightyFour\NineteenEightyWoo\Model\Customer;

use JsonSerializable;

/**
 * DTO for CustomerSendTo, part of DK Customer.
 */
class CustomerSendTo implements JsonSerializable {
	/**
	 * @var bool $Printer
	 */
	protected bool $Printer;

	/**
	 * @var bool $ClaimToPrinter
	 */
	protected bool $ClaimToPrinter;

	/**
	 * @var bool $Email
	 */
	protected bool $Email;

	/**
	 * @var bool $EDIInvoice
	 */
	protected bool $EDIInvoice;

	/**
	 * @var bool $PublishingSystem
	 */
	protected bool $PublishingSystem;

	/**
	 * @param $send_to
	 * @return void
	 */
	public function createSendToFromDKData( $send_to ): void {
		$this->setPrinter( $send_to->Printer ?? false );
		$this->setClaimToPrinter( $send_to->ClaimToPrinter ?? false );
		$this->setEmail( $send_to->Email ?? false );
		$this->setEDIInvoice( $send_to->EDIInvoice ?? false );
		$this->setPublishingSystem( $send_to->PublishingSystem ?? false );
	}

	/**
	 * @return bool
	 */
	public function isPrinter(): bool {
		return $this->Printer;
	}

	/**
	 * @param bool $Printer
	 * @return $this
	 */
	public function setPrinter( bool $Printer ): CustomerSendTo {
		$this->Printer = $Printer;
		return $this;
	}

	/**
	 * @return bool
	 */
	public function isClaimToPrinter(): bool {
		return $this->ClaimToPrinter;
	}

	/**
	 * @param bool $ClaimToPrinter
	 * @return $this
	 */
	public function setClaimToPrinter( bool $ClaimToPrinter ): CustomerSendTo {
		$this->ClaimToPrinter = $ClaimToPrinter;
		return $this;
	}

	/**
	 * @return bool
	 */
	public function isEmail(): bool {
		return $this->Email;
	}

	/**
	 * @param bool $Email
	 * @return $this
	 */
	public function setEmail( bool $Email ): CustomerSendTo {
		$this->Email = $Email;
		return $this;
	}

	/**
	 * @return bool
	 */
	public function isEDIInvoice(): bool {
		return $this->EDIInvoice;
	}

	/**
	 * @param bool $EDIInvoice
	 * @return $this
	 */
	public function setEDIInvoice( bool $EDIInvoice ): CustomerSendTo {
		$this->EDIInvoice = $EDIInvoice;
		return $this;
	}

	/**
	 * @return bool
	 */
	public function isPublishingSystem(): bool {
		return $this->PublishingSystem;
	}

	/**
	 * @param bool $PublishingSystem
	 * @return $this
	 */
	public function setPublishingSystem( bool $PublishingSystem ): CustomerSendTo {
		$this->PublishingSystem = $PublishingSystem;
		return $this;
	}

	/**
	 * @return string
	 */
	public function jsonSerialize(): string {
		return json_encode( get_object_vars( $this ) );
	}
}
