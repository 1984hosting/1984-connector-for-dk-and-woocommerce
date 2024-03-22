<?php

declare(strict_types = 1);

namespace NineteenEightyFour\NineteenEightyWoo\Model\Order;

use JsonSerializable;
use NineteenEightyFour\NineteenEightyWoo\Model\Customer\Customer;
use NineteenEightyFour\NineteenEightyWoo\Model\Customer\ItemReciverModel;
use stdClass;

/**
 * The Order DTO from DK.
 */
class Order implements JsonSerializable {
	/**
	 * @var int $Number
	 */
	protected int $Number;

	/**
	 * @var Customer|null $Customer
	 */
	protected Customer|null $Customer;

	/**
	 * @var string|null $CContact
	 */
	protected string|null $CContact;

	/**
	 * @var int $ID
	 */
	protected int $ID = 0;

	/**
	 * @var string|null
	 */
	protected string|null $OrderDate;

	/**
	 * @var string|null $CustomerOrderDate
	 */
	protected string|null $CustomerOrderDate;

	/**
	 * @var string|null $CustomerOrderNumber
	 */
	protected string|null $CustomerOrderNumber;

	/**
	 * @var float|int $TotalAmount
	 */
	protected float $TotalAmount = 0;

	/**
	 * @var string|null $Currency
	 */
	protected string|null $Currency;

	/**
	 * @var string|null $Reference
	 */
	protected string|null $Reference;

	/**
	 * @var int $SettledType
	 */
	protected int $SettledType = 0;

	/**
	 * @var int
	 */
	protected int $SettledAmount = 0;

	/**
	 * @var string|null $SalePerson
	 */
	protected string|null $SalePerson;

	/**
	 * @var string|null
	 */
	protected string|null $Text1;

	/**
	 * @var string|null $Text2
	 */
	protected string|null $Text2;

	/**
	 * @var string|null $Dim1
	 */
	protected string|null $Dim1;

	/**
	 * @var int $Origin
	 */
	protected int $Origin = 0;

	/**
	 * @var string|null $PaymentTerm
	 */
	protected string|null $PaymentTerm;

	/**
	 * @var int $ClaimStatus
	 */
	protected int $ClaimStatus = 0;

	/**
	 * @var int $Exchange
	 */
	protected int $Exchange = 0;

	/**
	 * @var string|null $Status
	 */
	protected string|null $Status;

	/**
	 * @var string|null $
	 */
	protected string|null $DeliveryStatus;

	/**
	 * @var ItemReciverModel|null
	 */
	protected ItemReciverModel|null $DeliverTo;

	/**
	 * @var array $OrderLines
	 */
	protected array $OrderLines;

	public function createOrderFromDKData( stdClass $order ): void {
		$this->setNumber( $order->Number );
		$this->setCustomer( $order->Customer );
		$this->setCContact( $order->CContact ?? null );
		$this->setID( $order->ID ?? 0 );
		$this->setOrderDate( $order->OrderDate ?? null );
		$this->setCustomerOrderDate( $order->CustomerOrderDate ?? null );
		$this->setCustomerOrderNumber( $order->CustomerOrderNumber ?? null );
		$this->setTotalAmount( $order->TotalAmount ?? 0 );
		$this->setCurrency( $order->Currency ?? null );
		$this->setReference( $order->reference ?? null );
		$this->setSettledType( $order->SettledType ?? 0 );
		$this->setSettledAmount( $order->SettledAmount ?? 0 );
		$this->setSalePerson( $order->SalePerson ?? null );
		$this->setText1( $order->Text1 ?? null );
		$this->setText2( $order->Text2 ?? null );
		$this->setDim1( $order->Dim1 ?? null );
		$this->setOrigin( $order->Origin ?? 0 );
		$this->setPaymentTerm( $order->PaymentTerm ?? null );
		$this->setClaimStatus( $order->ClaimStatus ?? 0 );
		$this->setExchange( $order->Exchange ?? 0 );
		$this->setStatus( $order->Status ?? null );
		$this->setDeliveryStatus( $order->DeliveryStatus ?? null );
		$this->setDeliverTo( $order->DeliverTo ?? null );
		$this->setOrderLines( $order->Lines ?? [] );
	}

	public function getNumber(): int {
		return $this->Number;
	}

	public function setNumber( int $Number ): Order {
		$this->Number = $Number;
		return $this;
	}

	public function getCustomer(): ?Customer {
		return $this->Customer;
	}

	public function setCustomer( Customer|stdClass $Customer ): void {
		if ( get_class( $Customer ) === 'Model\Customer\Customer' ) {
			$this->Customer = $Customer;
		} else {
			$this->Customer = new Customer();
			$this->Customer->createCustomerFromDKData( $Customer );
		}
	}

	public function getCContact(): ?string {
		return $this->CContact;
	}

	public function setCContact( ?string $CContact ): Order {
		$this->CContact = $CContact;
		return $this;
	}

	public function getID(): int {
		return $this->ID;
	}

	public function setID( int $ID ): Order {
		$this->ID = $ID;
		return $this;
	}

	public function getOrderDate(): ?string {
		return $this->OrderDate;
	}

	public function setOrderDate( ?string $OrderDate ): Order {
		$this->OrderDate = $OrderDate;
		return $this;
	}

	public function getCustomerOrderDate(): ?string {
		return $this->CustomerOrderDate;
	}

	public function setCustomerOrderDate( ?string $CustomerOrderDate ): Order {
		$this->CustomerOrderDate = $CustomerOrderDate;
		return $this;
	}

	public function getCustomerOrderNumber(): ?string {
		return $this->CustomerOrderNumber;
	}

	public function setCustomerOrderNumber( ?string $CustomerOrderNumber ): Order {
		$this->CustomerOrderNumber = $CustomerOrderNumber;
		return $this;
	}

	public function getTotalAmount(): float {
		return $this->TotalAmount;
	}

	public function setTotalAmount( float $TotalAmount ): Order {
		$this->TotalAmount = $TotalAmount;
		return $this;
	}

	public function getCurrency(): ?string {
		return $this->Currency;
	}

	public function setCurrency( ?string $Currency ): Order {
		$this->Currency = $Currency;
		return $this;
	}

	public function getReference(): ?string {
		return $this->Reference;
	}

	public function setReference( ?string $Reference ): Order {
		$this->Reference = $Reference;
		return $this;
	}

	public function getSettledType(): int {
		return $this->SettledType;
	}

	public function setSettledType( int $SettledType ): Order {
		$this->SettledType = $SettledType;
		return $this;
	}

	public function getSettledAmount(): int {
		return $this->SettledAmount;
	}

	public function setSettledAmount( int $SettledAmount ): Order {
		$this->SettledAmount = $SettledAmount;
		return $this;
	}

	public function getSalePerson(): ?string {
		return $this->SalePerson;
	}

	public function setSalePerson( ?string $SalePerson ): Order {
		$this->SalePerson = $SalePerson;
		return $this;
	}

	public function getText1(): ?string {
		return $this->Text1;
	}

	public function setText1( ?string $Text1 ): Order {
		$this->Text1 = $Text1;
		return $this;
	}

	public function getText2(): ?string {
		return $this->Text2;
	}

	public function setText2( ?string $Text2 ): Order {
		$this->Text2 = $Text2;
		return $this;
	}

	public function getDim1(): ?string {
		return $this->Dim1;
	}

	public function setDim1( ?string $Dim1 ): Order {
		$this->Dim1 = $Dim1;
		return $this;
	}

	public function getOrigin(): int {
		return $this->Origin;
	}

	public function setOrigin( int $Origin ): Order {
		$this->Origin = $Origin;
		return $this;
	}

	public function getPaymentTerm(): ?string {
		return $this->PaymentTerm;
	}

	public function setPaymentTerm( ?string $PaymentTerm ): Order {
		$this->PaymentTerm = $PaymentTerm;
		return $this;
	}

	public function getClaimStatus(): int {
		return $this->ClaimStatus;
	}

	public function setClaimStatus( int $ClaimStatus ): Order {
		$this->ClaimStatus = $ClaimStatus;
		return $this;
	}

	public function getExchange(): int {
		return $this->Exchange;
	}

	public function setExchange( int $Exchange ): Order {
		$this->Exchange = $Exchange;
		return $this;
	}

	public function getStatus(): ?string {
		return $this->Status;
	}

	public function setStatus( ?string $Status ): Order {
		$this->Status = $Status;
		return $this;
	}

	public function getDeliveryStatus(): ?string {
		return $this->DeliveryStatus;
	}

	public function setDeliveryStatus( ?string $DeliveryStatus ): Order {
		$this->DeliveryStatus = $DeliveryStatus;
		return $this;
	}

	public function getDeliverTo(): ?ItemReciverModel {
		return $this->DeliverTo;
	}

	public function setDeliverTo( ItemReciverModel|stdClass|null $DeliverTo ): Order {
		if ( is_null( $DeliverTo ) ) {
			$this->DeliverTo = null;
			return $this;
		} elseif ( get_class( $DeliverTo ) === 'Model\Customer\ItemReciverModel' ) {
			$this->DeliverTo = $DeliverTo;
		} else {
			$this->DeliverTo = new ItemReciverModel();
			$this->DeliverTo->createItemReciverModelFromDKData( $DeliverTo );
		}

		return $this;
	}

	public function getOrderLines(): array {
		return $this->OrderLines;
	}

	public function setOrderLines( array $OrderLines ): Order {
		$arr = [];
		foreach ( $OrderLines as $line ) {
			$order_line = new OrderLine();
			$order_line->createOrderLineFromDKData( $line );
			$arr[] = $order_line;
		}
		$this->OrderLines = $arr;

		return $this;
	}

	public function jsonSerialize(): string {
		return json_encode( get_object_vars( $this ) );
	}
}
