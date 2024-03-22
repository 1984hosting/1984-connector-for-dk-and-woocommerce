<?php

declare(strict_types = 1);

namespace NineteenEightyFour\NineteenEightyWoo\Model;

use JsonSerializable;

/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
 * The AttachmentModel DTO class for DK
 */
class AttachmentModel implements JsonSerializable {
	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var int
	 */
	protected int $ID;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string
	 */
	protected string $Name;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var int
	 */
	protected int $Size;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string
	 */
	protected string $Linked;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string
	 */
	protected string $MD5Hash;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var bool
	 */
	protected bool $ShowOnWeb;

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
	 * @return AttachmentModel
	 */
	public function setID(int $ID ) : AttachmentModel {
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
	 * @return AttachmentModel
	 */
	public function setName(string $Name ) : AttachmentModel {
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
	 * @return AttachmentModel
	 */
	public function setSize(int $Size ) : AttachmentModel {
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
	 * @return AttachmentModel
	 */
	public function setLinked(string $Linked ) : AttachmentModel {
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
	 * @return AttachmentModel
	 */
	public function setMD5Hash(string $MD5Hash ) : AttachmentModel {
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
	 * @return AttachmentModel
	 */
	public function setShowOnWeb(bool $ShowOnWeb ) : AttachmentModel {
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
