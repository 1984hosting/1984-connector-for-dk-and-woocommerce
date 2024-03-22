<?php

declare(strict_types = 1);

namespace NineteenEightyFour\NineteenEightyWoo\Model\Product;

use stdClass;

/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
 * The Attachments DTO class for DK
 */
class Attachments implements \JsonSerializable {
	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var int $ID
	 */
	protected int $ID;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string $Name
	 */
	protected string $Name;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var int $Size
	 */
	protected int $Size;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string $Linked
	 */
	protected string $Linked;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string $MD5Hash
	 */
	protected string $MD5Hash;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var bool $ShowOnWeb
	 */
	protected bool $ShowOnWeb = true;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 *
	 */
	public function createAttachmentFromDKData(stdClass $attachment) {
		// @TODO: Implement this
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return int
	 */
	public function getID() : int {
		return $this->ID;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param int $ID
	 * @return Attachments
	 */
	public function setID(int $ID ) : Attachments {
		$this->ID = $ID;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return string
	 */
	public function getName() : string {
		return $this->Name;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param string $Name
	 * @return Attachments
	 */
	public function setName(string $Name ) : Attachments {
		$this->Name = $Name;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return int
	 */
	public function getSize() : int {
		return $this->Size;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param int $Size
	 * @return Attachments
	 */
	public function setSize(int $Size ) : Attachments {
		$this->Size = $Size;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return string
	 */
	public function getLinked() : string {
		return $this->Linked;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param string $Linked
	 * @return Attachments
	 */
	public function setLinked(string $Linked ) : Attachments {
		$this->Linked = $Linked;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return string
	 */
	public function getMD5Hash() : string {
		return $this->MD5Hash;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param string $MD5Hash
	 * @return Attachments
	 */
	public function setMD5Hash(string $MD5Hash ) : Attachments {
		$this->MD5Hash = $MD5Hash;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return bool
	 */
	public function isShowOnWeb() : bool {
		return $this->ShowOnWeb;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param bool $ShowOnWeb
	 * @return Attachments
	 */
	public function setShowOnWeb(bool $ShowOnWeb ) : Attachments {
		$this->ShowOnWeb = $ShowOnWeb;
		return $this;
	}


	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return string
	 */
	public function jsonSerialize() : string {
		return json_encode( get_object_vars( $this ) );
	}
}
