<?php

declare(strict_types = 1);

namespace NineteenEightyFour\NineteenEightyWoo\Model\Customer;

use JsonSerializable;
use stdClass;

/**
 * The ItemReciverModel DTO class for DK
 */
class ItemReciverModel implements JsonSerializable {
	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string $Number
	 */
	protected string $Number;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string|null $Name
	 */
	protected string|null $Name;

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
	 * @var string|null $Address4
	 */
	protected string|null $Address4;

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
	 * @var string|null $CountryCode
	 */
	protected string|null $CountryCode;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string|null $SSNumber
	 */
	protected string|null $SSNumber;

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
	 * @var string|null $Fax
	 */
	protected string|null $Fax;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string|null $Telex
	 */
	protected string|null $Telex;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string|null $Email
	 */
	protected string|null $Email;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string|null $URL
	 */
	protected string|null $URL;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string|null $EANNumber
	 */
	protected string|null $EANNumber;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string|null $VATNumber
	 */
	public string|null $VATNumber;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var bool $Blocked
	 */
	public bool $Blocked = false;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string|null $Modified
	 */
	public string|null $Modified;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param stdClass $ItemReciver
	 * @return void
	 */
	public function createItemReciverModelFromDKData( stdClass $ItemReciver ) :void {
		$this->setNumber( $ItemReciver->Number );
		$this->setName( $ItemReciver->Name ?? null );
		$this->setAddress1( $ItemReciver->Address1 ?? null );
		$this->setAddress2( $ItemReciver->Address2 ?? null );
		$this->setAddress3( $ItemReciver->Address3 ?? null );
		$this->setAddress4( $ItemReciver->Address4 ?? null );
		$this->setCity( $ItemReciver->City ?? null );
		$this->setZipCode( $ItemReciver->ZipCode ?? null );
		$this->setCountryCode( $ItemReciver->CountryCode ?? null );
		$this->setSSNumber( $ItemReciver->SSnumber ?? null );
		$this->setPhone( $ItemReciver->Phone ?? null );
		$this->setPhoneLocal( $ItemReciver->PhoneLocal ?? null );
		$this->setPhoneMobile( $ItemReciver->PhoneMobile ?? null );
		$this->setFax( $ItemReciver->Fax ?? null );
		$this->setTelex( $ItemReciver->Telex ?? null );
		$this->setEmail( $ItemReciver->Email ?? null );
		$this->setURL( $ItemReciver->URL ?? null );
		$this->setEANNumber( $ItemReciver->EANNumber ?? null );
		$this->setVATNumber( $ItemReciver->VATNumber ?? null );
		$this->setBlocked( $ItemReciver->Blocked ?? false );
		$this->setModified( $ItemReciver->Modified ?? null );
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
	public function setNumber( ?string $Number ) :ItemReciverModel {
		$this->Number = $Number;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return string|null
	 */
	public function getName() :?string {
		return $this->Name;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param string|null $Name
	 * @return $this
	 */
	public function setName( ?string $Name ) :ItemReciverModel {
		$this->Name = $Name;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return string|null
	 */
	public function getAddress1() :?string {
		return $this->Address1;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param string|null $Address1
	 * @return $this
	 */
	public function setAddress1( ?string $Address1 ) :ItemReciverModel {
		$this->Address1 = $Address1;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return string|null
	 */
	public function getAddress2() :?string {
		return $this->Address2;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param string|null $Address2
	 * @return $this
	 */
	public function setAddress2( ?string $Address2 ) :ItemReciverModel {
		$this->Address2 = $Address2;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return string|null
	 */
	public function getAddress3() :?string {
		return $this->Address3;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param string|null $Address3
	 * @return $this
	 */
	public function setAddress3( ?string $Address3 ) :ItemReciverModel {
		$this->Address3 = $Address3;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return string|null
	 */
	public function getAddress4() :?string {
		return $this->Address4;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param string|null $Address4
	 * @return $this
	 */
	public function setAddress4( ?string $Address4 ) :ItemReciverModel {
		$this->Address4 = $Address4;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return string|null
	 */
	public function getCity() :?string {
		return $this->City;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param string|null $City
	 * @return $this
	 */
	public function setCity( ?string $City ) :ItemReciverModel {
		$this->City = $City;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return string|null
	 */
	public function getZipCode() :?string {
		return $this->ZipCode;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param string|null $ZipCode
	 * @return $this
	 */
	public function setZipCode( ?string $ZipCode ) :ItemReciverModel {
		$this->ZipCode = $ZipCode;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return string|null
	 */
	public function getCountryCode() :?string {
		return $this->CountryCode;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param string|null $CountryCode
	 * @return $this
	 */
	public function setCountryCode( ?string $CountryCode ) :ItemReciverModel {
		$this->CountryCode = $CountryCode;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return string|null
	 */
	public function getSSNumber() :?string {
		return $this->SSNumber;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param string|null $SSNumber
	 * @return $this
	 */
	public function setSSNumber( ?string $SSNumber ) :ItemReciverModel {
		$this->SSNumber = $SSNumber;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return string|null
	 */
	public function getPhone() :?string {
		return $this->Phone;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param string|null $Phone
	 * @return $this
	 */
	public function setPhone( ?string $Phone ) :ItemReciverModel {
		$this->Phone = $Phone;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return string|null
	 */
	public function getPhoneLocal() :?string {
		return $this->PhoneLocal;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param string|null $PhoneLocal
	 * @return $this
	 */
	public function setPhoneLocal( ?string $PhoneLocal ) :ItemReciverModel {
		$this->PhoneLocal = $PhoneLocal;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return string|null
	 */
	public function getPhoneMobile() :?string {
		return $this->PhoneMobile;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param string|null $PhoneMobile
	 * @return $this
	 */
	public function setPhoneMobile( ?string $PhoneMobile ) :ItemReciverModel {
		$this->PhoneMobile = $PhoneMobile;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return string|null
	 */
	public function getFax() :?string {
		return $this->Fax;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param string|null $Fax
	 * @return $this
	 */
	public function setFax( ?string $Fax ) :ItemReciverModel {
		$this->Fax = $Fax;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return string|null
	 */
	public function getTelex() :?string {
		return $this->Telex;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param string|null $Telex
	 * @return $this
	 */
	public function setTelex( ?string $Telex ) :ItemReciverModel {
		$this->Telex = $Telex;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return string|null
	 */
	public function getEmail() :?string {
		return $this->Email;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param string|null $Email
	 * @return $this
	 */
	public function setEmail( ?string $Email ) :ItemReciverModel {
		$this->Email = $Email;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return string|null
	 */
	public function getURL() :?string {
		return $this->URL;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param string|null $URL
	 * @return $this
	 */
	public function setURL( ?string $URL ) :ItemReciverModel {
		$this->URL = $URL;
		return $this;
	}

	public function getEANNumber() :?string {
		return $this->EANNumber;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param string|null $EANNumber
	 * @return $this
	 */
	public function setEANNumber( ?string $EANNumber ) :ItemReciverModel {
		$this->EANNumber = $EANNumber;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return string|null
	 */
	public function getVATNumber() :?string {
		return $this->VATNumber;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param string|null $VATNumber
	 * @return $this
	 */
	public function setVATNumber( ?string $VATNumber ) :ItemReciverModel {
		$this->VATNumber = $VATNumber;
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
	public function setBlocked( bool $Blocked ) :ItemReciverModel {
		$this->Blocked = $Blocked;
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
	public function setModified( ?string $Modified ) :ItemReciverModel {
		$this->Modified = $Modified;
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
