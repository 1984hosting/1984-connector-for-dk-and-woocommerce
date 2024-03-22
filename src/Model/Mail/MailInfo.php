<?php

declare(strict_types = 1);

namespace NineteenEightyFour\NineteenEightyWoo\Model\Mail;

use JsonSerializable;
use stdClass;

/**
 * The MailInfo DTO class for DK
 */
class MailInfo implements JsonSerializable {
	protected ?string $DisplayName;

	protected ?string $To;

	protected ?string $CC;

	protected ?string $BCC;

	protected ?string $Subject;

	protected ?string $Body;

	protected ?string $Priority;

	protected string|array|null $Attachments;

	protected bool $HasRecipients;

	/**
	 * @return $this
	 */
	public function createMailInfoFromDKData( stdClass $mail_info ): MailInfo {
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

	public function getDisplayName(): ?string {
		return $this->DisplayName;
	}

	/**
	 * @return $this
	 */
	public function setDisplayName( ?string $DisplayName ): MailInfo {
		$this->DisplayName = $DisplayName;
		return $this;
	}

	public function getTo(): ?string {
		return $this->To;
	}

	public function setTo( ?string $To ): MailInfo {
		$this->To = $To;
		return $this;
	}

	public function getCC(): ?string {
		return $this->CC;
	}

	/**
	 * @return $this
	 */
	public function setCC( ?string $CC ): MailInfo {
		$this->CC = $CC;
		return $this;
	}

	public function getBCC(): ?string {
		return $this->BCC;
	}

	/**
	 * @return $this
	 */
	public function setBCC( ?string $BCC ): MailInfo {
		$this->BCC = $BCC;
		return $this;
	}

	public function getSubject(): ?string {
		return $this->Subject;
	}

	/**
	 * @return $this
	 */
	public function setSubject( ?string $Subject ): MailInfo {
		$this->Subject = $Subject;
		return $this;
	}

	public function getBody(): ?string {
		return $this->Body;
	}

	/**
	 * @return $this
	 */
	public function setBody( ?string $Body ): MailInfo {
		$this->Body = $Body;
		return $this;
	}

	public function getPriority(): ?string {
		return $this->Priority;
	}

	/**
	 * @return $this
	 */
	public function setPriority( ?string $Priority ): MailInfo {
		$this->Priority = $Priority;
		return $this;
	}

	public function getAttachments(): array|string|null {
		return $this->Attachments;
	}

	/**
	 * @return $this
	 */
	public function setAttachments( array|string|null $Attachments ): MailInfo {
		$this->Attachments = $Attachments;
		return $this;
	}

	public function isHasRecipients(): bool {
		return $this->HasRecipients;
	}

	/**
	 * @return $this
	 */
	public function setHasRecipients( bool $HasRecipients ): MailInfo {
		$this->HasRecipients = $HasRecipients;
		return $this;
	}

	public function jsonSerialize(): string {
		return json_encode( get_object_vars( $this ) );
	}
}
