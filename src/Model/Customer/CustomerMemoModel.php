<?php

declare(strict_types = 1);

namespace NineteenEightyFour\NineteenEightyWoo\Model\Customer;

use JsonSerializable;

class CustomerMemoModel implements JsonSerializable {
	/**
	 * @var string|null $PageName
	 */
	protected ?string $PageName;

	/**
	 * @var string|null $PlainText
	 */
	protected ?string $PlainText;

	/**
	 * @var string|null $Modified
	 */
	protected ?string $Modified;

	/**
	 * @var int $RecordID
	 */
	protected int $RecordID = 0;

	/**
	 * @return string|null
	 */
	public function getPageName(): ?string {
		return $this->PageName;
	}

	/**
	 * @param string|null $PageName
	 * @return $this
	 */
	public function setPageName( ?string $PageName ): CustomerMemoModel {
		$this->PageName = $PageName;
		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getPlainText(): ?string {
		return $this->PlainText;
	}

	/**
	 * @param string|null $PlainText
	 * @return $this
	 */
	public function setPlainText( ?string $PlainText ): CustomerMemoModel {
		$this->PlainText = $PlainText;
		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getModified(): ?string {
		return $this->Modified;
	}

	/**
	 * @param string|null $Modified
	 * @return $this
	 */
	public function setModified( ?string $Modified ): CustomerMemoModel {
		$this->Modified = $Modified;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getRecordID(): int {
		return $this->RecordID;
	}

	/**
	 * @param int $RecordID
	 * @return $this
	 */
	public function setRecordID( int $RecordID ): CustomerMemoModel {
		$this->RecordID = $RecordID;
		return $this;
	}

	/**
	 * @return string
	 */
	public function jsonSerialize(): string {
		return json_encode( get_object_vars( $this ) );
	}
}
