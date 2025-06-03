<?php

declare(strict_types = 1);

namespace NineteenEightyFour\NineteenEightyWoo\Import;

use NineteenEightyFour\NineteenEightyWoo\Service\DKApiRequest;
use NineteenEightyFour\NineteenEightyWoo\Brick\Math\BigDecimal;
use NineteenEightyFour\NineteenEightyWoo\Brick\Math\RoundingMode;
use NineteenEightyFour\NineteenEightyWoo\Currency;
use NineteenEightyFour\NineteenEightyWoo\Config;
use NineteenEightyFour\NineteenEightyWoo\Helpers\Product as ProductHelper;
use DateTime;
use stdClass;
use WC_DateTime;
use WC_Product;
use WP_Error;
use WC_Tax;
use WC_Product_Variation;
use WC_Data_Store;

/**
 * The Products import class
 *
 * Handles reading product data from DK and importing it as products as
 * WooCommerce products.
 */
class Products {
	const API_PATH = '/Product/';

	/**
	 * The properties we get from the DK JSON API
	 */
	const INCLUDE_PROPERTIES = 'ItemCode,Description,PropositionPrice,' .
		'UnitPrice1,UnitPrice1WithTax,Inactive,NetWeight,UnitVolume,' .
		'TotalQuantityInWarehouse,UnitPrice1,TaxPercent,' .
		'AllowNegativeInventiry,ExtraDesc1,ExtraDesc2,ShowItemInWebShop,' .
		'Inactive,Deleted,PropositionDateTo,PropositionDateFrom,CurrencyCode,' .
		'CurrencyPrices,IsVariation,Warehouses';

	/**
	 * Save all products from DK
	 *
	 * This should be run at least nightly as a wp-cron job.
	 */
	public static function save_all_from_dk(): void {
		if ( ! defined( '1984_DK_WOO_DOING_SYNC' ) ) {
			define( '1984_DK_WOO_DOING_SYNC', true );
		}

		$json_objects = self::get_all_from_dk();

		if ( is_array( $json_objects ) ) {
			foreach ( $json_objects as $json_object ) {
				self::save_from_dk(
					$json_object->ItemCode,
					$json_object
				);
			}
		}
	}

	/**
	 * Get all products from DK
	 *
	 * This fetches all the products from the DK API. It includes inactive and
	 * deleted products as a means to label those properly in WooCommerce.
	 */
	public static function get_all_from_dk(): false|WP_Error|array {
		$api_request = new DKApiRequest();

		$query_string = '?include=' . self::INCLUDE_PROPERTIES;

		if ( ! Config::get_delete_inactive_products() ) {
			$query_string .= '&inactive=false';
		}

		if ( ! Config::get_import_nonweb_products() ) {
			$query_string .= '&onweb=true';
		}

		$result = $api_request->get_result(
			self::API_PATH . $query_string,
		);

		if ( $result instanceof WP_Error ) {
			return $result;
		}

		if ( $result->response_code !== 200 ) {
			return false;
		}

		return (array) $result->data;
	}

	/**
	 * Save a single product from DK as a WooCommerce product
	 *
	 * @param string        $sku The product SKU to fetch from DK.
	 * @param stdClass|null $json_object The PHP representation of a JSON object
	 *                                   form the DK Products endpoint. If not
	 *                                   set or null, this will fetch the
	 *                                   product from DK.
	 *
	 * @return int|false The ID for the WooCommerce Product on success,
	 *                   false on failure.
	 */
	public static function save_from_dk(
		string $sku,
		stdClass|null $json_object = null
	): int|false {
		if ( is_null( $json_object ) ) {
			$json_object = self::get_from_dk( $sku );
		}

		if ( ! is_object( $json_object ) ) {
			return false;
		}

		$wc_product = self::json_to_product( $json_object );

		if ( ! $wc_product ) {
			return false;
		}

		$wc_product->save();
		$wc_product->save_meta_data();

		return $wc_product->get_id();
	}

	/**
	 * Get a single product from the DK JSON API
	 *
	 * @param string $sku The product SKU to fetch from the DK API.
	 *
	 * @return stdClass|WP_Error|false An object representing the JSON response
	 *                                 from the DK API on success. WP_Error on
	 *                                 connection error or false on error in the
	 *                                 DK API.
	 */
	public static function get_from_dk(
		string $sku
	): stdClass|WP_Error|false {
		$api_request = new DKApiRequest();

		$result = $api_request->get_result(
			self::API_PATH . $sku,
		);

		if ( $result instanceof WP_Error ) {
			return $result;
		}

		if ( $result->response_code !== 200 ) {
			return false;
		}

		return (object) $result->data;
	}

	/**
	 * Convert a JSON object from the DK API to a WC_Product object
	 *
	 * @param stdClass $json_object A PHP object representation of a JSON object
	 *                              in the DK API.
	 *
	 * @return WC_Product|false A WooCommerce product, or false if it should not
	 *                          appear in the WooCommerce shop.
	 */
	public static function json_to_product(
		stdClass $json_object
	): WC_Product|false {
		$product_id = wc_get_product_id_by_sku( $json_object->ItemCode );

		if ( $product_id === 0 ) {
			$wc_product = self::json_to_new_product( $json_object );
		} else {
			$wc_product = self::update_product_from_json(
				$product_id,
				$json_object
			);
		}

		return $wc_product;
	}

	/**
	 * Create a product in DK based on a PHP object
	 *
	 * The PHP object is cast to a JSON string and then sent over to the DK API
	 * for processing.
	 *
	 * As this object is dealt with as a new product, all the attributes are
	 * read in regardless of price sync, stock status sync or product name sync
	 * are enabled.
	 *
	 * @param stdClass $json_object The JSON object from the DK API.
	 *
	 * @return WC_Product|false The resulting WooCommerce product, or false on
	 *                          failure or deletion.
	 */
	public static function json_to_new_product(
		stdClass $json_object
	): WC_Product|false {
		if (
			property_exists( $json_object, 'Deleted' ) &&
			$json_object->Deleted
		) {
			return false;
		}

		if ( $json_object->Inactive ) {
			return false;
		}

		if (
			mb_strtolower( Config::get_shipping_sku() ) ===
			mb_strtolower( $json_object->ItemCode )
		) {
			return false;
		}

		if (
			mb_strtolower( Config::get_cost_sku() ) ===
			mb_strtolower( $json_object->ItemCode )
		) {
			return false;
		}

		if (
			! $json_object->ShowItemInWebShop &&
			! Config::get_import_nonweb_products()
		) {
			return false;
		}

		if ( $json_object->IsVariation ) {
			$wc_product = wc_get_product_object( 'variable' );
			$wc_product->save();

			$variant_code = ProductVariations::get_product_variant_code_by_sku(
				$json_object->ItemCode
			);
			$wc_product->update_meta_data(
				'1984_dk_woo_origin',
				'product_variation'
			);
			$wc_product->update_meta_data(
				'1984_dk_woo_variant_code',
				$variant_code
			);
			$wc_product->set_attributes(
				ProductVariations::variation_attributes_to_wc_product_attributes(
					$variant_code
				)
			);
			$merged_variations = self::merge_variations(
				$json_object,
				$variant_code
			);
			$wc_product->update_meta_data(
				'1984_dk_woo_variations',
				$merged_variations
			);
			$wc_product->save();
		} else {
			$wc_product = wc_get_product_object( 'simple' );
			$wc_product->update_meta_data( '1984_dk_woo_origin', 'product' );
			$wc_product->save();
		}

		$wc_product->set_sku( $json_object->ItemCode );

		if ( ! $json_object->ShowItemInWebShop ) {
			$wc_product->set_status( 'Draft' );
		}

		if ( property_exists( $json_object, 'Description' ) ) {
			$wc_product->set_name( $json_object->Description );
		}

		// Take in descriptions if they have been defined in DK.
		if ( ! empty( $json_object->ExtraDesc1 ) ) {
			$wc_product->set_description( $json_object->ExtraDesc1 );
		}
		if ( ! empty( $json_object->ExtraDesc2 ) ) {
			$wc_product->set_short_description( $json_object->ExtraDesc2 );
		}

		if ( ! empty( $json_object->NetWeight ) ) {
			$wc_product->set_weight( $json_object->NetWeight );
		}

		$price = self::get_product_price_from_json( $json_object );

		if ( $price instanceof stdClass ) {
			$wc_product->set_regular_price( $price->price );
			$wc_product->set_sale_price( $price->sale_price );
			$wc_product->set_date_on_sale_from( $price->date_on_sale_from );
			$wc_product->set_date_on_sale_to( $price->date_on_sale_to );
			$wc_product->set_tax_class( $price->tax_class );

			$wc_product->update_meta_data(
				'1984_woo_dk_dk_currency',
				$price->currency
			);

			$wc_product->update_meta_data(
				'1984_dk_woo_price',
				$price,
			);
		} else {
			return false;
		}

		if ( $json_object->IsVariation ) {
			self::update_variations( $merged_variations, $wc_product );
		}

		if ( Config::get_product_quantity_sync() ) {
			$wc_product->set_manage_stock( true );

			$quantity = self::get_product_quantity_from_json( $json_object );

			$wc_product->set_stock_quantity( $quantity->stock_quantity );
			$wc_product->set_backorders( $quantity->backorders );
		}

		$current_date_and_time = new DateTime();

		$wc_product->update_meta_data(
			'last_downstream_sync',
			$current_date_and_time->format( 'U' )
		);

		$wc_product->save();

		return $wc_product;
	}

	/**
	 * Update a product based on a JSON object coming from the DK API
	 *
	 * - If the product is marked as deleted in DK, it will be deleted in WooCommerce
	 * - If the product is marked as inactive in DK, its status will be changed to `draft` in WooCommerce
	 * - If the product is marked as active in DK and  ShowItemInWebShop` is `true`, its status will be changed to `publish`
	 * - The product name will be updated from the `Description` attribute, if name sync is enabled.
	 * - The product weight will be updated
	 * - The product price, sale price and sale dates will be updated, if price sync is enabled
	 * - Product quantity and stock status will be updated, if quantity sync is enabled
	 *
	 * @param int      $product_id The Post ID for the WooCommerce product to be updated.
	 * @param stdClass $json_object An object representing the JSON object coming from the DK API.
	 *
	 * @return WC_Product|false The WC_Product object that was updated on
	 *                          success. False on failure or if the product is
	 *                          deleted.
	 */
	public static function update_product_from_json(
		int $product_id,
		stdClass $json_object
	): WC_Product|false {
		$wc_product = wc_get_product( $product_id );

		if ( ! ( $wc_product instanceof WC_Product ) ) {
			return false;
		}

		if (
			$json_object->Inactive ||
			(
				property_exists( $json_object, 'Deleted' ) &&
				$json_object->Deleted
			)
		) {
			wp_delete_post( $wc_product->get_id() );
			return false;
		}

		if ( $json_object->IsVariation ) {
			$variant_code = ProductVariations::get_product_variant_code_by_sku(
				$json_object->ItemCode
			);
			$wc_product->update_meta_data(
				'1984_dk_woo_origin',
				'product_variation'
			);
			$wc_product->update_meta_data(
				'1984_dk_woo_variant_code',
				$variant_code
			);
			$wc_product->set_attributes(
				ProductVariations::variation_attributes_to_wc_product_attributes(
					$variant_code
				)
			);
			$merged_variations = self::merge_variations(
				$json_object,
				$variant_code
			);
			$wc_product->update_meta_data(
				'1984_dk_woo_variations',
				$merged_variations
			);
			self::update_variations( $merged_variations, $wc_product );
		} else {
			$wc_product->update_meta_data( '1984_dk_woo_origin', 'product' );
			$wc_product->update_meta_data( '1984_dk_woo_variant_code', '' );
			$wc_product->update_meta_data( '1984_dk_woo_variations', '' );
		}

		if ( $json_object->ShowItemInWebShop ) {
			$wc_product->set_status( 'Publish' );
		} else {
			if ( $wc_product instanceof WC_Product_Variation ) {
				$wc_product->set_status( 'Private' );
			} else {
				$wc_product->set_status( 'Draft' );
			}
		}

		if (
			property_exists( $json_object, 'Description' ) &&
			Config::get_product_name_sync()
		) {
			$wc_product->set_name( $json_object->Description );
		}

		if ( empty( $json_object->NetWeight ) ) {
			$wc_product->set_weight( '' );
		} else {
			$wc_product->set_weight( $json_object->NetWeight );
		}

		if ( ProductHelper::price_sync_enabled( $wc_product ) ) {
			$price = self::get_product_price_from_json( $json_object );

			if ( $price instanceof stdClass ) {
				$wc_product->set_regular_price( $price->price );
				$wc_product->set_sale_price( $price->sale_price );
				$wc_product->set_date_on_sale_from( $price->date_on_sale_from );
				$wc_product->set_date_on_sale_to( $price->date_on_sale_to );
				$wc_product->set_tax_class( $price->tax_class );

				$wc_product->update_meta_data(
					'1984_woo_dk_dk_currency',
					$price->currency
				);

				$wc_product->update_meta_data(
					'1984_dk_woo_price',
					$price,
				);
			} else {
				return false;
			}
		}

		if ( ProductHelper::quantity_sync_enabled( $wc_product ) ) {
			$wc_product->set_manage_stock( true );

			$quantity = self::get_product_quantity_from_json( $json_object );

			$wc_product->set_stock_quantity( $quantity->stock_quantity );
			$wc_product->set_backorders( $quantity->backorders );
		}

		$current_date_and_time = new DateTime();

		$wc_product->update_meta_data(
			'last_downstream_sync',
			$current_date_and_time->format( 'U' )
		);

		$wc_product->save();

		return $wc_product;
	}

	/**
	 * Get a product's prices from a DK API response
	 *
	 * @param stdClass $json_object A PHP object representing the JSON response
	 *                              from the DK API.
	 *
	 * @return stdClass An object containing the properties
	 *                  `price` (float or empty string),
	 *                  `sale_price` (float or empty string),
	 *                  `date_on_sale_from` (WC_DateTime or empty string)
	 *                  and `date_on_sale_to` (WC_DateTime or empty string) or
	 *                  `false` on failure.
	 */
	public static function get_product_price_from_json(
		stdClass $json_object
	): stdClass|false {
		$store_currency = get_woocommerce_currency();
		$dk_currency    = Config::get_dk_currency();

		$tax_class = self::tax_class_from_rate(
			$json_object->TaxPercent
		);

		if ( $store_currency === $dk_currency ) {
			$price_before_tax      = $json_object->UnitPrice1;
			$price_with_tax        = $json_object->UnitPrice1WithTax;
			$sale_price_before_tax = $json_object->PropositionPrice;
		} else {
			$price_before_tax = self::get_currency_price_from_json(
				$json_object
			);

			if ( $price_before_tax instanceof WP_Error ) {
				return $price_before_tax;
			}

			$price_with_tax = self::calculate_price_after_tax(
				$price_before_tax,
				$json_object->TaxPercent
			);

			$sale_price_before_tax = Currency::convert(
				$json_object->PropositionPrice,
				$dk_currency,
				$store_currency
			);
		}

		if ( wc_prices_include_tax() ) {
			$price = $price_with_tax;

			if ( $sale_price_before_tax > 0 ) {
				$sale_price = self::calculate_price_after_tax(
					$sale_price_before_tax,
					$json_object->TaxPercent
				);
			} else {
				$sale_price = '';
			}
		} else {
			$price = $price_before_tax;

			if ( $sale_price_before_tax > 0 ) {
				$sale_price = $sale_price_before_tax;
			} else {
				$sale_price = '';
			}
		}

		if ( property_exists( $json_object, 'PropositionDateFrom' ) ) {
			$date_on_sale_from = new WC_DateTime(
				$json_object->PropositionDateFrom
			);
		} else {
			$date_on_sale_from = '';
		}

		if ( property_exists( $json_object, 'PropositionDateTo' ) ) {
			$date_on_sale_to = new WC_DateTime(
				$json_object->PropositionDateTo
			);
		} else {
			$date_on_sale_to = '';
		}

		return (object) array(
			'price'             => $price,
			'sale_price'        => $sale_price,
			'date_on_sale_from' => $date_on_sale_from,
			'date_on_sale_to'   => $date_on_sale_to,
			'currency'          => $dk_currency,
			'tax_class'         => $tax_class,
		);
	}

	/**
	 * Calculate an "after tax" price
	 *
	 * @param float|int $price_before_tax The original price, before tax.
	 * @param float     $tax_rate The tax rate percentage.
	 *
	 * @return float The "after tax" price as a float.
	 */
	public static function calculate_price_after_tax(
		float|int $price_before_tax,
		float $tax_rate
	): float {
		if ( $tax_rate === 0 ) {
			return (float) $price_before_tax;
		}

		$tax_percentage = BigDecimal::of( $tax_rate );

		$tax_fraction = $tax_percentage->dividedBy(
			100,
			12,
			roundingMode: RoundingMode::HALF_UP
		);

		return BigDecimal::of(
			$price_before_tax
		)->multipliedBy(
			$tax_fraction->plus( 1 )
		)->toFloat();
	}

	/**
	 * Get a product's price in the store's currency, before tax from a DK API
	 * response object
	 *
	 * This one checks if any manual `CurrencyPrices` have been set and if not
	 * converts `UnitPrice1` into the local currency.
	 *
	 * @param stdClass $json_object A PHP object representing the JSON response
	 *                              from the DK API.
	 *
	 * @return float|WP_Error A floating point representation of the local
	 *                        currency price, or WP_Error if the currency could
	 *                        not be converted.
	 */
	public static function get_currency_price_from_json(
		stdClass $json_object
	): float|WP_Error {
		$store_currency = get_woocommerce_currency();
		$dk_currency    = Config::get_dk_currency();

		foreach ( $json_object->CurrencyPrices as $currency_price ) {
			if ( $store_currency === $currency_price->CurrencyCode ) {
				return (float) $currency_price->Price1;
			}
		}

		$price_before_tax = Currency::convert(
			$json_object->UnitPrice1,
			$dk_currency,
			$store_currency
		);

		return (float) $price_before_tax;
	}

	/**
	 * Get a product's quantity and stock information from a DK API response
	 * object
	 *
	 * @param stdClass $json_object A PHP object representing the JSON response
	 *                              from the DK API.
	 *
	 * @return stdClass A PHP object containing the properties `stock_quantity`
	 *                  and `backorders`.
	 */
	public static function get_product_quantity_from_json(
		stdClass $json_object
	): stdClass {
		$result = array();

		$result['stock_quantity'] = $json_object->TotalQuantityInWarehouse;

		// 'Inventiry' is the spelling that DK uses. I'm dead serious.
		if ( $json_object->AllowNegativeInventiry ) {
			$result['backorders'] = 'yes';
		} else {
			$result['backorders'] = 'no';
		}

		return (object) $result;
	}

	/**
	 * Get a tax class from a VAT percentage rate
	 *
	 * @param float $percentage The tax rate to look up.
	 *
	 * @return string The matched tax class. Defaults to empty string, for the
	 *                default rate.
	 */
	public static function tax_class_from_rate( float $percentage ): string {
		if ( is_null( WC()->countries ) ) {
			return '';
		}

		if ( $percentage === 0.0 ) {
			return 'Zero rate';
		}

		$tax_rates = array(
			'' => array_values( WC_Tax::get_base_tax_rates( '' ) )[0],
		);

		foreach ( WC_Tax::get_tax_classes() as $tax_class ) {
			$values = array_values(
				WC_Tax::get_base_tax_rates( $tax_class )
			);
			if ( ! empty( $values ) ) {
				$tax_rates[ $tax_class ] = $values[0];
			}
		}

		foreach ( $tax_rates as $class => $r ) {
			if ( $percentage === $r['rate'] ) {
				return $class;
			}
		}

		return '';
	}

	/**
	 * Merge product variations
	 *
	 * Variations for each product in DK belong to its "warehouses" objects.
	 * This means that you can have a certain quantity in certain warehouses and
	 * those quantities need to be summed up into a single value.
	 *
	 * DK also does not supply the variation attribute names in their product
	 * API response and we use the opportunity to fetch them here.
	 *
	 * The resulting array is then saved as product meta.
	 *
	 * @param stdClass $json_object The product JSON object.
	 * @param string   $variant_code The product's variant code.
	 */
	public static function merge_variations(
		stdClass $json_object,
		string $variant_code
	): array {
		$attribute_names  = ProductVariations::get_variation_attribute_codes( $variant_code );
		$warehouses       = $json_object->Warehouses;
		$variations_array = array();

		foreach ( $warehouses as $w ) {
			foreach ( $w->Variations as $v ) {
				foreach ( $variations_array as $key => $s ) {
					if ( self::compare_variations( $s, $v ) ) {
						$variations_array[ $key ]->quantity = BigDecimal::of(
							$s->quantity
						)->plus(
							$v->Quantity
						)->toFloat();
						break;
					}
				}
				$variation = array(
					'quantity'    => (float) $v->Quantity,
					'attribute_1' => mb_strtolower( $attribute_names[0] ),
					'code_1'      => mb_strtolower( $v->Code ),
				);

				if ( property_exists( $v, 'Code2' ) ) {
					$variation['attribute_2'] = mb_strtolower( $attribute_names[1] );
					$variation['code_2']      = mb_strtolower( $v->Code2 );
				}

				$variations_array[] = (object) $variation;
			}
		}

		return $variations_array;
	}

	/**
	 * Compare a variation from DK product response with one from the product meta
	 *
	 * @param stdClass $dk_variation Variation as it comes from DK.
	 * @param stdClass $saved_variation Variation as saved as product meta.
	 */
	public static function compare_variations(
		stdClass $dk_variation,
		stdClass $saved_variation
	): bool {
		if (
			property_exists( $dk_variation, 'Code' ) &&
			property_exists( $saved_variation, 'code_1' ) &&
			$dk_variation->Code === $saved_variation->code_1
		) {
			if (
				property_exists( $dk_variation, 'Code2' ) &&
				property_exists( $saved_variation, 'code_2' ) &&
				$dk_variation->Code2 !== $saved_variation->code_2
			) {
				return false;
			}
			return true;
		}

		return false;
	}

	/**
	 * Update and delete product variants based on saved variations
	 *
	 * Creates, updates and deletes product variants based on the available
	 * variations from DK that are saved from the product meta.
	 *
	 * If a variant already exsists, it is updated, if it does not it is created
	 * and if it no longer is in DK, it gets deleted.
	 *
	 * @param array      $variations_array The variation parent product meta array.
	 * @param WC_Product $wc_product The parent product.
	 */
	public static function update_variations(
		array $variations_array,
		WC_Product $wc_product
	): array {
		$data_store             = WC_Data_Store::load( 'product' );
		$affected_variation_ids = array();

		foreach ( $variations_array as $v ) {
			$attributes = array(
				sanitize_title( 'attribute_' . $v->attribute_1 ) => $v->code_1,
			);

			if ( property_exists( $v, 'code_2' ) ) {
				$key                = sanitize_title( 'attribute_' . $v->attribute_2 );
				$attributes[ $key ] = $v->code_2;
			}

			$variation_id = $data_store->find_matching_product_variation(
				$wc_product,
				$attributes
			);

			if ( $variation_id === 0 ) {
				$variation = wc_get_product_object( 'variation' );

				$variation->set_parent_id( $wc_product->get_id() );
				$variation->set_attributes( $attributes );
				$variation->set_stock_quantity( $v->quantity );
				$variation->set_weight( $wc_product->get_weight() );
				$variation->set_manage_stock( $wc_product->get_manage_stock() );

				$price = $wc_product->get_meta( '1984_dk_woo_price' );

				if ( is_object( $price ) ) {
					$variation->set_regular_price( $price->price );
					$variation->set_sale_price( $price->sale_price );
					$variation->set_date_on_sale_from( $price->date_on_sale_from );
					$variation->set_date_on_sale_to( $price->date_on_sale_to );
					$variation->set_tax_class( $price->tax_class );
				}

				$affected_variation_ids[] = $variation->save();
			} else {
				$variation = wc_get_product( $variation_id );
				if (
					$variation instanceof WC_Product_Variation &&
					$wc_product->get_id() === $variation->get_parent_id()
				) {
					$variation->set_parent_id( $wc_product->get_id() );
					$variation->set_attributes( $attributes );
					if ( ProductHelper::quantity_sync_enabled( $wc_product ) ) {
						$variation->set_stock_quantity( $v->quantity );
						$variation->set_weight( $wc_product->get_weight() );
						$variation->set_manage_stock( $wc_product->get_manage_stock() );
					}
					if ( ProductHelper::price_sync_enabled( $wc_product ) ) {
						$price = $wc_product->get_meta( '1984_dk_woo_price' );

						if ( is_object( $price ) ) {
							$variation->set_regular_price( $price->price );
							$variation->set_sale_price( $price->sale_price );
							$variation->set_date_on_sale_from( $price->date_on_sale_from );
							$variation->set_date_on_sale_to( $price->date_on_sale_to );
							$variation->set_tax_class( $price->tax_class );
						}
					}

					$affected_variation_ids[] = $variation->save();
				}
			}
		}

		$variations_to_delete = wc_get_products(
			array(
				'type'    => 'variation',
				'parent'  => $wc_product->get_id(),
				'exclude' => $affected_variation_ids,
				'limit'   => -1,
			)
		);

		foreach ( $variations_to_delete as $vd ) {
			$affected_variation_ids[] = $vd->get_id();
			$vd->delete();
		}

		return $affected_variation_ids;
	}
}
