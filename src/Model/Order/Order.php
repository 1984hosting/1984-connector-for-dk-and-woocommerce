<?php

declare(strict_types = 1);

namespace NineteenEightyFour\NineteenEightyWoo\Model\Order;

use JsonSerializable;
use NineteenEightyFour\NineteenEightyWoo\Model\Customer\Customer;
use NineteenEightyFour\NineteenEightyWoo\Model\Customer\ItemReciverModel;
use stdClass;

/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
 * The Order DTO from DK.
 */
class Order implements JsonSerializable {
	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var int $Number
	 */
	protected int $Number;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var Customer|null $Customer
	 */
	protected Customer|null $Customer;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string|null $CContact
	 */
	protected string|null $CContact;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var int $ID
	 */
	protected int $ID = 0;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string|null
	 */
	protected string|null $OrderDate;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string|null $CustomerOrderDate
	 */
	protected string|null $CustomerOrderDate;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string|null $CustomerOrderNumber
	 */
	protected string|null $CustomerOrderNumber;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var float|int $TotalAmount
	 */
	protected float $TotalAmount = 0;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string|null $Currency
	 */
	protected string|null $Currency;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string|null $Reference
	 */
	protected string|null $Reference;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var int $SettledType
	 */
	protected int $SettledType = 0;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var int
	 */
	protected int $SettledAmount = 0;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string|null $SalePerson
	 */
	protected string|null $SalePerson;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string|null
	 */
	protected string|null $Text1;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string|null $Text2
	 */
	protected string|null $Text2;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string|null $Dim1
	 */
	protected string|null $Dim1;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var int $Origin
	 */
	protected int $Origin = 0;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string|null $PaymentTerm
	 */
	protected string|null $PaymentTerm;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var int $ClaimStatus
	 */
	protected int $ClaimStatus = 0;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var int $Exchange
	 */
	protected int $Exchange = 0;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string|null $Status
	 */
	protected string|null $Status;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var string|null $
	 */
	protected string|null $DeliveryStatus;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var ItemReciverModel|null
	 */
	protected ItemReciverModel|null $DeliverTo;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @var array $OrderLines
	 */
	protected array $OrderLines;

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param stdClass $order
	 * @return void
	 */
	public function createOrderFromDKData( stdClass $order ) : void {
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

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return int
	 */
	public function getNumber() : int {
		return $this->Number;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param int $Number
	 * @return Order
	 */
	public function setNumber( int $Number ) : Order {
		$this->Number = $Number;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return Customer|null
	 */
	public function getCustomer() : ?Customer {
		return $this->Customer;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param Customer|stdClass $Customer
	 * @return void
	 */
	public function setCustomer( Customer|stdClass $Customer ) : void {
		if ( get_class( $Customer ) === 'Model\Customer\Customer' ) {
			$this->Customer = $Customer;
		} else {
			$this->Customer = new Customer();
			$this->Customer->createCustomerFromDKData( $Customer );
		}
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return string|null
	 */
	public function getCContact() : ?string {
		return $this->CContact;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param string|null $CContact
	 * @return Order
	 */
	public function setCContact( ?string $CContact ) : Order {
		$this->CContact = $CContact;
		return $this;
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
	 * @return Order
	 */
	public function setID( int $ID ) : Order {
		$this->ID = $ID;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return string|null
	 */
	public function getOrderDate() : ?string {
		return $this->OrderDate;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param string|null $OrderDate
	 * @return Order
	 */
	public function setOrderDate( ?string $OrderDate ) : Order {
		$this->OrderDate = $OrderDate;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return string|null
	 */
	public function getCustomerOrderDate() : ?string {
		return $this->CustomerOrderDate;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param string|null $CustomerOrderDate
	 * @return Order
	 */
	public function setCustomerOrderDate(?string $CustomerOrderDate ) : Order {
		$this->CustomerOrderDate = $CustomerOrderDate;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return string|null
	 */
	public function getCustomerOrderNumber() : ?string {
		return $this->CustomerOrderNumber;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param string|null $CustomerOrderNumber
	 * @return Order
	 */
	public function setCustomerOrderNumber(?string $CustomerOrderNumber ) : Order {
		$this->CustomerOrderNumber = $CustomerOrderNumber;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return float
	 */
	public function getTotalAmount() : float {
		return $this->TotalAmount;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param float $TotalAmount
	 * @return Order
	 */
	public function setTotalAmount(float $TotalAmount ) : Order {
		$this->TotalAmount = $TotalAmount;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return string|null
	 */
	public function getCurrency() : ?string {
		return $this->Currency;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param string|null $Currency
	 * @return Order
	 */
	public function setCurrency(?string $Currency ) : Order {
		$this->Currency = $Currency;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return string|null
	 */
	public function getReference() : ?string {
		return $this->Reference;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param string|null $Reference
	 * @return Order
	 */
	public function setReference(?string $Reference ) : Order {
		$this->Reference = $Reference;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return int
	 */
	public function getSettledType() : int {
		return $this->SettledType;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param int $SettledType
	 * @return Order
	 */
	public function setSettledType(int $SettledType ) : Order {
		$this->SettledType = $SettledType;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return int
	 */
	public function getSettledAmount() : int {
		return $this->SettledAmount;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param int $SettledAmount
	 * @return Order
	 */
	public function setSettledAmount(int $SettledAmount ) : Order {
		$this->SettledAmount = $SettledAmount;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return string|null
	 */
	public function getSalePerson() : ?string {
		return $this->SalePerson;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param string|null $SalePerson
	 * @return Order
	 */
	public function setSalePerson(?string $SalePerson ) : Order {
		$this->SalePerson = $SalePerson;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return string|null
	 */
	public function getText1() : ?string {
		return $this->Text1;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param string|null $Text1
	 * @return Order
	 */
	public function setText1(?string $Text1 ) : Order {
		$this->Text1 = $Text1;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return string|null
	 */
	public function getText2() : ?string {
		return $this->Text2;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param string|null $Text2
	 * @return Order
	 */
	public function setText2(?string $Text2 ) : Order {
		$this->Text2 = $Text2;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return string|null
	 */
	public function getDim1() : ?string {
		return $this->Dim1;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param string|null $Dim1
	 * @return Order
	 */
	public function setDim1(?string $Dim1 ) : Order {
		$this->Dim1 = $Dim1;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return int
	 */
	public function getOrigin() : int {
		return $this->Origin;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param int $Origin
	 * @return Order
	 */
	public function setOrigin(int $Origin ) : Order {
		$this->Origin = $Origin;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return string|null
	 */
	public function getPaymentTerm() : ?string {
		return $this->PaymentTerm;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param string|null $PaymentTerm
	 * @return Order
	 */
	public function setPaymentTerm(?string $PaymentTerm ) : Order {
		$this->PaymentTerm = $PaymentTerm;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return int
	 */
	public function getClaimStatus() : int {
		return $this->ClaimStatus;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param int $ClaimStatus
	 * @return Order
	 */
	public function setClaimStatus(int $ClaimStatus ) : Order {
		$this->ClaimStatus = $ClaimStatus;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return int
	 */
	public function getExchange() : int {
		return $this->Exchange;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param int $Exchange
	 * @return Order
	 */
	public function setExchange(int $Exchange ) : Order {
		$this->Exchange = $Exchange;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return string|null
	 */
	public function getStatus() : ?string {
		return $this->Status;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param string|null $Status
	 * @return Order
	 */
	public function setStatus(?string $Status ) : Order {
		$this->Status = $Status;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return string|null
	 */
	public function getDeliveryStatus() : ?string {
		return $this->DeliveryStatus;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param string|null $DeliveryStatus
	 * @return Order
	 */
	public function setDeliveryStatus(?string $DeliveryStatus ) : Order {
		$this->DeliveryStatus = $DeliveryStatus;
		return $this;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return ItemReciverModel|null
	 */
	public function getDeliverTo() : ?ItemReciverModel {
		return $this->DeliverTo;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param ItemReciverModel|stdClass|null $DeliverTo
	 * @return Order
	 */
	public function setDeliverTo(ItemReciverModel|stdClass|null $DeliverTo ) : Order {
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

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @return array
	 */
	public function getOrderLines() : array {
		return $this->OrderLines;
	}

	/**
	 * Short description (CS requirement, unnecessary for DTO Class)
	 *
	 * @param array $OrderLines
	 * @return Order
	 */
	public function setOrderLines(array $OrderLines ) : Order {
		$arr = [];
		foreach ( $OrderLines as $line ) {
			$order_line = new OrderLine();
			$order_line->createOrderLineFromDKData( $line );
			$arr[] = $order_line;
		}
		$this->OrderLines = $arr;

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
