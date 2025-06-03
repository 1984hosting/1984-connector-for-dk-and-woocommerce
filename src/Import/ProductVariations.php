<?php

declare(strict_types = 1);

namespace NineteenEightyFour\NineteenEightyWoo\Import;

use NineteenEightyFour\NineteenEightyWoo\Service\DKApiRequest;
use stdClass;
use WC_Product_Attribute;
use WP_Error;

/**
 * The Product Variations import class
 *
 * Handles product variations, using the direct-to-database endpoints provided
 * by DK, overcoming several shortcomings in the actual JSON API, that does not
 * provide much information such as attribute names in the product endpoint.
 *
 * Variation data is cached locally using transients, making things a lot
 * quicker than sending HTTP request to DK every time we need information on
 * variants.
 */
class ProductVariations {
	const TRANSIENTS       = array( '1984_woo_dk_variations' );
	const TRANSIENT_EXPIRY = 1800;

	const PRODUCTS_TABLE  = 'INITEMS.DAT';
	const PRODUCTS_FIELDS = array( 'itemcode', 'variation' );

	const VARIATIONS_TABLE  = 'INVAGR.DAT';
	const VARIATIONS_FIELDS = array(
		'recordid',
		'code',
		'description',
		'subgroup1',
		'subgroup2',
	);

	const ATTRIBUTES_TABLE  = 'INVAHEAD.DAT';
	const ATTRIBUTES_FIELDS = array( 'recordid', 'code', 'description' );

	const VARIATION_ATTRIBUTES_TABLE  = 'INVALINE.DAT';
	const VARIATION_ATTRIBUTES_FIELDS = array(
		'headcode',
		'description',
		'headid',
		'inactive',
	);

	const ATTRIBUTE_VALUES_TABLE  = 'INVATEXT.DAT';
	const ATTRIBUTE_VALUES_FIELDS = array( 'code', 'description' );

	/**
	 * Get the DK variation attribute codes for a variation
	 *
	 * @param string $variation The variation code.
	 */
	public static function get_variation_attribute_codes(
		string $variation
	): array {
		$variations = self::get_variations();

		if ( ! key_exists( $variation, $variations ) ) {
			return array();
		}

		$variation = $variations[ $variation ];

		if ( ! property_exists( $variation, 'attributes' ) ) {
			return array();
		}

		return array_keys( $variation->attributes );
	}

	/**
	 * Create WooCommerce product variation attributes from variation code
	 *
	 * @param string $variation_code The variation code.
	 */
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

		return $wc_attributes;
	}

	/**
	 * Get the variant code for a DK product by its SKU/ItemCode
	 *
	 * This is an attribute that we can't fetch from the Product JSON endpoint,
	 * so we need to ping the database each time we see that it has variants at
	 * all.
	 *
	 * @param string $sku The product SKU/ItemCode.
	 */
	public static function get_product_variant_code_by_sku(
		string $sku
	): string {
		$variations = self::get_variations();

		foreach ( $variations as $vk => $v ) {
			if ( in_array( $sku, $v->skus, true ) ) {
				return $vk;
			}
		}

		return false;
	}

	/**
	 * Get product SKUs/ItemCodes by variation code
	 *
	 * Gets all the SKUs with a specific variation code as an array.
	 *
	 * @param string $variation_code The DK variation code.
	 */
	public static function get_product_skus_by_variation(
		string $variation_code
	): array {
		$variations = self::get_variations();

		if ( empty( $variations ) ) {
			return self::get_product_skus_by_variation_from_dk(
				$variation_code
			);
		}

		if ( ! array_key_exists( $variation_code, $variations ) ) {
			return array();
		}

		if ( ! property_exists( $variations[ $variation_code ], 'skus' ) ) {
			return array();
		}

		return $variations[ $variation_code ]->skus;
	}

	/**
	 * Get product SKUs/ItemCodes by variation code, bypassing the transient cache
	 *
	 * @param string $variation_code The DK variation code.
	 */
	public static function get_product_skus_by_variation_from_dk(
		string $variation_code
	): array|WP_Error {
		$request = new DKApiRequest();

		$result = $request->get_table_result(
			self::PRODUCTS_TABLE,
			self::PRODUCTS_FIELDS,
			'variation',
			$variation_code
		);

		if ( $result instanceof stdClass && $result->response_code === 200 ) {
			return array_column( $result->data, 'ITEMCODE' );
		}

		return array();
	}

	/**
	 * Get information about all variations
	 *
	 * This spits out an array containing information about all the variations
	 * used in DK, keyed by their variation ID.
	 */
	public static function get_variations(): array {
		$variations_transient = get_transient(
			'1984_woo_dk_variations'
		);

		if ( is_array( $variations_transient ) ) {
			return $variations_transient;
		}

		$variations_value = self::get_variations_from_dk();

		if ( is_array( $variations_value ) ) {
			set_transient(
				'1984_woo_dk_variations',
				$variations_value,
				self::TRANSIENT_EXPIRY
			);

			return $variations_value;
		}

		return array();
	}

	/**
	 * Get all the variations from DK, bypassing the transient cache
	 */
	public static function get_variations_from_dk(): array|WP_Error|false {
		$request = new DKApiRequest();

		$result = $request->get_table_result(
			self::VARIATIONS_TABLE,
			self::VARIATIONS_FIELDS
		);

		if ( $result instanceof WP_Error ) {
			return $result;
		}

		if ( $result->response_code === 200 ) {
			return self::parse_variations_json( $result->data );
		}

		return false;
	}

	/**
	 * Get information for an attribute
	 *
	 * @param string $code The attribute code.
	 */
	public static function get_attribute( string $code ): stdClass|false {
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

	/**
	 * Get information for an attribute, bypassing the transient cache
	 *
	 * @param string $code The attribute code.
	 */
	public static function get_attribute_from_dk(
		string $code
	): stdClass|false {
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

		if ( $result->response_code === 200 ) {
			$object      = $result->data[0];
			$description = (string) $object->DESCRIPTION;
			$record_id   = (int) $object->RECORDID;
			$code        = $object->CODE;
			return (object) array(
				'id'          => $record_id,
				'code'        => mb_strtolower( $code ),
				'description' => $description,
				'values'      => self::get_variation_attributes( $record_id ),
			);
		}

		return false;
	}

	/**
	 * Get variation attributes
	 *
	 * Get attributes for a variation, based on the variation ID
	 *
	 * @param int $variation_id The variation ID from DK.
	 */
	public static function get_variation_attributes(
		int $variation_id
	): array {
		$variation_attributes_transient = get_transient(
			'1984_woo_dk_variation_attributes_' . (string) $variation_id
		);

		if ( is_array( $variation_attributes_transient ) ) {
			return $variation_attributes_transient;
		}

		$variation_attributes_values =
		self::get_variation_attributes_from_dk( $variation_id );

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

	/**
	 * Get variation attributes from DK
	 *
	 * Get attributes for a variation, based on the variation ID, bypassing the
	 * transient cache
	 *
	 * @param int $variation_id The variation ID from DK.
	 */
	public static function get_variation_attributes_from_dk(
		int $variation_id
	): array {
		$values  = self::get_attribute_values();
		$request = new DKApiRequest();

		$result = $request->get_table_result(
			self::VARIATION_ATTRIBUTES_TABLE,
			self::VARIATION_ATTRIBUTES_FIELDS,
			'headid',
			(string) $variation_id
		);

		if ( $result->response_code === 200 ) {
			$attributes = array();

			foreach ( $result->data as $a ) {
				$code     = mb_strtolower( $a->HEADCODE );
				$code_key = mb_strtolower( $code );

				$attributes[ $code ] = (object) array(
					'code' => $code,
					'name' => $values[ $code_key ]->name,
				);
			}

			return $attributes;
		}

		return array();
	}

	/**
	 * Get attribute name from code
	 *
	 * @param string $code The attribute code.
	 *
	 * @return string The attribute name, or the attribute code if not found.
	 */
	public static function get_attribute_name( string $code ): string {
		$attribute_values = self::get_attribute_values();

		$key = mb_strtolower( $code );

		if ( ! key_exists( $key, $attribute_values ) ) {
			return $code;
		}

		if ( ! property_exists( $attribute_values[ $key ], 'name' ) ) {
			return $code;
		}

		return $attribute_values[ $key ]->name;
	}

	/**
	 * Get all the attribute values
	 */
	public static function get_attribute_values(): array {
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

	/**
	 * Get all the attribute values from DK, bypassing the transient cache
	 */
	public static function get_attribute_values_from_dk(): array {
		$request = new DKApiRequest();

		$result = $request->get_table_result(
			self::ATTRIBUTE_VALUES_TABLE,
			self::ATTRIBUTE_VALUES_FIELDS,
		);

		if ( $result->response_code === 200 ) {
			$values = array();

			foreach ( $result->data as $v ) {
				$code = $v->CODE;

				if ( property_exists( $v, 'DESCRIPTION' ) ) {
					$name = $v->DESCRIPTION;
				} else {
					$name = $code;
				}

				$values[ mb_strtolower( $code ) ] = (object) array(
					'code' => mb_strtolower( $code ),
					'name' => $name,
				);
			}

			return $values;
		}

		return array();
	}

	/**
	 * Parse the JSON response from get_variations_from_dk
	 *
	 * @param array $variation_json The variation JSON data as an array.
	 */
	public static function parse_variations_json( array $variation_json ): array {
		$variations = array();

		foreach ( $variation_json as $vj ) {
			$variation   = array();
			$code        = mb_strtolower( $vj->CODE );
			$description = $vj->DESCRIPTION;

			$variation['skus'] = self::get_product_skus_by_variation_from_dk(
				$code
			);

			$variations[ $code ] = array(
				'code'        => mb_strtolower( $code ),
				'description' => $description,
			);

			if ( property_exists( $vj, 'SUBGROUP1' ) ) {
				$variation['attributes'][ mb_strtolower( $vj->SUBGROUP1 ) ] =
				self::get_attribute( mb_strtolower( $vj->SUBGROUP1 ) );
			}

			if ( property_exists( $vj, 'SUBGROUP2' ) ) {
				$variation['attributes'][ mb_strtolower( $vj->SUBGROUP2 ) ] =
				self::get_attribute( mb_strtolower( $vj->SUBGROUP2 ) );
			}

			$variations[ $code ] = (object) $variation;
		}

		return $variations;
	}
}
