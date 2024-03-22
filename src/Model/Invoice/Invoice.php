<?php

declare(strict_types = 1);

namespace NineteenEightyFour\NineteenEightyWoo\Model\Invoice;

use JsonSerializable;
use Model\Order\OrderLine;
use stdClass;

/**
 * The Invoice DTO class for DK
 */
class Invoice implements JsonSerializable {
	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string|null $Number
	 */
	protected ?string $Number;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string|null $ObjectDate
	 */
	protected ?string $ObjectDate;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string|null $Created
	 */
	protected ?string $Created;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string|null $Modified
	 */
	protected ?string $Modified;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string|null $CreatedBy
	 */
	protected ?string $CreatedBy;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string|null $CNumber
	 */
	protected ?string $CNumber;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string|null $CName
	 */
	protected ?string $CName;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string|null $CAddress1
	 */
	protected ?string $CAddress1;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string|null $CAddress2
	 */
	protected ?string $CAddress2;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string|null $CAddress3
	 */
	protected ?string $CAddress3;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string|null $CAddress4
	 */
	protected ?string $CAddress4;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string|null $CZipCode
	 */
	protected ?string $CZipCode;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string|null $CCountryCode
	 */
	protected ?string $CCountryCode;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string|null $CSSNumber
	 */
	protected ?string $CSSNumber;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string|null $CPhone
	 */
	protected ?string $CPhone;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string|null $CContact
	 */
	protected ?string $CContact;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var int|null $RecordID
	 */
	protected ?int $RecordID = 0;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var int $OrderNumber
	 */
	protected int $OrderNumber = 0;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string|null $InvoiceDate
	 */
	protected ?string $InvoiceDate;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string|null $DueDate
	 */
	protected ?string $DueDate;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var float $DiscountPercent
	 */
	protected float $DiscountPercent = 0;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var float $Discount
	 */
	protected float $Discount = 0;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var float $TotalAmount
	 */
	protected float $TotalAmount = 0;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var float $TotalAmountWithTax
	 */
	protected float $TotalAmountWithTax = 0;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string|null $Currency
	 */
	protected ?string $Currency;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string|null $Reference
	 */
	protected ?string $Reference;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string|null $Voucher
	 */
	protected ?string $Voucher;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var int $SettledType
	 */
	protected int $SettledType = 0;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var float $SettledAmount
	 */
	protected float $SettledAmount = 0;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string|null $SalePerson
	 */
	protected ?string $SalePerson;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string|null $Text1
	 */
	protected ?string $Text1;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string|null $Text2
	 */
	protected ?string $Text2;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string|null $Dim1
	 */
	protected ?string $Dim1;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string|null $Dim2
	 */
	protected ?string $Dim2;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var int $Origin
	 */
	protected int $Origin = 0;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string|null $PaymentTerm
	 */
	protected ?string $PaymentTerm;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string|null $PaymentMode
	 */
	protected ?string $PaymentMode;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var int $ClaimStatus
	 */
	protected int $ClaimStatus = 0;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var float|int $Exchange
	 */
	protected float $Exchange = 0;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var int $SalesType
	 */
	protected int $SalesType = 0;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var int $Version
	 */
	protected int $Version = 0;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string|null $Project
	 */
	protected ?string $Project;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string|null $IRNumber
	 */
	protected ?string $IRNumber;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string|null $IRName
	 */
	protected ?string $IRName;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string|null $IRAddress1
	 */
	protected ?string $IRAddress1;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string|null $IRAddress2
	 */
	protected ?string $IRAddress2;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string|null $IRZipCode
	 */
	protected ?string $IRZipCode;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string|null $IRContact
	 */
	protected ?string $IRContact;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var int $ExternalInvoiceNumber
	 */
	protected int $ExternalInvoiceNumber = 0;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var int $ClaimNumber
	 */
	protected int $ClaimNumber = 0;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string|null $ClaimDate
	 */
	protected ?string $ClaimDate;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string|null $Register
	 */
	protected ?string $Register;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var int $PosInvoice
	 */
	protected int $PosInvoice = 0;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var int $JournalId
	 */
	protected int $JournalId = 0;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var array|null $Lines
	 */
	protected ?array $Lines;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param stdClass $Invoice
	 * @return void
	 */
	public function createInvoiceFromDKData( stdClass $Invoice ) :void {
		$this->setNumber( $Invoice->Number ?? null );
		$this->setObjectDate( $Invoice->ObjectDate ?? null );
		$this->setCreated( $Invoice->Created ?? null );
		$this->setModified( $Invoice->Modified ?? null );
		$this->setCreatedBy( $Invoice->CreatedBy ?? null );
		$this->setCNumber( $Invoice->CNumber ?? null );
		$this->setCName( $Invoice->CNumber ?? null );
		$this->setCAddress1( $Invoice->CAddress1 ?? null );
		$this->setCAddress2( $Invoice->CAddress2 ?? null );
		$this->setCAddress3( $Invoice->CAddress3 ?? null );
		$this->setCAddress4( $Invoice->CAddress4 ?? null );
		$this->setCZipCode( $Invoice->CZipCode ?? null );
		$this->setCCountryCode( $Invoice->CCountryCode ?? null );
		$this->setCSSNumber( $Invoice->CSSNumber ?? null );
		$this->setCPhone( $Invoice->CPhone ?? null );
		$this->setCContact( $Invoice->CContact ?? null );
		$this->setRecordID( $Invoice->RecordID ?? null );
		$this->setOrderNumber( $Invoice->OrderNumber ?? 0 );
		$this->setInvoiceDate( $Invoice->InvoiceDate ?? null );
		$this->setDueDate( $Invoice->DueDate ?? null );
		$this->setDiscountPercent( $Invoice->DiscountPercent ?? 0 );
		$this->setDiscount( $Invoice->Discount ?? 0 );
		$this->setTotalAmount( $Invoice->TotalAmount ?? 0 );
		$this->setTotalAmountWithTax( $Invoice->TotalAmountWithTax ?? 0 );
		$this->setCurrency( $Invoice->Currency ?? null );
		$this->setReference( $Invoice->Reference ?? null );
		$this->setVoucher( $Invoice->Voucher ?? null );
		$this->setSettledType( $Invoice->SettledType = 0 );
		$this->setSettledAmount( $Invoice->SettledAmount = 0 );
		$this->setSalePerson( $Invoice->SalePerson ?? null );
		$this->setText1( $Invoice->Text1 ?? null );
		$this->setText2( $Invoice->Text2 ?? null );
		$this->setDim1( $Invoice->Dim1 ?? null );
		$this->setDim2( $Invoice->Dim2 ?? null );
		$this->setOrigin( $Invoice->Origin ?? 0 );
		$this->setPaymentTerm( $Invoice->PaymentTerm ?? null );
		$this->setPaymentMode( $Invoice->PaymentMode ?? null );
		$this->setClaimStatus( $Invoice->ClaimStatus ?? 0 );
		$this->setExchange( $Invoice->Exchange ?? 0 );
		$this->setSalesType( $Invoice->SalesType ?? 0 );
		$this->setVersion( $Invoice->Version ?? 0 );
		$this->setProject( $Invoice->Project ?? null );
		$this->setIRNumber( $Invoice->IRNumber ?? null );
		$this->setIRName( $Invoice->IRName ?? null );
		$this->setIRAddress1( $Invoice->IRAddress1 ?? null );
		$this->setIRAddress2( $Invoice->IRAddress2 ?? null );
		$this->setIRZipCode( $Invoice->IRZipCode ?? null );
		$this->setIRContact( $Invoice->IRContact ?? null );
		$this->setExternalInvoiceNumber( $Invoice->ExternalInvoiceNumber ?? 0 );
		$this->setClaimNumber( $Invoice->ClaimNumber ?? 0 );
		$this->setClaimDate( $Invoice->ClaimDate ?? null );
		$this->setRegister( $Invoice->Register ?? null );
		$this->setPosInvoice( $Invoice->PosInvoice ?? 0 );
		$this->setJournalId( $Invoice->JournalId ?? 0 );
		$this->setLines( $Invoice->Lines ?? [] );
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return string|null
	 */
	public function getNumber() :?string {
		return $this->Number;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param string|null $Number
	 * @return $this
	 */
	public function setNumber( ?string $Number ) :Invoice {
		$this->Number = $Number;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return string|null
	 */
	public function getObjectDate() :?string {
		return $this->ObjectDate;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param string|null $ObjectDate
	 * @return $this
	 */
	public function setObjectDate( ?string $ObjectDate ) :Invoice {
		$this->ObjectDate = $ObjectDate;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return string|null
	 */
	public function getCreated() :?string {
		return $this->Created;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param string|null $Created
	 * @return $this
	 */
	public function setCreated( ?string $Created ) :Invoice {
		$this->Created = $Created;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return string|null
	 */
	public function getModified() :?string {
		return $this->Modified;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param string|null $Modified
	 * @return $this
	 */
	public function setModified( ?string $Modified ) :Invoice {
		$this->Modified = $Modified;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return string|null
	 */
	public function getCreatedBy() :?string {
		return $this->CreatedBy;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param string|null $CreatedBy
	 * @return $this
	 */
	public function setCreatedBy( ?string $CreatedBy ) :Invoice {
		$this->CreatedBy = $CreatedBy;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return string|null
	 */
	public function getCNumber() :?string {
		return $this->CNumber;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param string|null $CNumber
	 * @return $this
	 */
	public function setCNumber( ?string $CNumber ) :Invoice {
		$this->CNumber = $CNumber;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return string|null
	 */
	public function getCName() :?string {
		return $this->CName;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param string|null $CName
	 * @return $this
	 */
	public function setCName( ?string $CName ) :Invoice {
		$this->CName = $CName;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return string|null
	 */
	public function getCAddress1() :?string {
		return $this->CAddress1;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param string|null $CAddress1
	 * @return $this
	 */
	public function setCAddress1( ?string $CAddress1 ) :Invoice {
		$this->CAddress1 = $CAddress1;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return string|null
	 */
	public function getCAddress2() :?string {
		return $this->CAddress2;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param string|null $CAddress2
	 * @return $this
	 */
	public function setCAddress2( ?string $CAddress2 ) :Invoice {
		$this->CAddress2 = $CAddress2;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return string|null
	 */
	public function getCAddress3() :?string {
		return $this->CAddress3;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param string|null $CAddress3
	 * @return $this
	 */
	public function setCAddress3( ?string $CAddress3 ) :Invoice {
		$this->CAddress3 = $CAddress3;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return string|null
	 */
	public function getCAddress4() :?string {
		return $this->CAddress4;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param string|null $CAddress4
	 * @return $this
	 */
	public function setCAddress4( ?string $CAddress4 ) :Invoice {
		$this->CAddress4 = $CAddress4;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return string|null
	 */
	public function getCZipCode() :?string {
		return $this->CZipCode;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param string|null $CZipCode
	 * @return $this
	 */
	public function setCZipCode( ?string $CZipCode ) :Invoice {
		$this->CZipCode = $CZipCode;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return string|null
	 */
	public function getCCountryCode() :?string {
		return $this->CCountryCode;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param string|null $CCountryCode
	 * @return $this
	 */
	public function setCCountryCode( ?string $CCountryCode ) :Invoice {
		$this->CCountryCode = $CCountryCode;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return string|null
	 */
	public function getCSSNumber() :?string {
		return $this->CSSNumber;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param string|null $CSSNumber
	 * @return $this
	 */
	public function setCSSNumber( ?string $CSSNumber ) :Invoice {
		$this->CSSNumber = $CSSNumber;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return string|null
	 */
	public function getCPhone() :?string {
		return $this->CPhone;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param string|null $CPhone
	 * @return $this
	 */
	public function setCPhone( ?string $CPhone ) :Invoice {
		$this->CPhone = $CPhone;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return string|null
	 */
	public function getCContact() :?string {
		return $this->CContact;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param string|null $CContact
	 * @return $this
	 */
	public function setCContact( ?string $CContact ) :Invoice {
		$this->CContact = $CContact;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return int|null
	 */
	public function getRecordID() :?int {
		return $this->RecordID;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param int|null $RecordID
	 * @return $this
	 */
	public function setRecordID( ?int $RecordID ) :Invoice {
		$this->RecordID = $RecordID;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return int
	 */
	public function getOrderNumber() :int {
		return $this->OrderNumber;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param int $OrderNumber
	 * @return $this
	 */
	public function setOrderNumber( int $OrderNumber ) :Invoice {
		$this->OrderNumber = $OrderNumber;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return string|null
	 */
	public function getInvoiceDate() :?string {
		return $this->InvoiceDate;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param string|null $InvoiceDate
	 * @return $this
	 */
	public function setInvoiceDate( ?string $InvoiceDate ) :Invoice {
		$this->InvoiceDate = $InvoiceDate;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return string|null
	 */
	public function getDueDate() :?string {
		return $this->DueDate;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param string|null $DueDate
	 * @return $this
	 */
	public function setDueDate( ?string $DueDate ) :Invoice {
		$this->DueDate = $DueDate;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return float
	 */
	public function getDiscountPercent() :float {
		return $this->DiscountPercent;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param float $DiscountPercent
	 * @return $this
	 */
	public function setDiscountPercent( float $DiscountPercent ) :Invoice {
		$this->DiscountPercent = $DiscountPercent;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return float
	 */
	public function getDiscount() :float {
		return $this->Discount;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param float $Discount
	 * @return $this
	 */
	public function setDiscount( float $Discount ) :Invoice {
		$this->Discount = $Discount;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return float
	 */
	public function getTotalAmount() :float {
		return $this->TotalAmount;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param float $TotalAmount
	 * @return $this
	 */
	public function setTotalAmount( float $TotalAmount ) :Invoice {
		$this->TotalAmount = $TotalAmount;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return float
	 */
	public function getTotalAmountWithTax() :float {
		return $this->TotalAmountWithTax;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param float $TotalAmountWithTax
	 * @return $this
	 */
	public function setTotalAmountWithTax( float $TotalAmountWithTax ) :Invoice {
		$this->TotalAmountWithTax = $TotalAmountWithTax;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return string|null
	 */
	public function getCurrency() :?string {
		return $this->Currency;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param string|null $Currency
	 * @return $this
	 */
	public function setCurrency( ?string $Currency ) :Invoice {
		$this->Currency = $Currency;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return string|null
	 */
	public function getReference() :?string {
		return $this->Reference;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param string|null $Reference
	 * @return $this
	 */
	public function setReference( ?string $Reference ) :Invoice {
		$this->Reference = $Reference;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return string|null
	 */
	public function getVoucher() :?string {
		return $this->Voucher;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param string|null $Voucher
	 * @return $this
	 */
	public function setVoucher( ?string $Voucher ) :Invoice {
		$this->Voucher = $Voucher;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return int
	 */
	public function getSettledType() :int {
		return $this->SettledType;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param int $SettledType
	 * @return $this
	 */
	public function setSettledType( int $SettledType ) :Invoice {
		$this->SettledType = $SettledType;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return float
	 */
	public function getSettledAmount() :float {
		return $this->SettledAmount;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param float $SettledAmount
	 * @return $this
	 */
	public function setSettledAmount( float $SettledAmount ) :Invoice {
		$this->SettledAmount = $SettledAmount;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return string|null
	 */
	public function getSalePerson() :?string {
		return $this->SalePerson;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param string|null $SalePerson
	 * @return $this
	 */
	public function setSalePerson( ?string $SalePerson ) :Invoice {
		$this->SalePerson = $SalePerson;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return string|null
	 */
	public function getText1() :?string {
		return $this->Text1;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param string|null $Text1
	 * @return $this
	 */
	public function setText1( ?string $Text1 ) :Invoice {
		$this->Text1 = $Text1;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return string|null
	 */
	public function getText2() :?string {
		return $this->Text2;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param string|null $Text2
	 * @return $this
	 */
	public function setText2( ?string $Text2 ) :Invoice {
		$this->Text2 = $Text2;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return string|null
	 */
	public function getDim1() :?string {
		return $this->Dim1;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param string|null $Dim1
	 * @return $this
	 */
	public function setDim1( ?string $Dim1 ) :Invoice {
		$this->Dim1 = $Dim1;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return string|null
	 */
	public function getDim2() :?string {
		return $this->Dim2;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param string|null $Dim2
	 * @return $this
	 */
	public function setDim2( ?string $Dim2 ) :Invoice {
		$this->Dim2 = $Dim2;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return int
	 */
	public function getOrigin() :int {
		return $this->Origin;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param int $Origin
	 * @return $this
	 */
	public function setOrigin( int $Origin ) :Invoice {
		$this->Origin = $Origin;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return string|null
	 */
	public function getPaymentTerm() :?string {
		return $this->PaymentTerm;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param string|null $PaymentTerm
	 * @return $this
	 */
	public function setPaymentTerm( ?string $PaymentTerm ) :Invoice {
		$this->PaymentTerm = $PaymentTerm;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return string|null
	 */
	public function getPaymentMode() :?string {
		return $this->PaymentMode;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param string|null $PaymentMode
	 * @return $this
	 */
	public function setPaymentMode( ?string $PaymentMode ) :Invoice {
		$this->PaymentMode = $PaymentMode;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return int
	 */
	public function getClaimStatus() :int {
		return $this->ClaimStatus;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param int $ClaimStatus
	 * @return $this
	 */
	public function setClaimStatus( int $ClaimStatus ) :Invoice {
		$this->ClaimStatus = $ClaimStatus;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return float
	 */
	public function getExchange() :float {
		return $this->Exchange;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param float $Exchange
	 * @return $this
	 */
	public function setExchange( float $Exchange ) :Invoice {
		$this->Exchange = $Exchange;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return int
	 */
	public function getSalesType() :int {
		return $this->SalesType;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param int $SalesType
	 * @return $this
	 */
	public function setSalesType( int $SalesType ) :Invoice {
		$this->SalesType = $SalesType;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return int
	 */
	public function getVersion() :int {
		return $this->Version;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param int $Version
	 * @return $this
	 */
	public function setVersion( int $Version ) :Invoice {
		$this->Version = $Version;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return string|null
	 */
	public function getProject() :?string {
		return $this->Project;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param string|null $Project
	 * @return $this
	 */
	public function setProject( ?string $Project ) :Invoice {
		$this->Project = $Project;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return string|null
	 */
	public function getIRNumber() :?string {
		return $this->IRNumber;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param string|null $IRNumber
	 * @return $this
	 */
	public function setIRNumber( ?string $IRNumber ) :Invoice {
		$this->IRNumber = $IRNumber;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return string|null
	 */
	public function getIRName() :?string {
		return $this->IRName;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param string|null $IRName
	 * @return $this
	 */
	public function setIRName( ?string $IRName ) :Invoice {
		$this->IRName = $IRName;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return string|null
	 */
	public function getIRAddress1() :?string {
		return $this->IRAddress1;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param string|null $IRAddress1
	 * @return $this
	 */
	public function setIRAddress1( ?string $IRAddress1 ) :Invoice {
		$this->IRAddress1 = $IRAddress1;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return string|null
	 */
	public function getIRAddress2() :?string {
		return $this->IRAddress2;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param string|null $IRAddress2
	 * @return $this
	 */
	public function setIRAddress2( ?string $IRAddress2 ) :Invoice {
		$this->IRAddress2 = $IRAddress2;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return string|null
	 */
	public function getIRZipCode() :?string {
		return $this->IRZipCode;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param string|null $IRZipCode
	 * @return $this
	 */
	public function setIRZipCode( ?string $IRZipCode ) :Invoice {
		$this->IRZipCode = $IRZipCode;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return string|null
	 */
	public function getIRContact() :?string {
		return $this->IRContact;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param string|null $IRContact
	 * @return $this
	 */
	public function setIRContact( ?string $IRContact ) :Invoice {
		$this->IRContact = $IRContact;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return int
	 */
	public function getExternalInvoiceNumber() :int {
		return $this->ExternalInvoiceNumber;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param int $ExternalInvoiceNumber
	 * @return $this
	 */
	public function setExternalInvoiceNumber( int $ExternalInvoiceNumber ) :Invoice {
		$this->ExternalInvoiceNumber = $ExternalInvoiceNumber;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return int
	 */
	public function getClaimNumber() :int {
		return $this->ClaimNumber;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param int $ClaimNumber
	 * @return $this
	 */
	public function setClaimNumber( int $ClaimNumber ) :Invoice {
		$this->ClaimNumber = $ClaimNumber;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return string|null
	 */
	public function getClaimDate() :?string {
		return $this->ClaimDate;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param string|null $ClaimDate
	 * @return $this
	 */
	public function setClaimDate( ?string $ClaimDate ) :Invoice {
		$this->ClaimDate = $ClaimDate;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return string|null
	 */
	public function getRegister() :?string {
		return $this->Register;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param string|null $Register
	 * @return $this
	 */
	public function setRegister( ?string $Register ) :Invoice {
		$this->Register = $Register;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return int
	 */
	public function getPosInvoice() :int {
		return $this->PosInvoice;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param int $PosInvoice
	 * @return $this
	 */
	public function setPosInvoice( int $PosInvoice ) :Invoice {
		$this->PosInvoice = $PosInvoice;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return int
	 */
	public function getJournalId() :int {
		return $this->JournalId;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param int $JournalId
	 * @return $this
	 */
	public function setJournalId( int $JournalId ) :Invoice {
		$this->JournalId = $JournalId;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return array|null
	 */
	public function getLines() :?array {
		return $this->Lines;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param array|null $Lines
	 * @return $this
	 */
	public function setLines( ?array $Lines ) :Invoice {
		if ( ! is_null( $Lines ) ) {
			$this->Lines = [];
			return $this;
		} elseif ( is_array( $Lines ) ) {
			$return_arr = [];
			foreach ( $Lines as $Line ) {
				$line_object = new OrderLine();
				$line_object->createOrderLineFromDKData( $Line );
				$return_arr[] = $line_object;
			}

			$this->Lines = $return_arr;
		}

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
