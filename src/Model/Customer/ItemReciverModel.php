<?php

declare(strict_types = 1);

namespace NineteenEightyFour\NineteenEightyWoo\Model\Customer;

use JsonSerializable;
use stdClass;

/**
 * The ItemReciverModel DTO class for DK
 */
class ItemReciverModel implements JsonSerializable {
	protected string $Number;

	protected string|null $Name;

	protected string|null $Address1;

	protected string|null $Address2;

	protected string|null $Address3;

	protected string|null $Address4;

	protected string|null $City;

	protected string|null $ZipCode;

	protected string|null $CountryCode;

	protected string|null $SSNumber;

	protected string|null $Phone;

	protected string|null $PhoneLocal;

	protected string|null $PhoneMobile;

	protected string|null $Fax;

	protected string|null $Telex;

	protected string|null $Email;

	protected string|null $URL;

	protected string|null $EANNumber;

	public string|null $VATNumber;

	public bool $Blocked = false;

	public string|null $Modified;

	public function createItemReciverModelFromDKData( stdClass $ItemReciver ): void {
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

	public function getNumber(): ?string {
		return $this->Number;
	}

	public function setNumber( ?string $Number ): ItemReciverModel {
		$this->Number = $Number;
		return $this;
	}

	public function getName(): ?string {
		return $this->Name;
	}

	public function setName( ?string $Name ): ItemReciverModel {
		$this->Name = $Name;
		return $this;
	}

	public function getAddress1(): ?string {
		return $this->Address1;
	}

	public function setAddress1( ?string $Address1 ): ItemReciverModel {
		$this->Address1 = $Address1;
		return $this;
	}

	public function getAddress2(): ?string {
		return $this->Address2;
	}

	public function setAddress2( ?string $Address2 ): ItemReciverModel {
		$this->Address2 = $Address2;
		return $this;
	}

	public function getAddress3(): ?string {
		return $this->Address3;
	}

	public function setAddress3( ?string $Address3 ): ItemReciverModel {
		$this->Address3 = $Address3;
		return $this;
	}

	public function getAddress4(): ?string {
		return $this->Address4;
	}

	public function setAddress4( ?string $Address4 ): ItemReciverModel {
		$this->Address4 = $Address4;
		return $this;
	}

	public function getCity(): ?string {
		return $this->City;
	}

	public function setCity( ?string $City ): ItemReciverModel {
		$this->City = $City;
		return $this;
	}

	public function getZipCode(): ?string {
		return $this->ZipCode;
	}

	public function setZipCode( ?string $ZipCode ): ItemReciverModel {
		$this->ZipCode = $ZipCode;
		return $this;
	}

	public function getCountryCode(): ?string {
		return $this->CountryCode;
	}

	public function setCountryCode( ?string $CountryCode ): ItemReciverModel {
		$this->CountryCode = $CountryCode;
		return $this;
	}

	public function getSSNumber(): ?string {
		return $this->SSNumber;
	}

	public function setSSNumber( ?string $SSNumber ): ItemReciverModel {
		$this->SSNumber = $SSNumber;
		return $this;
	}

	public function getPhone(): ?string {
		return $this->Phone;
	}

	public function setPhone( ?string $Phone ): ItemReciverModel {
		$this->Phone = $Phone;
		return $this;
	}

	public function getPhoneLocal(): ?string {
		return $this->PhoneLocal;
	}

	public function setPhoneLocal( ?string $PhoneLocal ): ItemReciverModel {
		$this->PhoneLocal = $PhoneLocal;
		return $this;
	}

	public function getPhoneMobile(): ?string {
		return $this->PhoneMobile;
	}

	public function setPhoneMobile( ?string $PhoneMobile ): ItemReciverModel {
		$this->PhoneMobile = $PhoneMobile;
		return $this;
	}

	public function getFax(): ?string {
		return $this->Fax;
	}

	public function setFax( ?string $Fax ): ItemReciverModel {
		$this->Fax = $Fax;
		return $this;
	}

	public function getTelex(): ?string {
		return $this->Telex;
	}

	public function setTelex( ?string $Telex ): ItemReciverModel {
		$this->Telex = $Telex;
		return $this;
	}

	public function getEmail(): ?string {
		return $this->Email;
	}

	public function setEmail( ?string $Email ): ItemReciverModel {
		$this->Email = $Email;
		return $this;
	}

	public function getURL(): ?string {
		return $this->URL;
	}

	public function setURL( ?string $URL ): ItemReciverModel {
		$this->URL = $URL;
		return $this;
	}

	public function getEANNumber(): ?string {
		return $this->EANNumber;
	}

	public function setEANNumber( ?string $EANNumber ): ItemReciverModel {
		$this->EANNumber = $EANNumber;
		return $this;
	}

	public function getVATNumber(): ?string {
		return $this->VATNumber;
	}

	public function setVATNumber( ?string $VATNumber ): ItemReciverModel {
		$this->VATNumber = $VATNumber;
		return $this;
	}

	public function isBlocked(): bool {
		return $this->Blocked;
	}

	public function setBlocked( bool $Blocked ): ItemReciverModel {
		$this->Blocked = $Blocked;
		return $this;
	}

	public function getModified(): ?string {
		return $this->Modified;
	}

	public function setModified( ?string $Modified ): ItemReciverModel {
		$this->Modified = $Modified;
		return $this;
	}

	public function jsonSerialize(): string {
		return json_encode( get_object_vars( $this ) );
	}
}
