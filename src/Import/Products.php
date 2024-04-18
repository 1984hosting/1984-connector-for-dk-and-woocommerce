<?php

declare(strict_types = 1);

namespace NineteenEightyFour\NineteenEightyWoo\Import;

use DateTime;
use NineteenEightyFour\NineteenEightyWoo\Service\DKApiRequest;
use stdClass;
use WC_DateTime;
use WC_Product;
use WP_Error;
use WC_Tax;

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
		'Inactive,Deleted,PropositionDateTo,PropositionDateFrom';

	/**
	 * Save all products from DK
	 *
	 * This should be run at least nightly as a wp-cron job.
	 */
	public static function save_all_from_dk(): void {
		define( 'DOING_DK_SYNC', true );

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

		$result = $api_request->get_result(
			self::API_PATH
			.
			'?include=' . self::INCLUDE_PROPERTIES,
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

		if (
			0 === $wc_product->get_id() &&
			(
				true === $json_object->Inactive ||
				true === property_exists( $json_object, 'Deleted' ) ||
				false === $json_object->ShowItemInWebShop
			)
		) {
			return false;
		}

		if (
			true === $json_object->Inactive
		) {
			$wc_product->set_status( 'Draft' );
		} else {
			$wc_product->set_status( 'Publish' );
		}

		if (
			property_exists( $json_object, 'Deleted' ) &&
			true === $json_object->Deleted
		) {
			wp_delete_post( $wc_product->get_id() );
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
			// Products that are not in the WooCommerce shop already and are
			// deleted, not for online shops or inactive are ditched here.
			if (
				true === property_exists( $json_object, 'Deleted' ) ||
				false === $json_object->ShowItemInWebShop ||
				true === $json_object->Inactive
			) {
				return false;
			}

			// Initiate the product if it does not exsist.
			$wc_product = new WC_Product();
			$wc_product->set_sku( $json_object->ItemCode );

			// New products retreived from DK are assumed to have price and
			// stock sync enabled. (We don't want this to be null).
			$wc_product->update_meta_data( '1984_woo_dk_price_sync', true );
			$wc_product->update_meta_data( '1984_woo_dk_stock_sync', true );
		} else {
			$wc_product = wc_get_product( $product_id );
		}

		if ( true === property_exists( $json_object, 'Description' ) ) {
			$wc_product->set_name( $json_object->Description );
		}

		// Sync stock status if that is enabled for the product.
		if (
			0 === $product_id ||
			true === (bool) $wc_product->get_meta(
				'1984_woo_dk_stock_sync',
				true,
				'edit'
			)
		) {
			$wc_product->set_manage_stock( true );
			$wc_product->set_stock_quantity(
				$json_object->TotalQuantityInWarehouse
			);

			// 'Inventiry' is the spelling that DK uses. I'm dead serious.
			if ( true === $json_object->AllowNegativeInventiry ) {
				$wc_product->set_backorders( 'yes' );
			} else {
				$wc_product->set_backorders( 'no' );
			}
		}

		// Take in descriptions if they have been defined in DK.
		if ( false === empty( $json_object->ExtraDesc1 ) ) {
			$wc_product->set_description( $json_object->ExtraDesc1 );
		}
		if ( false === empty( $json_object->ExtraDesc2 ) ) {
			$wc_product->set_short_description( $json_object->ExtraDesc2 );
		}

		// Sync pricing if that is enabled.
		if (
			0 === $product_id ||
			true === (bool) $wc_product->get_meta( '1984_woo_dk_price_sync' )
		) {
			if ( true === wc_prices_include_tax() ) {
				$wc_product->set_tax_class(
					self::tax_class_from_rate( $json_object->TaxPercent )
				);

				$wc_product->set_regular_price( $json_object->UnitPrice1WithTax );

				if ( 0 > $json_object->PropositionPrice ) {
					$wc_product->set_sale_price(
						$json_object->PropositionPrice * ( 1 + ( $json_object->TaxPercent / 100 ) )
					);
				} else {
					$wc_product->set_sale_price( '' );
				}
			} else {
				$wc_product->set_regular_price( $json_object->UnitPrice1 );

				if ( 0 > $json_object->PropositionPrice ) {
					$wc_product->set_sale_price( $json_object->PropositionPrice );
				} else {
					$wc_product->set_sale_price( '' );
				}
			}

			if (
				true === property_exists(
					$json_object,
					'PropositionDateFrom'
				)
			) {
				$wc_product->set_date_on_sale_from(
					new WC_DateTime( $json_object->PropositionDateFrom )
				);
			}
			if (
				true === property_exists(
					$json_object,
					'PropositionDateTo'
				)
			) {
				$wc_product->set_date_on_sale_to(
					new WC_DateTime( $json_object->PropositionDateTo )
				);
			}
		}

		$date_and_time = new DateTime();

		$wc_product->update_meta_data(
			'last_downstream_sync',
			$date_and_time->format( 'U' )
		);

		return $wc_product;
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
		if ( 0.0 === $percentage ) {
			return 'Zero rate';
		}

		$tax_rates = array( '' => array_values( WC_Tax::get_base_tax_rates( '' ) )[0] );
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
}
