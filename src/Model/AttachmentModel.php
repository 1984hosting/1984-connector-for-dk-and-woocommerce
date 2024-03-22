<?php

declare(strict_types = 1);

namespace NineteenEightyFour\NineteenEightyWoo\Model;

use JsonSerializable;

/**
 * The AttachmentModel DTO class for DK
 */
class AttachmentModel implements JsonSerializable {
	/**
	 * @var int
	 */
	protected int $ID;

	/**
	 * @var string
	 */
	protected string $Name;

	/**
	 * @var int
	 */
	protected int $Size;

	/**
	 * @var string
	 */
	protected string $Linked;

	/**
	 * @var string
	 */
	protected string $MD5Hash;

	/**
	 * @var bool
	 */
	protected bool $ShowOnWeb;

	/**
	 * @return int
	 */
	public function getID() : int {
		return $this->ID;
	}

	/**
	 * @param int $ID
	 * @return AttachmentModel
	 */
	public function setID(int $ID ) : AttachmentModel {
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
	 * @return AttachmentModel
	 */
	public function setName(string $Name ) : AttachmentModel {
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
	 * @return AttachmentModel
	 */
	public function setSize(int $Size ) : AttachmentModel {
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
	 * @return AttachmentModel
	 */
	public function setLinked(string $Linked ) : AttachmentModel {
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
	 * @return AttachmentModel
	 */
	public function setMD5Hash(string $MD5Hash ) : AttachmentModel {
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
	 * @return AttachmentModel
	 */
	public function setShowOnWeb(bool $ShowOnWeb ) : AttachmentModel {
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
