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
use WC_Product_Query;
use WC_Product_Variable;
use WP_Error;
use WC_Tax;
use WC_Product_Variation;
use WP_Query;

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
		if ( false === defined( 'DOING_DK_SYNC' ) ) {
			define( 'DOING_DK_SYNC', true );
		}

		$json_objects = self::get_all_from_dk();

		if ( true === is_array( $json_objects ) ) {
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

		if ( 200 !== $result->response_code ) {
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

		if ( false === is_object( $json_object ) ) {
			return false;
		}

		$wc_product = self::json_to_product( $json_object );

		if ( false === $wc_product ) {
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

		if ( 200 !== $result->response_code ) {
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

		if ( 0 === $product_id ) {
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
			true === $json_object->Deleted
		) {
			return false;
		}

		if ( $json_object->Inactive ) {
			return false;
		}

		if ( strtolower( Config::get_shipping_sku() ) === strtolower( $json_object->ItemCode ) ) {
			return false;
		}

		if ( strtolower( Config::get_cost_sku() ) === strtolower( $json_object->ItemCode ) ) {
			return false;
		}

		if ( ! $json_object->ShowItemInWebShop && ! Config::get_import_nonweb_products() ) {
			return false;
		}

		$wc_product = new WC_Product();

		$wc_product->set_sku( $json_object->ItemCode );

		if ( true === $json_object->IsVariation ) {
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
			$wc_product->update_meta_data(
				'1984_dk_woo_variations',
				self::merge_variations( $json_object )
			);
		} else {
			$wc_product->update_meta_data( '1984_dk_woo_origin', 'product' );
			$wc_product->update_meta_data( '1984_dk_woo_variant_code', '' );
			$wc_product->update_meta_data( '1984_dk_woo_variations', '' );
		}

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
				true === $json_object->Deleted
			)
		) {
			wp_delete_post( $wc_product->get_id() );
			return false;
		}

		if ( true === $json_object->IsVariation ) {
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
			$wc_product->update_meta_data(
				'1984_dk_woo_variations',
				self::merge_variations( $json_object )
			);
		} else {
			$wc_product->update_meta_data( '1984_dk_woo_origin', 'product' );
			$wc_product->update_meta_data( '1984_dk_woo_variant_code', '' );
			$wc_product->update_meta_data( '1984_dk_woo_variations', '' );
		}


		if ( true === $json_object->ShowItemInWebShop ) {
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

			if ( 0 < $sale_price_before_tax ) {
				$sale_price = self::calculate_price_after_tax(
					$sale_price_before_tax,
					$json_object->TaxPercent
				);
			} else {
				$sale_price = '';
			}
		} else {
			$price = $price_before_tax;

			if ( 0 < $sale_price_before_tax ) {
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
		if ( 0 === $tax_rate ) {
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
		if ( true === $json_object->AllowNegativeInventiry ) {
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
		if ( true === is_null( WC()->countries ) ) {
			return '';
		}

		if ( 0.0 === $percentage ) {
			return 'Zero rate';
		}

		$tax_rates = array(
			'' => array_values( WC_Tax::get_base_tax_rates( '' ) )[0],
		);

		foreach ( WC_Tax::get_tax_classes() as $tax_class ) {
			$values = array_values(
				WC_Tax::get_base_tax_rates( $tax_class )
			);
			if ( false === empty( $values ) ) {
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

	public static function merge_variations( stdClass $json_object ) {
		$warehouses       = $json_object->Warehouses;
		$variations_array = array();

		foreach ( $warehouses as $w ) {
			foreach ( $w->Variations as $v ) {
				foreach ( $variations_array as $key => $s ) {
					if ( $v->Code === $s->Code && $v->Code2 === $s->Code2 ) {
						$variations_array[ $key ] = array(
							'code_1'        => (string) $v->Code,
							'description_1' => (string) $v->Description,
							'code_2'        => (string) $v->Code2,
							'description_2' => (string) $v->Description2,
							'quantity'      => BigDecimal::of( $v->Quantity )->plus( $s->Quantity )->toFloat(),
						);
						break;
					}
				}
				$variations_array[] = (object) array(
					'code_1'        => (string) $v->Code,
					'description_1' => (string) $v->Description,
					'code_2'        => (string) $v->Code2,
					'description_2' => (string) $v->Description2,
					'quantity'      => (float) $v->Quantity,
				);
			}
		}

		return $variations_array;
	}

	public static function variations_from_json( stdClass $json_object, int $parent_id ) {
		$parent_product = wc_get_product( $parent_id );

		if ( ! $parent_product instanceof WC_Product ) {
			return false;
		}

		$warehouses       = $json_object->warehouses;
		$variations_array = array();

		foreach ( $warehouses as $w ) {
			foreach ( $w->Variations as $v ) {
				// If this variant code already exsists in the array, we add to
				// the quantity, otherwise, we create a new object.
				if ( array_key_exists( $v->Code, $variations_array ) ) {
					$current_quantity = BigDecimal::of( $variations_array[ $v->Code ]->Quantity );
					$new_quantity     = $current_quantity->plus( $v->Quantity );

					$variations_array[ $v->Code ] = (object) array(
						'description' => $v->Description,
						'quantity'    => $new_quantity->toFloat(),
					);
				} else {
					$variations_array[ $v->Code ] = (object) array(
						'description' => $v->Description,
						'quantity'    => $v->Quantity,
					);
				}
			}
		}

		foreach ( $variations_array as $code => $variation_object ) {
			$variation_args = array(
				'post_type'   => 'product_variation',
				'post_parent' => $parent_id,
				'meta_key'    => '1984_dk_woo_origin',
				'meta_value'  => 'product_variation',
				'fields'      => 'ids',
			);

			$variation_query = new WP_Query( $variation_args );

			if ( $variation_query->have_posts() ) {
				$wc_product_variation = new WC_Product_Variation(
					$variation_query->get_posts()[0]
				);
			} else {
				$wc_product_variation = new WC_Product_Variation();

				$wc_product_variation->set_parent_id( $parent_id );

				$wc_product_variation->update_meta_data(
					'1984_dk_woo_origin',
					'product_variation'
				);

				$wc_product_variation->update_meta_data(
					'1984_dk_woo_variation_code',
					$code
				);
			}

			$wc_product_variation->set_description(
				$variation_object->description
			);

			$wc_product_variation->set_stock_quantity(
				$variation_object->quantity
			);

			$wc_product_variation->set_price(
				$parent_product->get_regular_price()
			);

			$wc_product_variation->set_sale_price(
				$parent_product->get_sale_price()
			);

			$wc_product_variation->save();
		}
	}
}
