<?php

declare(strict_types = 1);

namespace NineteenEightyFour\NineteenEightyWoo\Model\Customer;

use JsonSerializable;

class ContactModel implements JsonSerializable {
	protected string $Number;

	protected string|null $Name;

	protected string|null $Address1;

	protected string|null $Address2;

	protected string|null $Address3;

	protected string|null $City;

	protected string|null $ZipCode;

	protected string|null $CountryCode;

	protected string|null $Department;

	protected string|null $SSNumber;

	protected string|null $Phone;

	protected string|null $PhoneLocal;

	protected string|null $PhoneMobile;

	protected string|null $Fax;

	protected string|null $Telex;

	protected string|null $Email;

	protected string|null $URL;

	protected string|null $JobTitleCode;

	protected string|null $Modified;

	public function getNumber(): ?string {
		return $this->Number;
	}

	public function createContactModelFromDKData( $contact_model ): void {
	}

	public function setNumber( ?string $Number ): ContactModel {
		$this->Number = $Number;
		return $this;
	}

	public function getName(): ?string {
		return $this->Name;
	}

	public function setName( ?string $Name ): ContactModel {
		$this->Name = $Name;
		return $this;
	}

	public function getAddress1(): ?string {
		return $this->Address1;
	}

	public function setAddress1( ?string $Address1 ): ContactModel {
		$this->Address1 = $Address1;
		return $this;
	}

	public function getAddress2(): ?string {
		return $this->Address2;
	}

	public function setAddress2( ?string $Address2 ): ContactModel {
		$this->Address2 = $Address2;
		return $this;
	}

	public function getAddress3(): ?string {
		return $this->Address3;
	}

	public function setAddress3( ?string $Address3 ): ContactModel {
		$this->Address3 = $Address3;
		return $this;
	}

	public function getCity(): ?string {
		return $this->City;
	}

	public function setCity( ?string $City ): ContactModel {
		$this->City = $City;
		return $this;
	}

	public function getZipCode(): ?string {
		return $this->ZipCode;
	}

	public function setZipCode( ?string $ZipCode ): ContactModel {
		$this->ZipCode = $ZipCode;
		return $this;
	}

	public function getCountryCode(): ?string {
		return $this->CountryCode;
	}

	public function setCountryCode( ?string $CountryCode ): ContactModel {
		$this->CountryCode = $CountryCode;
		return $this;
	}

	public function getDepartment(): ?string {
		return $this->Department;
	}

	public function setDepartment( ?string $Department ): ContactModel {
		$this->Department = $Department;
		return $this;
	}

	public function getSSNumber(): ?string {
		return $this->SSNumber;
	}

	public function setSSNumber( ?string $SSNumber ): ContactModel {
		$this->SSNumber = $SSNumber;
		return $this;
	}

	public function getPhone(): ?string {
		return $this->Phone;
	}

	public function setPhone( ?string $Phone ): ContactModel {
		$this->Phone = $Phone;
		return $this;
	}

	public function getPhoneLocal(): ?string {
		return $this->PhoneLocal;
	}

	public function setPhoneLocal( ?string $PhoneLocal ): ContactModel {
		$this->PhoneLocal = $PhoneLocal;
		return $this;
	}

	public function getPhoneMobile(): ?string {
		return $this->PhoneMobile;
	}

	public function setPhoneMobile( ?string $PhoneMobile ): ContactModel {
		$this->PhoneMobile = $PhoneMobile;
		return $this;
	}

	public function getFax(): ?string {
		return $this->Fax;
	}

	public function setFax( ?string $Fax ): ContactModel {
		$this->Fax = $Fax;
		return $this;
	}

	public function getTelex(): ?string {
		return $this->Telex;
	}

	public function setTelex( ?string $Telex ): ContactModel {
		$this->Telex = $Telex;
		return $this;
	}

	public function getEmail(): ?string {
		return $this->Email;
	}

	public function setEmail( ?string $Email ): ContactModel {
		$this->Email = $Email;
		return $this;
	}

	public function getURL(): ?string {
		return $this->URL;
	}

	public function setURL( ?string $URL ): ContactModel {
		$this->URL = $URL;
		return $this;
	}

	public function getJobTitleCode(): ?string {
		return $this->JobTitleCode;
	}

	public function setJobTitleCode( ?string $JobTitleCode ): ContactModel {
		$this->JobTitleCode = $JobTitleCode;
		return $this;
	}

	public function getModified(): ?string {
		return $this->Modified;
	}

	public function setModified( ?string $Modified ): ContactModel {
		$this->Modified = $Modified;
		return $this;
	}



	public function jsonSerialize(): string {
		return json_encode( get_object_vars( $this ) );
	}
}
