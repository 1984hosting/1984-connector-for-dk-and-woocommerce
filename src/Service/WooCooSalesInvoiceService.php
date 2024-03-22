<?php

declare(strict_types = 1);

namespace NineteenEightyFour\NineteenEightyWoo\Service;

use Mail\MailInfo;
use Model\Customer\Customer;
use Model\Invoice\Invoice;
use Model\Order\Order;
use Service\Exception\WooCooServiceException;
use stdClass;

class WooCooSalesInvoiceService {
	protected DkApiService $apiService;

	/**
	 * The Default constructor fetches the DK Api Service. Dependency injection is
	 * not needed since it requires it at all times.
	 */
	public function __construct() {
		$this->apiService = new DkApiService();
	}

	/**
	 * Gets a PDF version of an invoice as a file stream string.
	 * Right now, we're saving the file onto the file system, but that's not a sustainable solution. Although, we're
	 * creating some kind of UUID string for the file name, but the access to the save folder has to be very strict.
	 *
	 * @TODO: Find a better solution of how to return the file to the requester.
	 *
	 * @throws WooCooServiceException
	 */
	public function getPDFVersionOfInvoice( string $number ): string {
		$data_from_dk = $this->handleDkResponse(
			$this->apiService->getPDFVersionOfInvoice( $number ),
			'Error occurred when getting PDF version of an invoice # ' . $number
		);

		$uuid = $this->uuid4();
		file_put_contents( $uuid . '.pdf', $data_from_dk );

		return $data_from_dk;
	}

	/**
	 * Gets a HTML version of an invoice and returns it as a string.
	 *
	 * @return string HTML string with the invoice.
	 * @throws WooCooServiceException
	 */
	public function getHTMLVersionOfInvoice( string $number ): string {
		$data_from_dk = $this->handleDkResponse(
			$this->apiService->getHTMLVersionOfInvoice( $number ),
			'Error occurred when getting HTML version of an invoice # ' . $number
		);

		return $data_from_dk;
	}

	/**
	 * Sends an invoice as Email (through the DK system!)
	 *
	 * @throws WooCooServiceException
	 */
	public function sendInvoiceAsEmail( string $invoice_number, MailInfo $email_message ): void {
		$this->handleDkResponse(
			$this->apiService->sendInvoiceAsEmail( $invoice_number, $email_message->jsonSerialize() ),
			'Error occurred when sending invoice # ' . $invoice_number . ' as an email.'
		);
	}

	public function calculatePricesBasedOnProductCustomerAndDiscountGroups(): void {
		// @TODO: Implement this calculatePricesBasedOnProductCustomerAndDiscountGroups() function.
	}

	public function getDefinedPriceRules(): void {
		// @TODO: Implement this getDefinedPriceRules() function.
	}

	public function createCreditInvoiceBasedOnAnotherInvoice(): void {
		// @TODO: Implement this createCreditInvoiceBasedOnAnotherInvoice() function.
	}

	/**
	 * Gets Sales Invoices, paginated. User needs to keep tally of how many invoices are actually within each
	 * response and stop when the response is empty.
	 *
	 * @param int         $page What page are you calling?
	 * @param int         $count How many invoices pr. dataset.
	 * @param string      $sorttype Ascending or Descending
	 * @param bool|null   $includeLines
	 * @param string|null $createdAfter
	 * @param string|null $createdBefore
	 * @param string|null $modifiedAfter
	 * @param string|null $modifiedBefore
	 * @param string|null $dueAfter
	 * @param string|null $salesPerson
	 * @param string|null $reference
	 * @param int|null    $recordid
	 * @param string|null $customer
	 * @param string|null $project
	 * @param string|null $itemcode
	 * @param string|null $include
	 * @param string|null $exclude
	 * @param string|null $sort
	 * @return array
	 * @throws WooCooServiceException
	 */
	public function getSaleInvoices(
		int $page,
		int $count,
		string $sorttype = 'Ascending',
		bool $includeLines = null,
		string $createdAfter = null,
		string $createdBefore = null,
		string $modifiedAfter = null,
		string $modifiedBefore = null,
		string $dueAfter = null,
		string $salesPerson = null,
		string $reference = null,
		int $recordid = null,
		string $customer = null,
		string $project = null,
		string $itemcode = null,
		string $include = null,
		string $exclude = null,
		string $sort = null
	): array|null {
		$data_from_dk = $this->handleDkResponse(
			$this->apiService->getSaleInvoices(
				$page,
				$count,
				$sorttype,
				$includeLines,
				$createdAfter,
				$createdBefore,
				$modifiedAfter,
				$modifiedBefore,
				$dueAfter,
				$salesPerson,
				$reference,
				$recordid,
				$customer,
				$project,
				$itemcode,
				$include,
				$exclude,
				$sort
			),
			'Error occurred when getting sale invoices'
		);

		if ( sizeof( $data_from_dk ) > 0 ) {
			$invoices = [];
			foreach ( $data_from_dk as $data ) {
				$Invoice = new Invoice();
				$Invoice->createInvoiceFromDKData( $data );
				$invoices[] = $Invoice;
			}

			return $invoices;
		} else {
			return null;
		}
	}

	public function getSalesInvoice( string $invoice_number ): Invoice|null {
		$data_from_dk = $this->handleDkResponse(
			$this->apiService->getSalesInvoice( $invoice_number ),
			'Error occurred when getting a sales invoice # ' . $invoice_number
		);

		if ( is_object( $data_from_dk ) ) {
			$Invoice = new Invoice();
			$Invoice->createInvoiceFromDKData( $data_from_dk );
			return $Invoice;
		} else {
			return null;
		}
	}

	public function refreshInvoiceInDKPlusFromDK(): void {
		// @TODO: Implement this refreshInvoiceInDKPlusFromDK() function.
	}

	public function createPaymentPlanForInvoice(): void {
		// @TODO: Implement this createPaymentPlanForInvoice() function.
	}

	public function cancelPaymentPlanForInvoice(): void {
		// @TODO: Implement this cancelPaymentPlanForInvoice() function.
	}

	public function deleteInvoice(): void {
		// @TODO: Implement this deleteInvoice() function.
	}

	/**
	 * @throws WooCooServiceException
	 */
	public function createSalesInvoice( Order $order, Customer $customer, bool $print_invoice = false ): void {
		$this->handleDkResponse(
			$this->apiService->createInvoice( $order->jsonSerialize() ),
			'Error occurred while creating an invoice'
		);
	}

	public function createSaleInvoicesInBulk( array $orders ): void {
	}

	/**
	 * Helper function to handle repetitive lines of code regarding the response from the DK API.
	 *
	 * @throws WooCooServiceException
	 */
	private function handleDkResponse( $response, $message ): array|string|stdClass|null {
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
		}
		return null;
	}

	private function uuid4(): string {
		/* 32 random HEX + space for 4 hyphens */
		$out = bin2hex( random_bytes( 18 ) );

		$out[8]  = '-';
		$out[13] = '-';
		$out[18] = '-';
		$out[23] = '-';

		/* UUID v4 */
		$out[14] = '4';

		/* variant 1 - 10xx */
		$out[19] = [ '8', '9', 'a', 'b' ][ random_int( 0, 3 ) ];

		return $out;
	}
}
