<?php

declare(strict_types = 1);

namespace NineteenEightyFour\NineteenEightyWoo\Model\Mail;

use JsonSerializable;
use stdClass;

class MailInfo implements JsonSerializable {
	/**
	 * @var string|null $DisplayName
	 */
	protected ?string $DisplayName;

	/**
	 * @var string|null $To
	 */
	protected ?string $To;

	/**
	 * @var string|null $CC
	 */
	protected ?string $CC;

	/**
	 * @var string|null $BCC
	 */
	protected ?string $BCC;

	/**
	 * @var string|null $Subject
	 */
	protected ?string $Subject;

	/**
	 * @var string|null $Body
	 */
	protected ?string $Body;

	/**
	 * @var string|null $Priority
	 */
	protected ?string $Priority;

	/**
	 * @var string|array|null $Attachments
	 */
	protected string|array|null $Attachments;

	/**
	 * @var bool $HasRecipients
	 */
	protected bool $HasRecipients;

	/**
	 * @param stdClass $mail_info
	 * @return $this
	 */
	public function createMailInfoFromDKData( stdClass $mail_info ) : MailInfo {
		$this->setDisplayName( $mail_info->DisplayName ?? null );
		$this->setTo( $mail_info->To ?? null );
		$this->setCC( $mail_info->CC ?? null );
		$this->setBCC( $mail_info->BCC ?? null );
		$this->setSubject( $mail_info->Subject ?? null );
		$this->setBody( $mail_info->Body ?? null );
		$this->setPriority( $mail_info->Priority ?? null );
		$this->setAttachments( $mail_info->Attachments ?? null );
		$this->setHasRecipients( $mail_info->HasRecipients ?? false );
		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getDisplayName() : ?string {
		return $this->DisplayName;
	}

	/**
	 * @param string|null $DisplayName
	 * @return $this
	 */
	public function setDisplayName( ?string $DisplayName ) : MailInfo {
		$this->DisplayName = $DisplayName;
		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getTo() : ?string {
		return $this->To;
	}

	/**
	 * @param string|null $To
	 * @return MailInfo
	 */
	public function setTo( ?string $To ) : MailInfo {
		$this->To = $To;
		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getCC() : ?string {
		return $this->CC;
	}

	/**
	 * @param string|null $CC
	 * @return $this
	 */
	public function setCC( ?string $CC ) : MailInfo {
		$this->CC = $CC;
		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getBCC() : ?string {
		return $this->BCC;
	}

	/**
	 * @param string|null $BCC
	 * @return $this
	 */
	public function setBCC( ?string $BCC ) : MailInfo {
		$this->BCC = $BCC;
		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getSubject() : ?string {
		return $this->Subject;
	}

	/**
	 * @param string|null $Subject
	 * @return $this
	 */
	public function setSubject( ?string $Subject ) : MailInfo {
		$this->Subject = $Subject;
		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getBody() : ?string {
		return $this->Body;
	}

	/**
	 * @param string|null $Body
	 * @return $this
	 */
	public function setBody( ?string $Body ) : MailInfo {
		$this->Body = $Body;
		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getPriority() : ?string {
		return $this->Priority;
	}

	/**
	 * @param string|null $Priority
	 * @return $this
	 */
	public function setPriority( ?string $Priority ) : MailInfo {
		$this->Priority = $Priority;
		return $this;
	}

	/**
	 * @return array|string|null
	 */
	public function getAttachments() : array|string|null {
		return $this->Attachments;
	}

	/**
	 * @param array|string|null $Attachments
	 * @return $this
	 */
	public function setAttachments( array|string|null $Attachments ) : MailInfo {
		$this->Attachments = $Attachments;
		return $this;
	}

	/**
	 * @return bool
	 */
	public function isHasRecipients() : bool {
		return $this->HasRecipients;
	}

	/**
	 * @param bool $HasRecipients
	 * @return $this
	 */
	public function setHasRecipients( bool $HasRecipients ) : MailInfo {
		$this->HasRecipients = $HasRecipients;
		return $this;
	}

	/**
	 * @return string
	 */
	public function jsonSerialize() : string {
		return json_encode( get_object_vars( $this ) );
	}
}
