<?php

declare(strict_types = 1);

namespace NineteenEightyFour\NineteenEightyWoo\Export;

use NineteenEightyFour\NineteenEightyWoo\Service\DKApiRequest;
use NineteenEightyFour\NineteenEightyWoo\Config;

class ShippingSKU {
	public static function is_in_dk( string $sku ) {
		$api_request = new DKApiRequest();

		$result = $api_request->get_result(
			'/Product/' . $sku
		);

		if ( 200 !== $result->response_code ) {
			return false;
		}

		Config::set_shipping_sku_is_in_dk();

		return true;
	}

	public static function create_in_dk( string $sku ) {
		$api_request = new DKApiRequest();

		$result = $api_request->request_result(
			'/Product/',
			wp_json_encode( self::post_body_for_dk( $sku ) ),
		);

		if ( 200 !== $result->response_code ) {
			return false;
		}

		Config::set_shipping_sku_is_in_dk();

		return true;
	}

	public static function post_body_for_dk( string $sku ) {
		return array(
			'ItemCode'          => $sku,
			'ShowItemInWebShop' => false,
			'Description'       => __( 'Shipping' ),
			'ItemClass'         => 'ServiceItem',
		);
	}
}
