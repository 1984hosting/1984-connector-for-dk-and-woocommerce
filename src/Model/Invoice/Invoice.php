<?php

declare(strict_types = 1);

namespace NineteenEightyFour\NineteenEightyWoo\Model\Invoice;

use JsonSerializable;
use Model\Order\OrderLine;
use stdClass;

class Invoice implements JsonSerializable {
	protected ?string $Number;

	protected ?string $ObjectDate;

	protected ?string $Created;

	protected ?string $Modified;

	protected ?string $CreatedBy;

	protected ?string $CNumber;

	protected ?string $CName;

	protected ?string $CAddress1;

	protected ?string $CAddress2;

	protected ?string $CAddress3;

	protected ?string $CAddress4;

	protected ?string $CZipCode;

	protected ?string $CCountryCode;

	protected ?string $CSSNumber;

	protected ?string $CPhone;

	protected ?string $CContact;

	protected ?int $RecordID = 0;

	protected int $OrderNumber = 0;

	protected ?string $InvoiceDate;

	protected ?string $DueDate;

	protected float $DiscountPercent = 0;

	protected float $Discount = 0;

	protected float $TotalAmount = 0;

	protected float $TotalAmountWithTax = 0;

	protected ?string $Currency;

	protected ?string $Reference;

	protected ?string $Voucher;

	protected int $SettledType = 0;

	protected float $SettledAmount = 0;

	protected ?string $SalePerson;

	protected ?string $Text1;

	protected ?string $Text2;

	protected ?string $Dim1;

	protected ?string $Dim2;

	protected int $Origin = 0;

	protected ?string $PaymentTerm;

	protected ?string $PaymentMode;

	protected int $ClaimStatus = 0;

	protected float $Exchange = 0;

	protected int $SalesType = 0;

	protected int $Version = 0;

	protected ?string $Project;

	protected ?string $IRNumber;

	protected ?string $IRName;

	protected ?string $IRAddress1;

	protected ?string $IRAddress2;

	protected ?string $IRZipCode;

	protected ?string $IRContact;

	protected int $ExternalInvoiceNumber = 0;

	protected int $ClaimNumber = 0;

	protected ?string $ClaimDate;

	protected ?string $Register;

	protected int $PosInvoice = 0;

	protected int $JournalId = 0;

	protected ?array $Lines;

	public function createInvoiceFromDKData( stdClass $Invoice ): void {
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
		$this->setProject( $Invoice->Project ?? 0 );
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

	public function getNumber(): ?string {
		return $this->Number;
	}

	public function setNumber( ?string $Number ): Invoice {
		$this->Number = $Number;
		return $this;
	}

	public function getObjectDate(): ?string {
		return $this->ObjectDate;
	}

	public function setObjectDate( ?string $ObjectDate ): Invoice {
		$this->ObjectDate = $ObjectDate;
		return $this;
	}

	public function getCreated(): ?string {
		return $this->Created;
	}

	public function setCreated( ?string $Created ): Invoice {
		$this->Created = $Created;
		return $this;
	}

	public function getModified(): ?string {
		return $this->Modified;
	}

	public function setModified( ?string $Modified ): Invoice {
		$this->Modified = $Modified;
		return $this;
	}

	public function getCreatedBy(): ?string {
		return $this->CreatedBy;
	}

	public function setCreatedBy( ?string $CreatedBy ): Invoice {
		$this->CreatedBy = $CreatedBy;
		return $this;
	}

	public function getCNumber(): ?string {
		return $this->CNumber;
	}

	public function setCNumber( ?string $CNumber ): Invoice {
		$this->CNumber = $CNumber;
		return $this;
	}

	public function getCName(): ?string {
		return $this->CName;
	}

	public function setCName( ?string $CName ): Invoice {
		$this->CName = $CName;
		return $this;
	}

	public function getCAddress1(): ?string {
		return $this->CAddress1;
	}

	public function setCAddress1( ?string $CAddress1 ): Invoice {
		$this->CAddress1 = $CAddress1;
		return $this;
	}

	public function getCAddress2(): ?string {
		return $this->CAddress2;
	}

	public function setCAddress2( ?string $CAddress2 ): Invoice {
		$this->CAddress2 = $CAddress2;
		return $this;
	}

	public function getCAddress3(): ?string {
		return $this->CAddress3;
	}

	public function setCAddress3( ?string $CAddress3 ): Invoice {
		$this->CAddress3 = $CAddress3;
		return $this;
	}

	public function getCAddress4(): ?string {
		return $this->CAddress4;
	}

	public function setCAddress4( ?string $CAddress4 ): Invoice {
		$this->CAddress4 = $CAddress4;
		return $this;
	}

	public function getCZipCode(): ?string {
		return $this->CZipCode;
	}

	public function setCZipCode( ?string $CZipCode ): Invoice {
		$this->CZipCode = $CZipCode;
		return $this;
	}

	public function getCCountryCode(): ?string {
		return $this->CCountryCode;
	}

	public function setCCountryCode( ?string $CCountryCode ): Invoice {
		$this->CCountryCode = $CCountryCode;
		return $this;
	}

	public function getCSSNumber(): ?string {
		return $this->CSSNumber;
	}

	public function setCSSNumber( ?string $CSSNumber ): Invoice {
		$this->CSSNumber = $CSSNumber;
		return $this;
	}

	public function getCPhone(): ?string {
		return $this->CPhone;
	}

	public function setCPhone( ?string $CPhone ): Invoice {
		$this->CPhone = $CPhone;
		return $this;
	}

	public function getCContact(): ?string {
		return $this->CContact;
	}

	public function setCContact( ?string $CContact ): Invoice {
		$this->CContact = $CContact;
		return $this;
	}

	public function getRecordID(): ?int {
		return $this->RecordID;
	}

	public function setRecordID( ?int $RecordID ): Invoice {
		$this->RecordID = $RecordID;
		return $this;
	}

	public function getOrderNumber(): int {
		return $this->OrderNumber;
	}

	public function setOrderNumber( int $OrderNumber ): Invoice {
		$this->OrderNumber = $OrderNumber;
		return $this;
	}

	public function getInvoiceDate(): ?string {
		return $this->InvoiceDate;
	}

	public function setInvoiceDate( ?string $InvoiceDate ): Invoice {
		$this->InvoiceDate = $InvoiceDate;
		return $this;
	}

	public function getDueDate(): ?string {
		return $this->DueDate;
	}

	public function setDueDate( ?string $DueDate ): Invoice {
		$this->DueDate = $DueDate;
		return $this;
	}

	public function getDiscountPercent(): float {
		return $this->DiscountPercent;
	}

	public function setDiscountPercent( float $DiscountPercent ): Invoice {
		$this->DiscountPercent = $DiscountPercent;
		return $this;
	}



	public function getDiscount(): float {
		return $this->Discount;
	}

	public function setDiscount( float $Discount ): Invoice {
		$this->Discount = $Discount;
		return $this;
	}

	public function getTotalAmount(): float {
		return $this->TotalAmount;
	}

	public function setTotalAmount( float $TotalAmount ): Invoice {
		$this->TotalAmount = $TotalAmount;
		return $this;
	}

	public function getTotalAmountWithTax(): float {
		return $this->TotalAmountWithTax;
	}

	public function setTotalAmountWithTax( float $TotalAmountWithTax ): Invoice {
		$this->TotalAmountWithTax = $TotalAmountWithTax;
		return $this;
	}

	public function getCurrency(): ?string {
		return $this->Currency;
	}

	public function setCurrency( ?string $Currency ): Invoice {
		$this->Currency = $Currency;
		return $this;
	}

	public function getReference(): ?string {
		return $this->Reference;
	}

	public function setReference( ?string $Reference ): Invoice {
		$this->Reference = $Reference;
		return $this;
	}

	public function getVoucher(): ?string {
		return $this->Voucher;
	}

	public function setVoucher( ?string $Voucher ): Invoice {
		$this->Voucher = $Voucher;
		return $this;
	}

	public function getSettledType(): int {
		return $this->SettledType;
	}

	public function setSettledType( int $SettledType ): Invoice {
		$this->SettledType = $SettledType;
		return $this;
	}

	public function getSettledAmount(): float {
		return $this->SettledAmount;
	}

	public function setSettledAmount( float $SettledAmount ): Invoice {
		$this->SettledAmount = $SettledAmount;
		return $this;
	}

	public function getSalePerson(): ?string {
		return $this->SalePerson;
	}

	public function setSalePerson( ?string $SalePerson ): Invoice {
		$this->SalePerson = $SalePerson;
		return $this;
	}

	public function getText1(): ?string {
		return $this->Text1;
	}

	public function setText1( ?string $Text1 ): Invoice {
		$this->Text1 = $Text1;
		return $this;
	}

	public function getText2(): ?string {
		return $this->Text2;
	}

	public function setText2( ?string $Text2 ): Invoice {
		$this->Text2 = $Text2;
		return $this;
	}

	public function getDim1(): ?string {
		return $this->Dim1;
	}

	public function setDim1( ?string $Dim1 ): Invoice {
		$this->Dim1 = $Dim1;
		return $this;
	}

	public function getDim2(): ?string {
		return $this->Dim2;
	}

	public function setDim2( ?string $Dim2 ): Invoice {
		$this->Dim2 = $Dim2;
		return $this;
	}

	public function getOrigin(): int {
		return $this->Origin;
	}

	public function setOrigin( int $Origin ): Invoice {
		$this->Origin = $Origin;
		return $this;
	}

	public function getPaymentTerm(): ?string {
		return $this->PaymentTerm;
	}

	public function setPaymentTerm( ?string $PaymentTerm ): Invoice {
		$this->PaymentTerm = $PaymentTerm;
		return $this;
	}

	public function getPaymentMode(): ?string {
		return $this->PaymentMode;
	}

	public function setPaymentMode( ?string $PaymentMode ): Invoice {
		$this->PaymentMode = $PaymentMode;
		return $this;
	}

	public function getClaimStatus(): int {
		return $this->ClaimStatus;
	}

	public function setClaimStatus( int $ClaimStatus ): Invoice {
		$this->ClaimStatus = $ClaimStatus;
		return $this;
	}

	public function getExchange(): float {
		return $this->Exchange;
	}

	public function setExchange( float $Exchange ): Invoice {
		$this->Exchange = $Exchange;
		return $this;
	}

	public function getSalesType(): int {
		return $this->SalesType;
	}

	public function setSalesType( int $SalesType ): Invoice {
		$this->SalesType = $SalesType;
		return $this;
	}

	public function getVersion(): int {
		return $this->Version;
	}

	public function setVersion( int $Version ): Invoice {
		$this->Version = $Version;
		return $this;
	}

	public function getProject(): ?string {
		return $this->Project;
	}

	public function setProject( ?string $Project ): Invoice {
		$this->Project = $Project;
		return $this;
	}

	public function getIRNumber(): ?string {
		return $this->IRNumber;
	}

	public function setIRNumber( ?string $IRNumber ): Invoice {
		$this->IRNumber = $IRNumber;
		return $this;
	}

	public function getIRName(): ?string {
		return $this->IRName;
	}

	public function setIRName( ?string $IRName ): Invoice {
		$this->IRName = $IRName;
		return $this;
	}

	public function getIRAddress1(): ?string {
		return $this->IRAddress1;
	}

	public function setIRAddress1( ?string $IRAddress1 ): Invoice {
		$this->IRAddress1 = $IRAddress1;
		return $this;
	}

	public function getIRAddress2(): ?string {
		return $this->IRAddress2;
	}

	public function setIRAddress2( ?string $IRAddress2 ): Invoice {
		$this->IRAddress2 = $IRAddress2;
		return $this;
	}

	public function getIRZipCode(): ?string {
		return $this->IRZipCode;
	}

	public function setIRZipCode( ?string $IRZipCode ): Invoice {
		$this->IRZipCode = $IRZipCode;
		return $this;
	}

	public function getIRContact(): ?string {
		return $this->IRContact;
	}

	public function setIRContact( ?string $IRContact ): Invoice {
		$this->IRContact = $IRContact;
		return $this;
	}

	public function getExternalInvoiceNumber(): int {
		return $this->ExternalInvoiceNumber;
	}

	public function setExternalInvoiceNumber( int $ExternalInvoiceNumber ): Invoice {
		$this->ExternalInvoiceNumber = $ExternalInvoiceNumber;
		return $this;
	}

	public function getClaimNumber(): int {
		return $this->ClaimNumber;
	}

	public function setClaimNumber( int $ClaimNumber ): Invoice {
		$this->ClaimNumber = $ClaimNumber;
		return $this;
	}

	public function getClaimDate(): ?string {
		return $this->ClaimDate;
	}

	public function setClaimDate( ?string $ClaimDate ): Invoice {
		$this->ClaimDate = $ClaimDate;
		return $this;
	}

	public function getRegister(): ?string {
		return $this->Register;
	}

	public function setRegister( ?string $Register ): Invoice {
		$this->Register = $Register;
		return $this;
	}

	public function getPosInvoice(): int {
		return $this->PosInvoice;
	}

	public function setPosInvoice( int $PosInvoice ): Invoice {
		$this->PosInvoice = $PosInvoice;
		return $this;
	}

	public function getJournalId(): int {
		return $this->JournalId;
	}

	public function setJournalId( int $JournalId ): Invoice {
		$this->JournalId = $JournalId;
		return $this;
	}

	public function getLines(): ?array {
		return $this->Lines;
	}

	public function setLines( ?array $Lines ): Invoice {
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

	public function jsonSerialize(): string {
		return json_encode( get_object_vars( $this ) );
	}
}
