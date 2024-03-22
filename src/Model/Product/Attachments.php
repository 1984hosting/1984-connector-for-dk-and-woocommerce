<?php

declare(strict_types = 1);

namespace NineteenEightyFour\NineteenEightyWoo\Model\Product;

use stdClass;

/**
 * The Attachments DTO class for DK
 */
class Attachments implements \JsonSerializable {
	/**
	 * @var int $ID
	 */
	protected int $ID;

	/**
	 * @var string $Name
	 */
	protected string $Name;

	/**
	 * @var int $Size
	 */
	protected int $Size;

	/**
	 * @var string $Linked
	 */
	protected string $Linked;

	/**
	 * @var string $MD5Hash
	 */
	protected string $MD5Hash;

	/**
	 * @var bool $ShowOnWeb
	 */
	protected bool $ShowOnWeb = true;

	/**
	 *
	 */
	public function createAttachmentFromDKData(stdClass $attachment) {
		// @TODO: Implement this
	}

	/**
	 * @return int
	 */
	public function getID() : int {
		return $this->ID;
	}

	/**
	 * @param int $ID
	 * @return Attachments
	 */
	public function setID(int $ID ) : Attachments {
		$this->ID = $ID;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getName() : string {
		return $this->Name;
	}

	/**
	 * @param string $Name
	 * @return Attachments
	 */
	public function setName(string $Name ) : Attachments {
		$this->Name = $Name;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getSize() : int {
		return $this->Size;
	}

	/**
	 * @param int $Size
	 * @return Attachments
	 */
	public function setSize(int $Size ) : Attachments {
		$this->Size = $Size;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getLinked() : string {
		return $this->Linked;
	}

	/**
	 * @param string $Linked
	 * @return Attachments
	 */
	public function setLinked(string $Linked ) : Attachments {
		$this->Linked = $Linked;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getMD5Hash() : string {
		return $this->MD5Hash;
	}

	/**
	 * @param string $MD5Hash
	 * @return Attachments
	 */
	public function setMD5Hash(string $MD5Hash ) : Attachments {
		$this->MD5Hash = $MD5Hash;
		return $this;
	}

	/**
	 * @return bool
	 */
	public function isShowOnWeb() : bool {
		return $this->ShowOnWeb;
	}

	/**
	 * @param bool $ShowOnWeb
	 * @return Attachments
	 */
	public function setShowOnWeb(bool $ShowOnWeb ) : Attachments {
		$this->ShowOnWeb = $ShowOnWeb;
		return $this;
	}


	/**
	 * @return string
	 */
	public function jsonSerialize() : string {
		return json_encode( get_object_vars( $this ) );
	}
}
