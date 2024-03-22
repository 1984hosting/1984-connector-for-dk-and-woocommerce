<?php

declare(strict_types = 1);

namespace NineteenEightyFour\NineteenEightyWoo\Model\Customer;

use JsonSerializable;

class CustomerPropertyModel implements JsonSerializable {
	/**
	 * @var string|null $ID
	 */
	protected ?string $ID;

	/**
	 * @var string|null $Attribute
	 */
	protected ?string $Attribute;

	/**
	 * @var string|null $Option
	 */
	protected ?string $Option;

	/**
	 * @var string|null $Comment
	 */
	protected ?string $Comment;

	/**
	 * @var string|null $Modified
	 */
	protected ?string $Modified;

	/**
	 * @return string|null
	 */
	public function getID() : ?string {
		return $this->ID;
	}

	/**
	 * @param string|null $ID
	 * @return $this
	 */
	public function setID( ?string $ID ) : CustomerPropertyModel {
		$this->ID = $ID;
		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getAttribute() : ?string {
		return $this->Attribute;
	}

	/**
	 * @param string|null $Attribute
	 * @return $this
	 */
	public function setAttribute( ?string $Attribute ) : CustomerPropertyModel {
		$this->Attribute = $Attribute;
		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getOption() : ?string {
		return $this->Option;
	}

	/**
	 * @param string|null $Option
	 * @return $this
	 */
	public function setOption( ?string $Option ) : CustomerPropertyModel {
		$this->Option = $Option;
		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getComment() : ?string {
		return $this->Comment;
	}

	/**
	 * @param string|null $Comment
	 * @return $this
	 */
	public function setComment( ?string $Comment ) : CustomerPropertyModel {
		$this->Comment = $Comment;
		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getModified() : ?string {
		return $this->Modified;
	}

	/**
	 * @param string|null $Modified
	 * @return $this
	 */
	public function setModified( ?string $Modified ) : CustomerPropertyModel {
		$this->Modified = $Modified;
		return $this;
	}

	/**
	 * @return string
	 */
	public function jsonSerialize() : string {
		return json_encode( get_object_vars( $this ) );
	}
}
