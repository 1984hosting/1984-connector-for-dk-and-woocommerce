<?php

declare(strict_types = 1);

namespace NineteenEightyFour\NineteenEightyWoo\Model\Customer;

use JsonSerializable;
use NineteenEightyFour\NineteenEightyWoo\Model\Customer\CustomerCondition;
use stdClass;

/**
 * ATM there are 7 variables that are described in the Dataset for Customer that are NOT in it
 * when a call to Customer is made. Test were made with the test dataset from the API (87000 records)
 * and on a live dataset with over 40.000 records. Therefor they are not implemented correctly. These
 * variables are:
 *
 * Deleted
 * Contacts
 * Recivers
 * Memos
 * Properties
 * Changes
 * Attachments
 *
 * Since the documentation for the API is not the best, I'm assuming this is because of lack of implementation,
 * changes in scope on the way or ... just pick one :-(
 *
 * I'm leaving the variables in for now, but feel free to remove them or disable them, if necessary.
 *
 * @author Hilmar Kári Hallbjörnsson (hilmar@umadgera.is)
 * @date 2024-03-05
 */
class Customer implements JsonSerializable {
	protected bool $Deleted;

	protected array $Contacts = [];

	protected array $Recivers = [];

	protected array $Memos = [];

	protected array $Properties = [];

	protected array $Changes = [];

	protected array $Attachments = [];

	protected CustomerCondition|null $Conditions;

	protected CustomerSendTo|null $SendTo;

	protected UBL|null $UBL;

	protected int|null $RecordID;

	protected string|null $Number;

	protected string|null $Name;

	protected string|null $SSNumber;

	protected string|null $Alias;

	protected string|null $Address1;

	protected string|null $Address2;

	protected string|null $Address3;

	protected string|null $City;

	protected string|null $ZipCode;

	protected float $BalanceAmount = 0;

	protected string|null $Phone;

	protected string|null $PhoneLocal;

	protected string|null $PhoneMobile;

	protected string|null $PhoneFax;

	protected string|null $CountryCode;

	protected string|null $OriginCountryCode;

	protected string|null $Email;

	protected string|null $Password;

	protected string|null $Group;

	protected string|null $SalesPerson;

	protected float $Discount = 0;

	protected bool|null $UseItemRecivers;

	protected string|null $PaymentTerm;

	protected string|null $PaymentMode;

	protected string|null $CurrencyCode;

	protected bool $NoVat = false;

	protected string|null $LedgerCode;

	protected bool $Blocked = false;

	protected string $Gender;

	protected int $PriceGroup = 0;

	protected float $BillingFee = 0;

	protected string|null $Modified;

	protected string|null $ObjectDate;

	protected string|null $VATNumber;

	protected string|null $ISATNumber;

	public function createCustomerFromDKData( stdClass $customer ): void {
		if ( isset( $customer->Contacts ) ) {
			if ( is_array( $customer->Contacts ) && count( $customer->Contacts ) > 0 ) {
				$this->setContacts( $customer->Contacts );
			} elseif ( is_array( $customer->Contacts ) && count( $customer->Contacts ) === 0 ) {
				$this->setContacts( [] );
			}
		} else {
			$this->setContacts( [] );
		}
		if ( isset( $customer->Recivers ) ) {
			if ( is_array( $customer->Recivers ) && count( $customer->Recivers ) > 0 ) {
				$this->setRecivers( $customer->Recivers );
			} elseif ( is_array( $customer->Recivers ) && count( $customer->Recivers ) === 0 ) {
				$this->setRecivers( [] );
			}
		} else {
			$this->setRecivers( [] );
		}
		if ( isset( $customer->Memos ) ) {
			if ( is_array( $customer->Memos ) && count( $customer->Memos ) > 0 ) {
				$this->setMemos( $customer->Memos );
			} elseif ( is_array( $customer->Memos ) && count( $customer->Memos ) === 0 ) {
				$this->setMemos( [] );
			}
		} else {
			$this->setMemos( [] );
		}
		if ( isset( $customer->Properties ) ) {
			if ( is_array( $customer->Properties ) && count( $customer->Properties ) > 0 ) {
				$this->setProperties( $customer->Properties );
			} elseif ( is_array( $customer->Properties ) && count( $customer->Properties ) === 0 ) {
				$this->setProperties( [] );
			}
		} else {
			$this->setProperties( [] );
		}
		if ( isset( $customer->Changes ) ) {
			if ( is_array( $customer->Changes ) && count( $customer->Changes ) > 0 ) {
				$this->setChanges( $customer->Changes );
			} elseif ( is_array( $customer->Changes ) && count( $customer->Changes ) === 0 ) {
				$this->setChanges( [] );
			}
		} else {
			$this->setChanges( [] );
		}
		if ( isset( $customer->Attachments ) ) {
			if ( is_array( $customer->Attachments ) && count( $customer->Attachments ) > 0 ) {
				$this->setAttachments( $customer->Attachments );
			} elseif ( is_array( $customer->Attachments ) && count( $customer->Attachments ) === 0 ) {
				$this->setAttachments( [] );
			}
		} else {
			$this->setAttachments( [] );
		}
		$condition = new CustomerCondition();
		$condition->createCustomerConditionFromDKData( $customer->Conditions );
		$this->setConditions( $condition );
		$sendTo = new CustomerSendTo();
		$sendTo->createSendToFromDKData( $customer->SendTo );
		$this->setSendTo( $sendTo );
		$ubl = new UBL();
		$ubl->createUBLFromDKData( $customer->UBL );
		$this->setUBL( $ubl );
		$this->setRecordID( $customer->RecordID );
		$this->setNumber( $customer->Number );
		$this->setName( $customer->Name );
		$this->setSSNumber( $customer->SSNumber ?? null );
		$this->setBalanceAmount( $customer->BalanceAmount ?? 0 );
		$this->setCountryCode( $customer->CountryCode ?? 'IS' );
		$this->setGroup( $customer->Group ?? null );
		$this->setSalesPerson( $customer->SalesPerson ?? null );
		$this->setDiscount( $customer->Discount ?? 0 );
		$this->setUseItemRecivers( $customer->UseItemRecivers ?? false );
		$this->setPaymentTerm( $customer->PaymentTerm ?? null );
		$this->setPaymentMode( $customer->PaymentMode ?? null );
		$this->setNoVat( $customer->NoVat ?? false );
		$this->setLedgerCode( $customer->LedgerCode ?? null );
		$this->setBlocked( $customer->Blocked ?? false );
		$this->setGender( $customer->Gender ?? null );
		$this->setPriceGroup( $customer->PriceGroup ?? 0 );
		$this->setBillingFee( $customer->BillingFee ?? 0 );
		$this->setModified( $customer->Modified ?? null );
		$this->setObjectDate( $customer->ObjectDate ?? null );
		$this->setAlias( $customer->Alias ?? null );
		$this->setAddress1( $customer->Address1 ?? null );
		$this->setAddress2( $customer->Address2 ?? null );
		$this->setAddress3( $customer->Address3 ?? null );
		$this->setCity( $customer->City ?? null );
		$this->setZipCode( $customer->ZipCode ?? null );
		$this->setPhone( $customer->Phone ?? null );
		$this->setPhoneLocal( $customer->PhoneLocal ?? null );
		$this->setPhoneMobile( $customer->PhoneMobile ?? null );
		$this->setPhoneFax( $customer->PhoneFax ?? null );
		$this->setOriginCountryCode( $customer->OriginCountryCode ?? null );
		$this->setEmail( $customer->Email ?? null );
		$this->setPassword( $customer->Password ?? null );
		$this->setCurrencyCode( $customer->CurrencyCode ?? null );
		$this->setVATNumber( $customer->VATNumber ?? null );
		$this->setISATNumber( $customer->ISATNumber ?? null );
	}

	public function isDeleted(): bool {
		return $this->Deleted;
	}

	public function setDeleted( bool $Deleted ): Customer {
		$this->Deleted = $Deleted;
		return $this;
	}

	public function getContacts(): array {
		return $this->Contacts;
	}

	public function setContacts( array $Contacts ): Customer {
		if ( count( $Contacts ) > 0 ) {
			$new_array = [];
			foreach ( $Contacts as $contact ) {
				$type = gettype( $contact );
				if ( ! is_a( $type, 'ContactModel' ) ) {
					$contact_model = new ContactModel();
					// @TODO: Implement this
				}
			}
		}
		$this->Contacts = $Contacts;
		return $this;
	}

	public function getRecivers(): array {
		return $this->Recivers;
	}

	public function setRecivers( array $Recivers ): Customer {
		$arr = [];
		foreach ( $Recivers as $reciver ) {
			$ItemReciver = new ItemReciverModel();
			$ItemReciver->createItemReciverModelFromDKData( $reciver );
			$arr[] = $ItemReciver;
		}
		$this->Recivers = $arr;
		return $this;
	}

	public function getMemos(): array {
		return $this->Memos;
	}

	public function setMemos( array $Memos ): Customer {
		$this->Memos = $Memos;
		return $this;
	}

	public function getProperties(): array {
		return $this->Properties;
	}

	public function setProperties( array $Properties ): Customer {
		$this->Properties = $Properties;
		return $this;
	}

	public function getChanges(): array {
		return $this->Changes;
	}

	public function setChanges( array $Changes ): Customer {
		$this->Changes = $Changes;
		return $this;
	}

	public function getAttachments(): array {
		return $this->Attachments;
	}

	public function setAttachments( array $Attachments ): Customer {
		$this->Attachments = $Attachments;
		return $this;
	}

	public function getConditions(): CustomerCondition {
		return $this->Conditions;
	}

	public function setConditions( CustomerCondition $Conditions ): Customer {
		$this->Conditions = $Conditions;
		return $this;
	}

	public function getSendTo(): CustomerSendTo {
		return $this->SendTo;
	}

	public function setSendTo( CustomerSendTo $SendTo ): Customer {
		$this->SendTo = $SendTo;
		return $this;
	}

	public function getUBL(): UBL {
		return $this->UBL;
	}

	public function setUBL( UBL $UBL ): Customer {
		$this->UBL = $UBL;
		return $this;
	}

	public function getRecordID(): int {
		return $this->RecordID;
	}

	public function setRecordID( int $RecordID ): Customer {
		$this->RecordID = $RecordID;
		return $this;
	}

	public function getNumber(): string {
		return $this->Number;
	}

	public function setNumber( string $Number ): Customer {
		$this->Number = $Number;
		return $this;
	}

	public function getName(): string|null {
		return $this->Name;
	}

	public function setName( string|null $Name ): Customer {
		$this->Name = $Name;
		return $this;
	}

	public function getSSNumber(): string|null {
		return $this->SSNumber;
	}

	public function setSSNumber( string|null $SSNumber ): Customer {
		$this->SSNumber = $SSNumber;
		return $this;
	}

	public function getAlias(): string|null {
		return $this->Alias;
	}

	public function setAlias( string|null $Alias ): Customer {
		$this->Alias = $Alias;
		return $this;
	}

	public function getAddress1(): string|null {
		return $this->Address1;
	}

	public function setAddress1( string|null $Address1 ): Customer {
		$this->Address1 = $Address1;
		return $this;
	}

	public function getAddress2(): string|null {
		return $this->Address2;
	}

	public function setAddress2( string|null $Address2 ): Customer {
		$this->Address2 = $Address2;
		return $this;
	}

	public function getAddress3(): string|null {
		return $this->Address3;
	}

	public function setAddress3( string|null $Address3 ): Customer {
		$this->Address3 = $Address3;
		return $this;
	}

	public function getCity(): string|null {
		return $this->City;
	}

	public function setCity( string|null $City ): Customer {
		$this->City = $City;
		return $this;
	}

	public function getZipCode(): string|null {
		return $this->ZipCode;
	}

	public function setZipCode( string|null $ZipCode ): Customer {
		$this->ZipCode = $ZipCode;
		return $this;
	}

	public function getBalanceAmount(): float {
		return $this->BalanceAmount;
	}

	public function setBalanceAmount( float $BalanceAmount ): Customer {
		$this->BalanceAmount = $BalanceAmount;
		return $this;
	}

	public function getPhone(): string|null {
		return $this->Phone;
	}

	public function setPhone( string|null $Phone ): Customer {
		$this->Phone = $Phone;
		return $this;
	}

	public function getPhoneLocal(): string|null {
		return $this->PhoneLocal;
	}

	public function setPhoneLocal( string|null $PhoneLocal ): Customer {
		$this->PhoneLocal = $PhoneLocal;
		return $this;
	}

	public function getPhoneMobile(): string|null {
		return $this->PhoneMobile;
	}

	public function setPhoneMobile( string|null $PhoneMobile ): Customer {
		$this->PhoneMobile = $PhoneMobile;
		return $this;
	}

	public function getPhoneFax(): string|null {
		return $this->PhoneFax;
	}

	public function setPhoneFax( string|null $PhoneFax ): Customer {
		$this->PhoneFax = $PhoneFax;
		return $this;
	}

	public function getCountryCode(): string|null {
		return $this->CountryCode;
	}

	public function setCountryCode( string|null $CountryCode ): Customer {
		$this->CountryCode = $CountryCode;
		return $this;
	}

	public function getOriginCountryCode(): string|null {
		return $this->OriginCountryCode;
	}

	public function setOriginCountryCode( string|null $OriginCountryCode ): Customer {
		$this->OriginCountryCode = $OriginCountryCode;
		return $this;
	}

	public function getEmail(): string|null {
		return $this->Email;
	}

	public function setEmail( string|null $Email ): Customer {
		$this->Email = $Email;
		return $this;
	}

	public function getPassword(): string|null {
		return $this->Password;
	}

	public function setPassword( string|null $Password ): Customer {
		$this->Password = $Password;
		return $this;
	}

	public function getGroup(): string|null {
		return $this->Group;
	}

	public function setGroup( string|null $Group ): Customer {
		$this->Group = $Group;
		return $this;
	}

	public function getSalesPerson(): string|null {
		return $this->SalesPerson;
	}

	public function setSalesPerson( string|null $SalesPerson ): Customer {
		$this->SalesPerson = $SalesPerson;
		return $this;
	}

	public function getDiscount(): float {
		return $this->Discount;
	}

	public function setDiscount( float $Discount ): Customer {
		$this->Discount = $Discount;
		return $this;
	}

	public function isUseItemRecivers(): bool {
		return $this->UseItemRecivers;
	}

	public function setUseItemRecivers( bool $UseItemRecivers ): Customer {
		$this->UseItemRecivers = $UseItemRecivers;
		return $this;
	}

	public function getPaymentTerm(): string|null {
		return $this->PaymentTerm;
	}

	public function setPaymentTerm( string|null $PaymentTerm ): Customer {
		$this->PaymentTerm = $PaymentTerm;
		return $this;
	}

	public function getPaymentMode(): string|null {
		return $this->PaymentMode;
	}

	public function setPaymentMode( string|null $PaymentMode ): Customer {
		$this->PaymentMode = $PaymentMode;
		return $this;
	}

	public function getCurrencyCode(): string|null {
		return $this->CurrencyCode;
	}

	public function setCurrencyCode( string|null $CurrencyCode ): Customer {
		$this->CurrencyCode = $CurrencyCode;
		return $this;
	}

	public function isNoVat(): bool {
		return $this->NoVat;
	}

	public function setNoVat( bool $NoVat ): Customer {
		$this->NoVat = $NoVat;
		return $this;
	}

	public function getLedgerCode(): string|null {
		return $this->LedgerCode;
	}

	public function setLedgerCode( string|null $LedgerCode ): Customer {
		$this->LedgerCode = $LedgerCode;
		return $this;
	}

	public function isBlocked(): bool {
		return $this->Blocked;
	}

	public function setBlocked( bool $Blocked ): Customer {
		$this->Blocked = $Blocked;
		return $this;
	}

	public function getGender(): string|null {
		return $this->Gender;
	}

	public function setGender( string|null $Gender ): Customer {
		$this->Gender = $Gender;
		return $this;
	}

	public function getPriceGroup(): int {
		return $this->PriceGroup;
	}

	public function setPriceGroup( int $PriceGroup ): Customer {
		$this->PriceGroup = $PriceGroup;
		return $this;
	}

	public function getBillingFee(): float {
		return $this->BillingFee;
	}

	public function setBillingFee( float $BillingFee ): Customer {
		$this->BillingFee = $BillingFee;
		return $this;
	}

	public function getModified(): string|null {
		return $this->Modified;
	}

	public function setModified( string|null $Modified ): Customer {
		$this->Modified = $Modified;
		return $this;
	}

	public function getObjectDate(): string|null {
		return $this->ObjectDate;
	}

	public function setObjectDate( string|null $ObjectDate ): Customer {
		$this->ObjectDate = $ObjectDate;
		return $this;
	}

	public function getVATNumber(): string|null {
		return $this->VATNumber;
	}

	public function setVATNumber( string|null $VATNumber ): Customer {
		$this->VATNumber = $VATNumber;
		return $this;
	}

	public function getISATNumber(): string|null {
		return $this->ISATNumber;
	}

	public function setISATNumber( string|null $ISATNumber ): Customer {
		$this->ISATNumber = $ISATNumber;
		return $this;
	}

	public function jsonSerialize(): string {
		return json_encode( get_object_vars( $this ) );
	}
}
