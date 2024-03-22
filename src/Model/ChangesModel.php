<?php

declare(strict_types = 1);

namespace NineteenEightyFour\NineteenEightyWoo\Model;

use JsonSerializable;

/**
 * The ChangesModel DTO class for DK
 */
class ChangesModel implements JsonSerializable {
	/**
	 * @var string
	 */
	protected string $Modified;

	/**
	 * @var string
	 */
	protected string $By;

	/**
	 * @var array
	 */
	protected array $Fields;

	/**
	 * @return string
	 */
	public function getModified() : string {
		return $this->Modified;
	}

	/**
	 * @param string $Modified
	 * @return ChangesModel
	 */
	public function setModified(string $Modified ) : ChangesModel {
		$this->Modified = $Modified;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getBy() : string {
		return $this->By;
	}

	/**
	 * @param string $By
	 * @return ChangesModel
	 */
	public function setBy(string $By ) : ChangesModel {
		$this->By = $By;
		return $this;
	}

	/**
	 * @return array
	 */
	public function getFields() : array {
		return $this->Fields;
	}

	/**
	 * @param array $Fields
	 * @return ChangesModel
	 */
	public function setFields(array $Fields ) : ChangesModel {
		$this->Fields = $Fields;
		return $this;
	}


	/**
	 * @return string
	 */
	public function jsonSerialize() : string {
		return json_encode( get_object_vars( $this ) );
	}
}
