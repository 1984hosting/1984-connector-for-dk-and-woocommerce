<?php

declare(strict_types = 1);

namespace NineteenEightyFour\NineteenEightyWoo\Service;

use Model\Product\Product;
use Service\Exception\WooCooServiceException;
use stdClass;

class WooCooProductService implements WooCooServiceInterface {

	/**
	 * @var DkApiService $apiService
	 */
	protected DkApiService $apiService;

	/**
	 * The Default constructor fetches the DK Api Service. Dependency injection is
	 * not needed since it requires it at all times.
	 */
	public function __construct() {
		$this->apiService = new DkApiService();
	}

	/**
	 * Gets a product from barcode
	 */
	public function getProductFromBarcode( string $barcode ): void {
		// @TODO: Implement this getProductFromBarcode function
	}

	/**
	 * Creates a barcode for an existing Product.
	 *
	 * @param $data
	 */
	public function createProductBarcode( $data ): void {
		// @TODO: Implement this createProductBarcode function
	}

	/**
	 * Gets all products based on given parameters.
	 * Note that all data from DK is charged by the payload, so be careful calling this function without any
	 * parameters, e.g. the $modified parameter.
	 *
	 * @param string|null $inactive
	 *   Denotes if the dataset should include the inactive products or not. NULL will give you both active and inactive.
	 * @param string|null $on_web
	 *   Denotes if the dataset should include only products marked with "on_web" or not. NULL will give you both.
	 * @param int|null    $product_group_id
	 *   If supplied, only products from this product group is fetched.
	 * @param string|null $warehouse
	 *   Denotes what warehouse should be used to fetch information from. There is always Warehouse 1.
	 * @param string|null $modified
	 *   Time since the information was last fetched. Very useful for narrowing down the dataset.
	 * @throws WooCooServiceException
	 */
	public function getProducts(
		string $inactive = null,
		string $on_web = null,
		int $product_group_id = null,
		string $warehouse = null,
		string $modified = null
	): array {
		$data_from_dk  = $this->handleDkResponse(
			$this->apiService->getProducts( $inactive, $on_web, $product_group_id, $warehouse, $modified ),
			'Error occurred while fetching all Products.'
		);
		$product_array = [];
		foreach ( $data_from_dk as $data ) {
			$product_item = new Product();
			$product_item->createProductFromDkData( $data );
			$product_array[] = $product_item;
		}

		return $product_array;
	}

	public function createProduct( $data ): void {
		// @TODO: Implement this createProduct function
	}

	public function getCountBasedOnCriteria( $data ): void {
		// @TODO: Implement this getCountBasedOnCriteria function. B Priority
	}

	/**
	 * Deletes one product from DK, based on its SKU (ItemCode). The SKU will be base64 encoded
	 * before sent to DK.
	 *
	 * @throws WooCooServiceException
	 */
	public function deleteProduct( string $sku ): array|stdClass|null {
		return $this->handleDkResponse(
			$this->apiService->deleteProduct( $sku ),
			'Error occurred while deleting Product with sku: ' . $sku
		);
	}

	/**
	 * Gets one Product from DK based on its SKU. The SKU will be base64 encoded before sent to DK.
	 *
	 * @throws WooCooServiceException
	 */
	public function getProduct( string $sku ): ?stdClass {
		return $this->handleDkResponse(
			$this->apiService->getProductById( $sku ),
			'Error occurred while fetching product with Sku: ' . $sku
		);
	}

	public function getProductsPaged(): void {
		// @TODO: Implement this getProductsPaged function.
	}

	public function getBarcodeForProduct( $sku, $barcode ): void {
		// @TODO: Implement this getBarCodeForProduct function.
	}

	public function getProductBarcodes( $sku ): void {
		// @TODO: Implement this getProductBarcodes function.
	}

	public function updateProduct( $data ): void {
		// @TODO: Implement this updateProduct function.
	}

	public function getProductAttachment(): void {
		// @TODO: Implement this getProductAttachment function.
	}

	public function uploadFileAsAttachmentToProduct(): void {
		// @TODO: Implement this uploadFileAsAttachmentToProduct function.
	}

	public function refreshProductInformationFromDk(): void {
		// @TODO: Implement this refreshProductInformationFromDk function
	}

	public function removeVendorProductLinkForProduct(): void {
		// @TODO: Implement this removeVendorProductLinkForProduct function.
	}

	public function createVendorItemLink(): void {
		// @TODO: Implement this createVendorItemLink function.
	}

	public function updateVendorProductLink(): void {
		// @TODO: Implement this updateVendorProductLink function.
	}

	/**
	 * Gets all Product groups from DK.
	 *
	 * @throws WooCooServiceException
	 */
	public function getAllProductGroups(): array|null {
		return $this->handleDkResponse(
			$this->apiService->getProductGroups(),
			'Error occurred while fetching all Product Groups.'
		);
	}

	public function getProductSalesLegerCodes(): void {
		// @TODO: Implement this getProductSalesLegerCodes function.
	}

	public function getAllGeneralLegerTransactions(): void {
		// @TODO: Implement this getAllGeneralLegerTransactions function.
	}

	public function getReturnedProductUnits(): void {
		// @TODO: Implement this getReturnedProductUnits function.
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
		}
		return null;
	}
}
