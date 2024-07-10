<?php

declare(strict_types = 1);

namespace NineteenEightyFour\NineteenEightyWoo\Import;

use NineteenEightyFour\NineteenEightyWoo\Service\DKApiRequest;
use WC_Product;
use WC_Product_Attribute;

class ProductVariations {
	const TRANSIENT_EXPIRY = 600;

	const PRODUCTS_TABLE  = 'INITEMS.DAT';
	const PRODUCTS_FIELDS = array( 'itemcode', 'variation' );

	const VARIATIONS_TABLE  = 'INVAGR.DAT';
	const VARIATIONS_FIELDS = array( 'recordid', 'code', 'description', 'subgroup1', 'subgroup2' );

	const ATTRIBUTES_TABLE  = 'INVAHEAD.DAT';
	const ATTRIBUTES_FIELDS = array( 'recordid', 'code', 'description' );

	const VARIATION_ATTRIBUTES_TABLE  = 'INVALINE.DAT';
	const VARIATION_ATTRIBUTES_FIELDS = array( 'headcode', 'description', 'headid', 'inactive' );

	const ATTRIBUTE_VALUES_TABLE  = 'INVATEXT.DAT';
	const ATTRIBUTE_VALUES_FIELDS = array( 'code', 'description' );

	public static function attributes_to_woocommerce_variation_attributes(
		string $code
	): array {
		if ( empty( $code ) ) {
			return array();
		}

		$variations = self::get_variations();

		$attributes = $variations[ $code ]->attributes;

		$woocommerce_variation_attributes = array();

		foreach ( $attributes as $code => $attribute ) {
			$woocommerce_variation_attributes[ $code ] = array_keys( $attribute->values );
		}

		return $woocommerce_variation_attributes;
	}

	public static function variation_attributes_to_wc_product_attributes(
		string $variation_code
	): array {
		$variations = self::get_variations();
		$variation  = $variations[ $variation_code ];

		$wc_attributes = array();

		foreach ( $variation->attributes as $v ) {
			$wc_product_attribute = new WC_Product_Attribute();
			$wc_product_attribute->set_name( $v->code );
			$wc_product_attribute->set_options( array_keys( $v->values ) );
			$wc_product_attribute->set_visible( true );
			$wc_product_attribute->set_variation( true );

			$wc_attributes[] = $wc_product_attribute;
		}

		return array_reverse( $wc_attributes );
	}

	public static function get_product_variant_code_by_sku( string $sku ) {
		$request = new DKApiRequest();

		$result = $request->get_table_result(
			self::PRODUCTS_TABLE,
			self::PRODUCTS_FIELDS,
			'itemcode',
			$sku
		);

		if ( 200 === $result->response_code ) {
			return $result->data[0]->VARIATION;
		}

		return '';
	}

	public static function get_product_skus_by_variation( string $variation ) {
		$product_skus_by_variation_transient = get_transient(
			'1984_woo_dk_product_skus_by_variation_' . $variation
		);

		if ( is_array( $product_skus_by_variation_transient ) ) {
			return $product_skus_by_variation_transient;
		}

		$product_skus_by_variation_value = self::get_product_skus_by_variation_from_dk( $variation );

		if ( is_array( $product_skus_by_variation_value ) ) {
			set_transient(
				'1984_woo_dk_product_skus_by_variation_' . $variation,
				$product_skus_by_variation_value,
				self::TRANSIENT_EXPIRY
			);

			return $product_skus_by_variation_value;
		}

		return array();
	}

	public static function get_product_skus_by_variation_from_dk( string $variation ) {
		$request = new DKApiRequest();

		$result = $request->get_table_result(
			self::PRODUCTS_TABLE,
			self::PRODUCTS_FIELDS,
			'variation',
			$variation
		);

		if ( 200 === $result->response_code ) {
			return array_column( $result->data, 'ITEMCODE' );
		}

		return array();
	}

	public static function get_variations() {
		$variations_transient = get_transient(
			'1984_woo_dk_variations'
		);

		if ( is_array( $variations_transient ) ) {
			return $variations_transient;
		}

		$variations_value = self::get_variations_from_dk();

		if ( $variations_value ) {
			set_transient(
				'1984_woo_dk_variations',
				$variations_value,
				self::TRANSIENT_EXPIRY
			);

			return $variations_value;
		}

		return false;
	}

	public static function get_variations_from_dk() {
		$request = new DKApiRequest();

		$result = $request->get_table_result(
			self::VARIATIONS_TABLE,
			self::VARIATIONS_FIELDS
		);

		if ( 200 === $result->response_code ) {
			return self::parse_variations_json( $result->data );
		}

		return false;
	}

	public static function get_attribute( string $code ) {
		$attribute_transient = get_transient(
			'1984_woo_dk_attribute_' . $code
		);

		if ( is_object( $attribute_transient ) ) {
			return $attribute_transient;
		}

		$attribute_value = self::get_attribute_from_dk( $code );

		if ( $attribute_value ) {
			set_transient(
				'1984_woo_dk_attribute_' . $code,
				$attribute_value,
				self::TRANSIENT_EXPIRY
			);

			return $attribute_value;
		}

		return false;
	}

	public static function get_attribute_from_dk( string $code ) {
		$request = new DKApiRequest();

		$result = $request->get_table_result(
			self::ATTRIBUTES_TABLE,
			self::ATTRIBUTES_FIELDS,
			'code',
			$code
		);

		if ( empty( $result->data ) ) {
			return false;
		}

		if ( 200 === $result->response_code ) {
			$object      = $result->data[0];
			$description = (string) $object->DESCRIPTION;
			$record_id   = (int) $object->RECORDID;
			$code        = $object->CODE;
			return (object) array(
				'id'          => $record_id,
				'code'        => $code,
				'description' => $description,
				'values'      => self::get_variation_attributes( $record_id ),
			);
		}

		return false;
	}

	public static function get_variation_attributes( int $variation_id ) {
		$variation_attributes_transient = get_transient(
			'1984_woo_dk_variation_attributes_' . (string) $variation_id
		);

		if ( is_array( $variation_attributes_transient ) ) {
			return $variation_attributes_transient;
		}

		$variation_attributes_values = self::get_variation_attributes_from_dk( $variation_id );

		if ( is_array( $variation_attributes_values ) ) {
			set_transient(
				'1984_woo_dk_variation_attributes_' . (string) $variation_id,
				$variation_attributes_values,
				self::TRANSIENT_EXPIRY
			);

			return $variation_attributes_values;
		}

		return array();
	}

	public static function get_variation_attributes_from_dk( int $variation_id ) {
		$values  = self::get_attribute_values();
		$request = new DKApiRequest();

		$result = $request->get_table_result(
			self::VARIATION_ATTRIBUTES_TABLE,
			self::VARIATION_ATTRIBUTES_FIELDS,
			'headid',
			(string) $variation_id
		);

		if ( 200 === $result->response_code ) {
			$attributes = array();

			foreach ( $result->data as $a ) {
				if ( 'true' === $a->INACTIVE ) {
					continue;
				}

				$code     = $a->HEADCODE;
				$code_key = strtoupper( $code );

				$attributes[ $code ] = (object) array(
					'code' => $code,
					'name' => $values[ $code_key ]->name,
				);
			}

			return $attributes;
		}

		return array();
	}

	public static function get_attribute_values() {
		$attribute_values_transient = get_transient(
			'1984_woo_dk_attribute_values'
		);

		if ( is_array( $attribute_values_transient ) ) {
			return $attribute_values_transient;
		}

		$attribute_values = self::get_attribute_values_from_dk();

		if ( is_array( $attribute_values ) ) {
			set_transient(
				'1984_woo_dk_attribute_values',
				$attribute_values,
				self::TRANSIENT_EXPIRY
			);

			return $attribute_values;
		}

		return array();
	}

	public static function get_attribute_name( $code ) {
		$attribute_values = self::get_attribute_values();

		$key = strtoupper( $code );

		if ( ! key_exists( $key, $attribute_values ) ) {
			return $code;
		}

		if ( ! property_exists( $attribute_values[ $key ], 'name' ) ) {
			return $code;
		}

		return $attribute_values[ $key ]->name;
	}

	public static function get_attribute_values_from_dk() {
		$request = new DKApiRequest();

		$result = $request->get_table_result(
			self::ATTRIBUTE_VALUES_TABLE,
			self::ATTRIBUTE_VALUES_FIELDS,
		);

		if ( 200 === $result->response_code ) {
			$values = array();

			foreach ( $result->data as $v ) {
				$code = $v->CODE;

				if ( property_exists( $v, 'DESCRIPTION' ) ) {
					$name = $v->DESCRIPTION;
				} else {
					$name = $code;
				}

				$values[ $code ] = (object) array(
					'code' => $code,
					'name' => $name,
				);
			}

			return $values;
		}

		return array();
	}

	public static function parse_variations_json( $variation_json ): array {
		$variations = array();

		foreach ( $variation_json as $vj ) {
			$code        = $vj->CODE;
			$description = $vj->DESCRIPTION;
			$attribute_1 = $vj->SUBGROUP1;
			$attribute_2 = $vj->SUBGROUP2;

			$variations[ $code ] = (object) array(
				'code'        => $code,
				'description' => $description,
				'attributes'  => array(
					$attribute_1 => self::get_attribute( $attribute_1 ),
					$attribute_2 => self::get_attribute( $attribute_2 ),
				),
			);
		}

		return $variations;
	}
}
