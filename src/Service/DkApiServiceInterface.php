<?php

declare(strict_types = 1);

namespace NineteenEightyFour\NineteenEightyWoo\Service;

  /**
   * Interface DkApiServiceInterface
   */
interface DkApiServiceInterface {

	function getProducts(
		string $inactive,
		string $onweb,
		string $group = null,
		string $warehouse = null,
		string $modified = null,
		string $modified_before = null
	): array;

	function getProductGroups(): void;

	function getProductById( string $id ): array;

	/**
	 * Gets one customer by ID. If no customer is found, NULL is returned.
	 *
	 * @param string $id The Social Security Number of the customer
	 */
	function getCustomerById( string $id ): array|null;

	/**
	 * Creates a customer, by the user data.
	 */
	function createCustomer( string $data ): array;

	/**
	 * Creates a product, by the user data.
	 */
	function createProduct( string $data ): array;

	/**
	 * Creates a Sales Order within DK
	 *
	 * @param string $data JSON String with the post data
	 */
	function createSalesOrder( string $data ): array;

	/**
	 * Creates an Invoice within DK
	 *
	 * @param string $data JSON String with the post data
	 * @param bool   $print_invoice Indicates if the invoice should be printed or filed
	 *               as "unprinted".
	 */
	function createInvoice( string $data, bool $print_invoice = false ): array;
}
