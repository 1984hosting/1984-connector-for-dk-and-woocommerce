<?php

declare(strict_types = 1);

namespace NineteenEightyFour\NineteenEightyWoo\Service;

use Service\Exception\WooCooServiceException;
use stdClass;

class WooCooCompanyService {

	protected DkApiService $apiService;

	/**
	 * The Default constructor fetches the DK Api Service. Dependency injection is
	 * not needed since it requires it at all times.
	 */
	public function __construct() {
		$this->apiService = new DkApiService();
	}

	/**
	 *
	 * @throws WooCooServiceException
	 */
	public function getCompanyRelatedInformationAndSettings(): void {
		$data_from_dk = $this->handleDkResponse(
			$this->apiService->getCompanyRelatedInformationAndSettings(),
			'Error occurred when fetching Company Related Information and settings.'
		);
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
