<?php

declare(strict_types = 1);

namespace NineteenEightyFour\NineteenEightyWoo\Model\Customer;

use JsonSerializable;

/**
 * DTO for CustomerSendTo, part of DK Customer.
 */
class CustomerSendTo implements JsonSerializable {
	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var bool $Printer
	 */
	protected bool $Printer;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var bool $ClaimToPrinter
	 */
	protected bool $ClaimToPrinter;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var bool $Email
	 */
	protected bool $Email;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var bool $EDIInvoice
	 */
	protected bool $EDIInvoice;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var bool $PublishingSystem
	 */
	protected bool $PublishingSystem;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param $send_to
	 * @return void
	 */
	public function createSendToFromDKData( $send_to ) :void {
		$this->setPrinter( $send_to->Printer ?? false );
		$this->setClaimToPrinter( $send_to->ClaimToPrinter ?? false );
		$this->setEmail( $send_to->Email ?? false );
		$this->setEDIInvoice( $send_to->EDIInvoice ?? false );
		$this->setPublishingSystem( $send_to->PublishingSystem ?? false );
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return bool
	 */
	public function isPrinter() :bool {
		return $this->Printer;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param bool $Printer
	 * @return $this
	 */
	public function setPrinter( bool $Printer ) :CustomerSendTo {
		$this->Printer = $Printer;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return bool
	 */
	public function isClaimToPrinter() :bool {
		return $this->ClaimToPrinter;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param bool $ClaimToPrinter
	 * @return $this
	 */
	public function setClaimToPrinter( bool $ClaimToPrinter ) :CustomerSendTo {
		$this->ClaimToPrinter = $ClaimToPrinter;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return bool
	 */
	public function isEmail() :bool {
		return $this->Email;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param bool $Email
	 * @return $this
	 */
	public function setEmail( bool $Email ) :CustomerSendTo {
		$this->Email = $Email;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return bool
	 */
	public function isEDIInvoice() :bool {
		return $this->EDIInvoice;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param bool $EDIInvoice
	 * @return $this
	 */
	public function setEDIInvoice( bool $EDIInvoice ) :CustomerSendTo {
		$this->EDIInvoice = $EDIInvoice;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return bool
	 */
	public function isPublishingSystem() :bool {
		return $this->PublishingSystem;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param bool $PublishingSystem
	 * @return $this
	 */
	public function setPublishingSystem( bool $PublishingSystem ) :CustomerSendTo {
		$this->PublishingSystem = $PublishingSystem;
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
