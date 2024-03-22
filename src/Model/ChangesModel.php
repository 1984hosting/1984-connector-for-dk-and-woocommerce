<?php

declare(strict_types = 1);

namespace NineteenEightyFour\NineteenEightyWoo\Model;

use JsonSerializable;

/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
 * The ChangesModel DTO class for DK
 */
class ChangesModel implements JsonSerializable {
	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string
	 */
	protected string $Modified;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string
	 */
	protected string $By;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var array
	 */
	protected array $Fields;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return string
	 */
	public function getModified() : string {
		return $this->Modified;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param string $Modified
	 * @return ChangesModel
	 */
	public function setModified(string $Modified ) : ChangesModel {
		$this->Modified = $Modified;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return string
	 */
	public function getBy() : string {
		return $this->By;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param string $By
	 * @return ChangesModel
	 */
	public function setBy(string $By ) : ChangesModel {
		$this->By = $By;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return array
	 */
	public function getFields() : array {
		return $this->Fields;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param array $Fields
	 * @return ChangesModel
	 */
	public function setFields(array $Fields ) : ChangesModel {
		$this->Fields = $Fields;
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
