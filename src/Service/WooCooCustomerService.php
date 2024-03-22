<?php

declare(strict_types = 1);

namespace NineteenEightyFour\NineteenEightyWoo\Service;

use Model\Customer\Customer;
use Model\Customer\CustomerGroup;
use Model\Customer\ItemReciverModel;
use Model\Invoice\Invoice;
use Model\Order\Order;
use Service\Exception\WooCooServiceException;
use stdClass;

/**
 * Class that implements everything for the Customer part of the DK API.
 */
class WooCooCustomerService {
	protected DkApiService $apiService;

	/**
	 * The Default constructor fetches the DK Api Service. Dependency injection is
	 * not needed since it requires it at all times.
	 */
	public function __construct() {
		$this->apiService = new DkApiService();
	}

	/**
	 * Gets all Customers from DK.
	 *
	 * @param bool $include_objects This boolean variable doesn't do much, the diff is almost the same.
	 * @throws WooCooServiceException
	 */
	public function getAllCustomers( bool $include_objects = false ): array {
		$new_data     = [];
		$data_from_dk = $this->handleDkResponse(
			$this->apiService->getCustomers( $include_objects ),
			'Error occurred while fetching all customers.'
		);

		foreach ( $data_from_dk as $data ) {
			$customer = new Customer();
			$customer->createCustomerFromDKData( $data );
			$new_data[ $customer->getRecordID() ] = $customer;
		}

		return $new_data;
	}

	/**
	 * Deletes a customer (UNIMPLEMENTED)
	 */
	public function deleteCustomer( string $id ): array|stdClass|null {
		// @TODO: implement this deleteCustomer($id) function.
		return null;
	}

	/**
	 * Gets one customer by their ID, normally SSN.
	 *
	 * @throws WooCooServiceException
	 */
	public function getCustomerById( string $id ): Customer|null {
		$data_from_dk = $this->handleDkResponse(
			$this->apiService->getCustomerById( $id ),
			'Error occurred while fetching customer with ID: ' . $id
		);

		if ( is_object( $data_from_dk ) ) {
			$customer = new Customer();
			$customer->createCustomerFromDKData( $data_from_dk );
			return $customer;
		} else {
			// @TODO: How do we want to deal with "Customer not found"?
			return null;
		}
	}

	/**
	 * Updates a customer based on the data sent.
	 *
	 * @param string $data Json Encoded string.
	 * @throws WooCooServiceException
	 */
	public function updateCustomer( string $id, Customer $customer ): stdClass|null {
		$data_from_dk = $this->handleDkResponse(
			$this->apiService->updateCustomer( $id, $customer->jsonSerialize() ),
			'Error occurred while updating customer.'
		);

		// @TODO: Decide what to return from this function, because DK only returns "".
		return null;
	}

	/**
	 * Gets all transactions for customer (UNIMPLEMENTED)
	 */
	public function getTransactionsForCustomer( string $id ): array {
		// @TODO: Implement this getTransactionsForCustomer($id) function.
	}

	/**
	 * Gets information about a customers card (UNIMPLEMENTED).
	 */
	public function getCustomerCardInformation( string $id ): void {
		// @TODO: Implement this getCustomerCardInformation($id) function.
	}

	/**
	 * Updates card information for customer (UNIMPLEMENTED).
	 */
	public function updateCustomerCardInformation( string $id, string $data ): void {
		// @TODO: Implement this updateCustomerCardInformation($id, $data) function.
	}

	/**
	 * Gets all Orders for a Customer
	 *
	 * @return array of Orders
	 * @throws WooCooServiceException
	 */
	public function getOrdersForCustomer( string $id ): array {
		$data_from_dk = $this->handleDkResponse(
			$this->apiService->getOrdersForCustomer( $id ),
			'Error occurred when fetching orders for customer ' . $id
		);

		$orders = [];
		foreach ( $data_from_dk as $order ) {
			$order_object = new Order();
			$order_object->createOrderFromDKData( $order );
			$orders[] = $order_object;
		}

		return $orders;
	}

	/**
	 * Gets all quotes for one customer (UNIMPLEMENTED).
	 */
	public function getQuotesForCustomer( string $id ): void {
		// @TODO: Implement this getQuotesForCustomer($id) function.
	}

	/**
	 * Gets all invoices for one customer
	 *
	 * @return array of Invoice data
	 * @throws WooCooServiceException
	 */
	public function getInvoicesForCustomer( string $id ): array {
		$data_from_dk = $this->handleDkResponse(
			$this->apiService->getInvoicesForCustomer( $id ),
			'Error occurred when fetching invoices for customer ' . $id
		);

		$return_arr = [];
		foreach ( $data_from_dk as $data ) {
			$invoice = new Invoice();
			$invoice->createInvoiceFromDKData( $data );
			$return_arr[] = $invoice;
		}

		return $return_arr;
	}

	/**
	 * Gets all projects for a customer (UNIMPLEMENTED).
	 */
	public function getProjectsForCustomer( string $id ): void {
		// @TODO: Implement this getProjectesForCustomer($id) function.
	}

	/**
	 * Finds a customer based on a filter (UNIMPLEMENTED).
	 */
	public function getCustomerBasedOnFilter(): void {
		// @TODO: Implement this getCustomerBasedOnFilter function.
		// This API Call is like a search function for customers. Since web stores
		// are always working with an individual, I didn't see a reason to implement
		// it now.
	}

	/**
	 * Gets a count of customers that have changed by certain conditions (UNIMPLEMENTED).
	 */
	public function getCountOfCustomersThatHaveChangedByConditions(): void {
		// @TODO: Implement this getCountOfCustomersThatHaveChangedByConditions function.
	}

	/**
	 * Gets all customers that belong to certain customer group (UNIMPLEMENTED).
	 */
	public function getCustomersByGroup(): void {
		// @TODO: Implement this getCustomersByGroup function.
	}

	/**
	 * Gets a customer by their phone number (searches in all phone number fields) (UNIMPLEMENTED).
	 */
	public function getCustomerByPhoneNumber(): void {
		// @TODO: Implement this getCustomerByPhoneNumber function.
	}

	/**
	 * Generates a Phone Caller ID by their phone number (UNIMPLEMENTED).
	 */
	public function generatePhoneCallerIDByPhoneNumber(): void {
		// @TODO: Implement this generatePhoneCallerIDByPhoneNumber function.
	}

	/**
	 * Creates a Customer in the DK system.
	 *
	 * @throws WooCooServiceException
	 */
	public function createCustomer( string $data ): null {
		$data_from_dk = $this->handleDkResponse(
			$this->apiService->createCustomer( $data ),
			'Error occurred when creating customer.'
		);

		// @TODO: Decide who to notify a creation of a Customer, since DK only returns
		// Status 200 and a null body.
		return null;
	}

	/**
	 * Gets all documents that are assigned to a customer (UNIMPLEMENTED).
	 */
	public function getDocumentAssignedToCustomer( string $customer_number, int $document_id ): void {
		// @TODO: Implement this getDocumentAssignedToCustomer($customer_number, $document_id) function.
	}

	/**
	 * Get all receivers that are assigned to a Customer.
	 *
	 * @throws WooCooServiceException
	 */
	public function getReciversAssignedToCustomer( string $customer_number ): array|null {
		$data_from_dk = $this->handleDkResponse(
			$this->apiService->getReciversAssignedToCustomer( $customer_number ),
			'Error occurred while getting receivers for customer ' . $customer_number
		);

		if ( count( $data_from_dk ) > 0 ) {
			$receivers = [];
			foreach ( $data_from_dk as $receiver_data ) {
				$Receiver = new ItemReciverModel();
				$Receiver->createItemReciverModelFromDKData( $receiver_data );
				$receivers[] = $Receiver;
			}

			return $receivers;
		} else {
			return null;
		}
	}

	/**
	 * Gets a contact by id from a customer (by id also).
	 *
	 * @throws WooCooServiceException
	 */
	public function getContactAssignedToCustomer( string $customer_number, string $contact_number ): ItemReciverModel|null {
		$data_from_dk = $this->handleDkResponse(
			$this->apiService->getContactAssignedToCustomer( $customer_number, $contact_number ),
			'Error occurred while getting receiver ' . $contact_number . ' for customer ' . $customer_number
		);

		if ( is_object( $data_from_dk ) ) {
			$ItemReciver = new ItemReciverModel();
			$ItemReciver->createItemReciverModelFromDKData( $data_from_dk );
			return $ItemReciver;
		}

		return null;
	}

	/**
	 * @throws WooCooServiceException
	 */
	public function createReciver( string $customer_id, ItemReciverModel $receiver ): void {
		$data_from_dk = $this->handleDkResponse(
			$this->apiService->createReciver( $customer_id, $receiver->jsonSerialize() ),
			'Error occurred when creating a receiver'
		);

		// @TODO: Decide who to notify a creation of a Receiver, since DK only returns
		// Status 200 and a null body.
	}

	/**
	 * Deletes a contact.
	 *
	 * @throws WooCooServiceException
	 */
	public function deleteContact( string $customer_id, string $receiver_id ): void {
		$data_from_dk = $this->handleDkResponse(
			$this->apiService->deleteContactAssignedToCustomer( $customer_id, $receiver_id ),
			'Error occurred when deleting receiver ' . $receiver_id . ' for customer ' . $customer_id
		);

		// @TODO: Decide who to notify a deletion of a Receiver, since DK only returns
		// Status 200 and a null body.
	}

	/**
	 * Updates a Contact.
	 *
	 * @throws WooCooServiceException
	 */
	public function updateContact( string $customer_id, string $receiver_id, ItemReciverModel $receiver ): void {
		$data_from_dk = $this->handleDkResponse(
			$this->apiService->updateContactAssignedToCustomer( $customer_id, $receiver_id, $receiver->jsonSerialize() ),
			'Error occurred when updating contact ' . $receiver_id . ' for customer ' . $customer_id
		);

		// @TODO: Decide who to notify updates of a Receiver, since DK only returns
		// Status 200 and a null body.
	}

	/**
	 * Gets all customer groups. Can have optional parameter Modified after.
	 *
	 * @param string|null $modified_after
	 * @throws WooCooServiceException
	 */
	public function getCustomerGroups( string $modified_after = null ): array|null {
		$data_from_dk = $this->handleDkResponse(
			$this->apiService->getCustomerGroups( $modified_after ),
			'Error occurred when getting customer groups.'
		);

		if ( ! is_null( $data_from_dk ) ) {
			$customer_groups = [];
			foreach ( $data_from_dk as $data ) {
				$customer_group = new CustomerGroup();
				$customer_group->createCustomerGroupFromDKData( $data );
				$customer_groups[] = $customer_group;
			}

			return $customer_groups;
		} else {
			return null;
		}
	}

	/**
	 * Gets general ledger transaction (UNIMPLEMENTED).
	 */
	public function getGeneralLedgerTransaction(): void {
		// @TODO: Implement this getGeneralLedgerTransaction() function.
	}

	/**
	 * Gets receiver by their number.
	 * The dataset returns an array of data, but I'm not sure if that's ever going to be larger than one?!?
	 *
	 * @throws WooCooServiceException
	 */
	public function getItemReciverByNumber( string $number ): array|null {
		$data_from_dk = $this->handleDkResponse(
			$this->apiService->getItemReciverByNumber( $number ),
			'Error occurred while getting item receiver by number ' . $number
		);

		$ItemReciverArray = [];
		if ( is_array( $data_from_dk ) ) {
			foreach ( $data_from_dk as $data ) {
				$item_reciver = new ItemReciverModel();
				$item_reciver->createItemReciverModelFromDKData( $data );
				$ItemReciverArray[] = $item_reciver;
			}
		} else {
			return null;
		}

		return $ItemReciverArray;
	}

	/**
	 * Gets customers that a receiv
	 *
	 * @throws WooCooServiceException
	 */
	public function getReciverCustomerByReciverNumber( string $number ): array|null {
		$data_from_dk = $this->handleDkResponse(
			$this->apiService->getReciverCustomerByReciverNumber( $number ),
			'Error occurred while getting receiver customer by receiver number ' . $number
		);

		$customers = [];
		if ( is_array( $data_from_dk ) ) {
			foreach ( $data_from_dk as $data ) {
				$customer = new Customer();
				$customer->createCustomerFromDKData( $data );
				$customers[] = $customer;
			}
		} else {
			return null;
		}

		return $customers;
	}

	/**
	 * Helper function to handle repetitive lines of code regarding the response from the DK API.
	 *
	 * @throws WooCooServiceException
	 */
	private function handleDkResponse( $response, $message ): array|stdClass|null {
		if ( $response['response_code'] === 200 ) {
			return $response['data'];
		}
		// Deal with Error occurred.
		elseif ( $response['response_code'] === 400 ) {
			throw new WooCooServiceException( $message, $response['response_code'] );
		}
		// Deal with Un-Authorized call
		elseif ( $response['response_code'] === 401 ) {
			throw new WooCooServiceException(
				'Un-Authorised request. Please check your API key.',
				$response['response_code']
			);
		} elseif ( $response['response_code'] === 404 ) {
			throw new WooCooServiceException(
				'Data not found',
				$response['response_code']
			);
		}
		return null;
	}
}
