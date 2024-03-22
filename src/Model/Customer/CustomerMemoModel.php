<?php

declare(strict_types = 1);

namespace NineteenEightyFour\NineteenEightyWoo\Model\Customer;

use JsonSerializable;

/**
 * The CustomerMemoModel DTO class for DK
 */
class CustomerMemoModel implements JsonSerializable {
	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string|null $PageName
	 */
	protected ?string $PageName;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string|null $PlainText
	 */
	protected ?string $PlainText;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string|null $Modified
	 */
	protected ?string $Modified;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var int $RecordID
	 */
	protected int $RecordID = 0;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return string|null
	 */
	public function getPageName() :?string {
		return $this->PageName;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param string|null $PageName
	 * @return $this
	 */
	public function setPageName( ?string $PageName ) :CustomerMemoModel {
		$this->PageName = $PageName;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return string|null
	 */
	public function getPlainText() :?string {
		return $this->PlainText;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param string|null $PlainText
	 * @return $this
	 */
	public function setPlainText( ?string $PlainText ) :CustomerMemoModel {
		$this->PlainText = $PlainText;
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
	public function setModified( ?string $Modified ) :CustomerMemoModel {
		$this->Modified = $Modified;
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
	public function setRecordID( int $RecordID ) :CustomerMemoModel {
		$this->RecordID = $RecordID;
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
