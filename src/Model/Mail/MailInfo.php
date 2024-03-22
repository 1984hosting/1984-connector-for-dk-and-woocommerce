<?php

declare(strict_types = 1);

namespace NineteenEightyFour\NineteenEightyWoo\Model\Mail;

use JsonSerializable;
use stdClass;

/**
 * The MailInfo DTO class for DK
 */
class MailInfo implements JsonSerializable {
	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string|null $DisplayName
	 */
	protected ?string $DisplayName;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string|null $To
	 */
	protected ?string $To;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string|null $CC
	 */
	protected ?string $CC;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string|null $BCC
	 */
	protected ?string $BCC;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string|null $Subject
	 */
	protected ?string $Subject;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string|null $Body
	 */
	protected ?string $Body;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string|null $Priority
	 */
	protected ?string $Priority;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string|array|null $Attachments
	 */
	protected string|array|null $Attachments;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var bool $HasRecipients
	 */
	protected bool $HasRecipients;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
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

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function getDisplayName(): ?string {
		return $this->DisplayName;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return $this
	 */
	public function setDisplayName( ?string $DisplayName ): MailInfo {
		$this->DisplayName = $DisplayName;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function getTo(): ?string {
		return $this->To;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function setTo( ?string $To ): MailInfo {
		$this->To = $To;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function getCC(): ?string {
		return $this->CC;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return $this
	 */
	public function setCC( ?string $CC ): MailInfo {
		$this->CC = $CC;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function getBCC(): ?string {
		return $this->BCC;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return $this
	 */
	public function setBCC( ?string $BCC ): MailInfo {
		$this->BCC = $BCC;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function getSubject(): ?string {
		return $this->Subject;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return $this
	 */
	public function setSubject( ?string $Subject ): MailInfo {
		$this->Subject = $Subject;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function getBody(): ?string {
		return $this->Body;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return $this
	 */
	public function setBody( ?string $Body ): MailInfo {
		$this->Body = $Body;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function getPriority(): ?string {
		return $this->Priority;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return $this
	 */
	public function setPriority( ?string $Priority ): MailInfo {
		$this->Priority = $Priority;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function getAttachments(): array|string|null {
		return $this->Attachments;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return $this
	 */
	public function setAttachments( array|string|null $Attachments ): MailInfo {
		$this->Attachments = $Attachments;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function isHasRecipients(): bool {
		return $this->HasRecipients;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return $this
	 */
	public function setHasRecipients( bool $HasRecipients ): MailInfo {
		$this->HasRecipients = $HasRecipients;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 */
	public function jsonSerialize(): string {
		return json_encode( get_object_vars( $this ) );
	}
}
