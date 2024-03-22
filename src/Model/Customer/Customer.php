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
	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var bool $Deleted
	 */
	protected bool $Deleted;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var array $Contacts
	 */
	protected array $Contacts = [];

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var array $Recivers
	 */
	protected array $Recivers = [];

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var array $Memos
	 */
	protected array $Memos = [];

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var array $Properties
	 */
	protected array $Properties = [];

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var array $Changes
	 */
	protected array $Changes = [];

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var array $Attachments
	 */
	protected array $Attachments = [];

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var CustomerCondition|null $Conditions
	 */
	protected CustomerCondition|null $Conditions;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var CustomerSendTo|null $SendTo
	 */
	protected CustomerSendTo|null $SendTo;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var UBL|null $UBL
	 */
	protected UBL|null $UBL;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var int|null $RecordID
	 */
	protected int|null $RecordID;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string|null $Number
	 */
	protected string|null $Number;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string|null $Name
	 */
	protected string|null $Name;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string|null $SSNumber
	 */
	protected string|null $SSNumber;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string|null $Alias
	 */
	protected string|null $Alias;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string|null $Address1
	 */
	protected string|null $Address1;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string|null $Address2
	 */
	protected string|null $Address2;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string|null $Address3
	 */
	protected string|null $Address3;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string|null $City
	 */
	protected string|null $City;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string|null $ZipCode
	 */
	protected string|null $ZipCode;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var float $BalanceAmount
	 */
	protected float $BalanceAmount = 0;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string|null $Phone
	 */
	protected string|null $Phone;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string|null $PhoneLocal
	 */
	protected string|null $PhoneLocal;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string|null $PhoneMobile
	 */
	protected string|null $PhoneMobile;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string|null $PhoneFax
	 */
	protected string|null $PhoneFax;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string|null $CountryCode
	 */
	protected string|null $CountryCode;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string|null $OriginCountryCode
	 */
	protected string|null $OriginCountryCode;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string|null $Email
	 */
	protected string|null $Email;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string|null $Password
	 */
	protected string|null $Password;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string|null $Group
	 */
	protected string|null $Group;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string|null $SalesPerson
	 */
	protected string|null $SalesPerson;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var float|int $Discount
	 */
	protected float $Discount = 0;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var bool|null $UseItemRecivers
	 */
	protected bool|null $UseItemRecivers;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string|null $PaymentTerm
	 */
	protected string|null $PaymentTerm;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string|null $PaymentMode
	 */
	protected string|null $PaymentMode;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string|null $CurrencyCode
	 */
	protected string|null $CurrencyCode;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var bool $NoVat
	 */
	protected bool $NoVat = false;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string|null $LedgerCode
	 */
	protected string|null $LedgerCode;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var bool $Blocked
	 */
	protected bool $Blocked = false;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string $Gender
	 */
	protected string $Gender;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var int $PriceGroup
	 */
	protected int $PriceGroup = 0;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var float $BillingFee
	 */
	protected float $BillingFee = 0;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string|null $Modified
	 */
	protected string|null $Modified;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string|null $ObjectDate
	 */
	protected string|null $ObjectDate;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string|null $VATNumber
	 */
	protected string|null $VATNumber;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string|null $ISATNumber
	 */
	protected string|null $ISATNumber;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param stdClass $customer
	 * @return void
	 */
	public function createCustomerFromDKData( stdClass $customer ) :void {
		if ( isset( $customer->Contacts ) ) {
			if ( is_array( $customer->Contacts ) && sizeof( $customer->Contacts ) > 0 ) {
				$this->setContacts( $customer->Contacts );
			} elseif ( is_array( $customer->Contacts ) && sizeof( $customer->Contacts ) === 0 ) {
				$this->setContacts( [] );
			}
		} else {
			$this->setContacts( [] );
		}
		if ( isset( $customer->Recivers ) ) {
			if ( is_array( $customer->Recivers ) && sizeof( $customer->Recivers ) > 0 ) {
				$this->setRecivers( $customer->Recivers );
			} elseif ( is_array( $customer->Recivers ) && sizeof( $customer->Recivers ) === 0 ) {
				$this->setRecivers( [] );
			}
		} else {
			$this->setRecivers( [] );
		}
		if ( isset( $customer->Memos ) ) {
			if ( is_array( $customer->Memos ) && sizeof( $customer->Memos ) > 0 ) {
				$this->setMemos( $customer->Memos );
			} elseif ( is_array( $customer->Memos ) && sizeof( $customer->Memos ) === 0 ) {
				$this->setMemos( [] );
			}
		} else {
			$this->setMemos( [] );
		}
		if ( isset( $customer->Properties ) ) {
			if ( is_array( $customer->Properties ) && sizeof( $customer->Properties ) > 0 ) {
				$this->setProperties( $customer->Properties );
			} elseif ( is_array( $customer->Properties ) && sizeof( $customer->Properties ) === 0 ) {
				$this->setProperties( [] );
			}
		} else {
			$this->setProperties( [] );
		}
		if ( isset( $customer->Changes ) ) {
			if ( is_array( $customer->Changes ) && sizeof( $customer->Changes ) > 0 ) {
				$this->setChanges( $customer->Changes );
			} elseif ( is_array( $customer->Changes ) && sizeof( $customer->Changes ) === 0 ) {
				$this->setChanges( [] );
			}
		} else {
			$this->setChanges( [] );
		}
		if ( isset( $customer->Attachments ) ) {
			if ( is_array( $customer->Attachments ) && sizeof( $customer->Attachments ) > 0 ) {
				$this->setAttachments( $customer->Attachments );
			} elseif ( is_array( $customer->Attachments ) && sizeof( $customer->Attachments ) === 0 ) {
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

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return bool
	 */
	public function isDeleted() :bool {
		return $this->Deleted;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param bool $Deleted
	 * @return $this
	 */
	public function setDeleted( bool $Deleted ) :Customer {
		$this->Deleted = $Deleted;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return array
	 */
	public function getContacts() :array {
		return $this->Contacts;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param array $Contacts
	 * @return $this
	 */
	public function setContacts( array $Contacts ) :Customer {
		if ( sizeof( $Contacts ) > 0 ) {
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

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return array
	 */
	public function getRecivers() :array {
		return $this->Recivers;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param array $Recivers
	 * @return $this
	 */
	public function setRecivers( array $Recivers ) :Customer {
		$arr = [];
		foreach ( $Recivers as $reciver ) {
			$ItemReciver = new ItemReciverModel();
			$ItemReciver->createItemReciverModelFromDKData( $reciver );
			$arr[] = $ItemReciver;
		}
		$this->Recivers = $arr;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return array
	 */
	public function getMemos() :array {
		return $this->Memos;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param array $Memos
	 * @return $this
	 */
	public function setMemos( array $Memos ) :Customer {
		$this->Memos = $Memos;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return array
	 */
	public function getProperties() :array {
		return $this->Properties;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param array $Properties
	 * @return $this
	 */
	public function setProperties( array $Properties ) :Customer {
		$this->Properties = $Properties;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return array
	 */
	public function getChanges() :array {
		return $this->Changes;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param array $Changes
	 * @return $this
	 */
	public function setChanges( array $Changes ) :Customer {
		$this->Changes = $Changes;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return array
	 */
	public function getAttachments() :array {
		return $this->Attachments;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param array $Attachments
	 * @return $this
	 */
	public function setAttachments( array $Attachments ) :Customer {
		$this->Attachments = $Attachments;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return CustomerCondition
	 */
	public function getConditions() :CustomerCondition {
		return $this->Conditions;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param \NineteenEightyFour\NineteenEightyWoo\Model\Customer\CustomerCondition $Conditions
	 * @return $this
	 */
	public function setConditions( CustomerCondition $Conditions ) :Customer {
		$this->Conditions = $Conditions;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return CustomerSendTo
	 */
	public function getSendTo() :CustomerSendTo {
		return $this->SendTo;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param CustomerSendTo $SendTo
	 * @return $this
	 */
	public function setSendTo( CustomerSendTo $SendTo ) :Customer {
		$this->SendTo = $SendTo;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return UBL
	 */
	public function getUBL() :UBL {
		return $this->UBL;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param UBL $UBL
	 * @return $this
	 */
	public function setUBL( UBL $UBL ) :Customer {
		$this->UBL = $UBL;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return int
	 */
	public function getRecordID() :int {
		return $this->RecordID;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param int $RecordID
	 * @return $this
	 */
	public function setRecordID( int $RecordID ) :Customer {
		$this->RecordID = $RecordID;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return string
	 */
	public function getNumber() :string {
		return $this->Number;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param string $Number
	 * @return $this
	 */
	public function setNumber( string $Number ) :Customer {
		$this->Number = $Number;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return string|null
	 */
	public function getName() :string|null {
		return $this->Name;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param string|null $Name
	 * @return $this
	 */
	public function setName( string|null $Name ) :Customer {
		$this->Name = $Name;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return string|null
	 */
	public function getSSNumber() :string|null {
		return $this->SSNumber;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param string|null $SSNumber
	 * @return $this
	 */
	public function setSSNumber( string|null $SSNumber ) :Customer {
		$this->SSNumber = $SSNumber;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return string|null
	 */
	public function getAlias() :string|null {
		return $this->Alias;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param string|null $Alias
	 * @return $this
	 */
	public function setAlias( string|null $Alias ) :Customer {
		$this->Alias = $Alias;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return string|null
	 */
	public function getAddress1() :string|null {
		return $this->Address1;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param string|null $Address1
	 * @return $this
	 */
	public function setAddress1( string|null $Address1 ) :Customer {
		$this->Address1 = $Address1;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return string|null
	 */
	public function getAddress2() :string|null {
		return $this->Address2;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param string|null $Address2
	 * @return $this
	 */
	public function setAddress2( string|null $Address2 ) :Customer {
		$this->Address2 = $Address2;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return string|null
	 */
	public function getAddress3() :string|null {
		return $this->Address3;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param string|null $Address3
	 * @return $this
	 */
	public function setAddress3( string|null $Address3 ) :Customer {
		$this->Address3 = $Address3;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return string|null
	 */
	public function getCity() :string|null {
		return $this->City;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param string|null $City
	 * @return $this
	 */
	public function setCity( string|null $City ) :Customer {
		$this->City = $City;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return string|null
	 */
	public function getZipCode() :string|null {
		return $this->ZipCode;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param string|null $ZipCode
	 * @return $this
	 */
	public function setZipCode( string|null $ZipCode ) :Customer {
		$this->ZipCode = $ZipCode;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return float
	 */
	public function getBalanceAmount() :float {
		return $this->BalanceAmount;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param float $BalanceAmount
	 * @return $this
	 */
	public function setBalanceAmount( float $BalanceAmount ) :Customer {
		$this->BalanceAmount = $BalanceAmount;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return string|null
	 */
	public function getPhone() :string|null {
		return $this->Phone;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param string|null $Phone
	 * @return $this
	 */
	public function setPhone( string|null $Phone ) :Customer {
		$this->Phone = $Phone;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return string|null
	 */
	public function getPhoneLocal() :string|null {
		return $this->PhoneLocal;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param string|null $PhoneLocal
	 * @return $this
	 */
	public function setPhoneLocal( string|null $PhoneLocal ) :Customer {
		$this->PhoneLocal = $PhoneLocal;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return string|null
	 */
	public function getPhoneMobile() :string|null {
		return $this->PhoneMobile;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param string|null $PhoneMobile
	 * @return $this
	 */
	public function setPhoneMobile( string|null $PhoneMobile ) :Customer {
		$this->PhoneMobile = $PhoneMobile;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return string|null
	 */
	public function getPhoneFax() :string|null {
		return $this->PhoneFax;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param string|null $PhoneFax
	 * @return $this
	 */
	public function setPhoneFax( string|null $PhoneFax ) :Customer {
		$this->PhoneFax = $PhoneFax;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return string|null
	 */
	public function getCountryCode() :string|null {
		return $this->CountryCode;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param string|null $CountryCode
	 * @return $this
	 */
	public function setCountryCode( string|null $CountryCode ) :Customer {
		$this->CountryCode = $CountryCode;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return string|null
	 */
	public function getOriginCountryCode() :string|null {
		return $this->OriginCountryCode;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param string|null $OriginCountryCode
	 * @return $this
	 */
	public function setOriginCountryCode( string|null $OriginCountryCode ) :Customer {
		$this->OriginCountryCode = $OriginCountryCode;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return string|null
	 */
	public function getEmail() :string|null {
		return $this->Email;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param string|null $Email
	 * @return $this
	 */
	public function setEmail( string|null $Email ) :Customer {
		$this->Email = $Email;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return string|null
	 */
	public function getPassword() :string|null {
		return $this->Password;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param string|null $Password
	 * @return $this
	 */
	public function setPassword( string|null $Password ) :Customer {
		$this->Password = $Password;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return string|null
	 */
	public function getGroup() :string|null {
		return $this->Group;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param string|null $Group
	 * @return $this
	 */
	public function setGroup( string|null $Group ) :Customer {
		$this->Group = $Group;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return string|null
	 */
	public function getSalesPerson() :string|null {
		return $this->SalesPerson;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param string|null $SalesPerson
	 * @return $this
	 */
	public function setSalesPerson( string|null $SalesPerson ) :Customer {
		$this->SalesPerson = $SalesPerson;
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
	public function setDiscount( float $Discount ) :Customer {
		$this->Discount = $Discount;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return bool
	 */
	public function isUseItemRecivers() :bool {
		return $this->UseItemRecivers;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param bool $UseItemRecivers
	 * @return $this
	 */
	public function setUseItemRecivers( bool $UseItemRecivers ) :Customer {
		$this->UseItemRecivers = $UseItemRecivers;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return string|null
	 */
	public function getPaymentTerm() :string|null {
		return $this->PaymentTerm;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param string|null $PaymentTerm
	 * @return $this
	 */
	public function setPaymentTerm( string|null $PaymentTerm ) :Customer {
		$this->PaymentTerm = $PaymentTerm;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return string|null
	 */
	public function getPaymentMode() :string|null {
		return $this->PaymentMode;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param string|null $PaymentMode
	 * @return $this
	 */
	public function setPaymentMode( string|null $PaymentMode ) :Customer {
		$this->PaymentMode = $PaymentMode;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return string|null
	 */
	public function getCurrencyCode() :string|null {
		return $this->CurrencyCode;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param string|null $CurrencyCode
	 * @return $this
	 */
	public function setCurrencyCode( string|null $CurrencyCode ) :Customer {
		$this->CurrencyCode = $CurrencyCode;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return bool
	 */
	public function isNoVat() :bool {
		return $this->NoVat;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param bool $NoVat
	 * @return $this
	 */
	public function setNoVat( bool $NoVat ) :Customer {
		$this->NoVat = $NoVat;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return string|null
	 */
	public function getLedgerCode() :string|null {
		return $this->LedgerCode;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param string|null $LedgerCode
	 * @return $this
	 */
	public function setLedgerCode( string|null $LedgerCode ) :Customer {
		$this->LedgerCode = $LedgerCode;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return bool
	 */
	public function isBlocked() :bool {
		return $this->Blocked;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param bool $Blocked
	 * @return $this
	 */
	public function setBlocked( bool $Blocked ) :Customer {
		$this->Blocked = $Blocked;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return string|null
	 */
	public function getGender() :string|null {
		return $this->Gender;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param string|null $Gender
	 * @return $this
	 */
	public function setGender( string|null $Gender ) :Customer {
		$this->Gender = $Gender;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return int
	 */
	public function getPriceGroup() :int {
		return $this->PriceGroup;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param int $PriceGroup
	 * @return $this
	 */
	public function setPriceGroup( int $PriceGroup ) :Customer {
		$this->PriceGroup = $PriceGroup;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return float
	 */
	public function getBillingFee() :float {
		return $this->BillingFee;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param float $BillingFee
	 * @return $this
	 */
	public function setBillingFee( float $BillingFee ) :Customer {
		$this->BillingFee = $BillingFee;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return string|null
	 */
	public function getModified() :string|null {
		return $this->Modified;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param string|null $Modified
	 * @return $this
	 */
	public function setModified( string|null $Modified ) :Customer {
		$this->Modified = $Modified;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return string|null
	 */
	public function getObjectDate() :string|null {
		return $this->ObjectDate;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param string|null $ObjectDate
	 * @return $this
	 */
	public function setObjectDate( string|null $ObjectDate ) :Customer {
		$this->ObjectDate = $ObjectDate;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return string|null
	 */
	public function getVATNumber() :string|null {
		return $this->VATNumber;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param string|null $VATNumber
	 * @return $this
	 */
	public function setVATNumber( string|null $VATNumber ) :Customer {
		$this->VATNumber = $VATNumber;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return string|null
	 */
	public function getISATNumber() :string|null {
		return $this->ISATNumber;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param string|null $ISATNumber
	 * @return $this
	 */
	public function setISATNumber( string|null $ISATNumber ) :Customer {
		$this->ISATNumber = $ISATNumber;
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
