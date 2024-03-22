<?php

declare(strict_types = 1);

namespace NineteenEightyFour\NineteenEightyWoo\Model\Customer;

use JsonSerializable;

/**
 * The ContactModel DTO Class for DK
 */
class ContactModel implements JsonSerializable {
	/**
	 * @var string $Number
	 */
	protected string $Number;

	/**
	 * @var string|null $Name
	 */
	protected string|null $Name;

	/**
	 * @var string|null $Address1
	 */
	protected string|null $Address1;

	/**
	 * @var string|null $Address2
	 */
	protected string|null $Address2;

	/**
	 * @var string|null $Address3
	 */
	protected string|null $Address3;

	/**
	 * @var string|null $City
	 */
	protected string|null $City;

	/**
	 * @var string|null $ZipCode
	 */
	protected string|null $ZipCode;

	/**
	 * @var string|null $CountryCode
	 */
	protected string|null $CountryCode;

	/**
	 * @var string|null $Department
	 */
	protected string|null $Department;

	/**
	 * @var string|null $SSNumber
	 */
	protected string|null $SSNumber;

	/**
	 * @var string|null $Phone
	 */
	protected string|null $Phone;

	/**
	 * @var string|null $PhoneLocal
	 */
	protected string|null $PhoneLocal;

	/**
	 * @var string|null $PhoneMobile
	 */
	protected string|null $PhoneMobile;

	/**
	 * @var string|null $Fax
	 */
	protected string|null $Fax;

	/**
	 * @var string|null $Telex
	 */
	protected string|null $Telex;

	/**
	 * @var string|null $Email
	 */
	protected string|null $Email;

	/**
	 * @var string|null $URL
	 */
	protected string|null $URL;

	/**
	 * @var string|null $JobTitleCode
	 */
	protected string|null $JobTitleCode;

	/**
	 * @var string|null $Modified
	 */
	protected string|null $Modified;

	public function getNumber(): ?string {
		return $this->Number;
	}

	/**
	 * @param $contact_model
	 */
	public function createContactModelFromDKData( $contact_model ): void {
		// @TODO: Implement this Model
	}

	/**
	 * @return $this
	 */
	public function setNumber( ?string $Number ): ContactModel {
		$this->Number = $Number;
		return $this;
	}

	public function getName(): ?string {
		return $this->Name;
	}

	/**
	 * @return $this
	 */
	public function setName( ?string $Name ): ContactModel {
		$this->Name = $Name;
		return $this;
	}

	public function getAddress1(): ?string {
		return $this->Address1;
	}

	/**
	 * @return $this
	 */
	public function setAddress1( ?string $Address1 ): ContactModel {
		$this->Address1 = $Address1;
		return $this;
	}

	public function getAddress2(): ?string {
		return $this->Address2;
	}

	/**
	 * @return $this
	 */
	public function setAddress2( ?string $Address2 ): ContactModel {
		$this->Address2 = $Address2;
		return $this;
	}

	public function getAddress3(): ?string {
		return $this->Address3;
	}

	/**
	 * @return $this
	 */
	public function setAddress3( ?string $Address3 ): ContactModel {
		$this->Address3 = $Address3;
		return $this;
	}

	public function getCity(): ?string {
		return $this->City;
	}

	/**
	 * @return $this
	 */
	public function setCity( ?string $City ): ContactModel {
		$this->City = $City;
		return $this;
	}

	public function getZipCode(): ?string {
		return $this->ZipCode;
	}

	/**
	 * @return $this
	 */
	public function setZipCode( ?string $ZipCode ): ContactModel {
		$this->ZipCode = $ZipCode;
		return $this;
	}

	public function getCountryCode(): ?string {
		return $this->CountryCode;
	}

	/**
	 * @return $this
	 */
	public function setCountryCode( ?string $CountryCode ): ContactModel {
		$this->CountryCode = $CountryCode;
		return $this;
	}

	public function getDepartment(): ?string {
		return $this->Department;
	}

	/**
	 * @return $this
	 */
	public function setDepartment( ?string $Department ): ContactModel {
		$this->Department = $Department;
		return $this;
	}

	public function getSSNumber(): ?string {
		return $this->SSNumber;
	}

	/**
	 * @return $this
	 */
	public function setSSNumber( ?string $SSNumber ): ContactModel {
		$this->SSNumber = $SSNumber;
		return $this;
	}

	public function getPhone(): ?string {
		return $this->Phone;
	}

	/**
	 * @return $this
	 */
	public function setPhone( ?string $Phone ): ContactModel {
		$this->Phone = $Phone;
		return $this;
	}

	public function getPhoneLocal(): ?string {
		return $this->PhoneLocal;
	}

	/**
	 * @return $this
	 */
	public function setPhoneLocal( ?string $PhoneLocal ): ContactModel {
		$this->PhoneLocal = $PhoneLocal;
		return $this;
	}

	public function getPhoneMobile(): ?string {
		return $this->PhoneMobile;
	}

	/**
	 * @return $this
	 */
	public function setPhoneMobile( ?string $PhoneMobile ): ContactModel {
		$this->PhoneMobile = $PhoneMobile;
		return $this;
	}

	public function getFax(): ?string {
		return $this->Fax;
	}

	/**
	 * @return $this
	 */
	public function setFax( ?string $Fax ): ContactModel {
		$this->Fax = $Fax;
		return $this;
	}

	public function getTelex(): ?string {
		return $this->Telex;
	}

	/**
	 * @return $this
	 */
	public function setTelex( ?string $Telex ): ContactModel {
		$this->Telex = $Telex;
		return $this;
	}

	public function getEmail(): ?string {
		return $this->Email;
	}

	/**
	 * @return $this
	 */
	public function setEmail( ?string $Email ): ContactModel {
		$this->Email = $Email;
		return $this;
	}

	public function getURL(): ?string {
		return $this->URL;
	}

	/**
	 * @return $this
	 */
	public function setURL( ?string $URL ): ContactModel {
		$this->URL = $URL;
		return $this;
	}

	public function getJobTitleCode(): ?string {
		return $this->JobTitleCode;
	}

	/**
	 * @return $this
	 */
	public function setJobTitleCode( ?string $JobTitleCode ): ContactModel {
		$this->JobTitleCode = $JobTitleCode;
		return $this;
	}

	public function getModified(): ?string {
		return $this->Modified;
	}

	/**
	 * @return $this
	 */
	public function setModified( ?string $Modified ): ContactModel {
		$this->Modified = $Modified;
		return $this;
	}

	public function jsonSerialize(): string {
		return json_encode( get_object_vars( $this ) );
	}
}
