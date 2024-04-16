<?php

declare(strict_types = 1);

namespace NineteenEightyFour\NineteenEightyWoo\Cron;

use NineteenEightyFour\NineteenEightyWoo\Import\Products as ImportProducts;
use WC_Product_Query;

/**
 * The EveryMinute cron class
 *
 * Handles the cront task that is run every minute. This is intended for any
 * high-frequency task done by the plugin.
 */
class EveryMinute {
	/**
	 * Run the every-minute wp-cron task
	 *
	 * Fetches product that have not yet been downstream synced from the DK API.
	 */
	public static function run(): void {
		$products_without_downstream_sync = new WC_Product_Query(
			array(
				// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
				'meta_query' => array(
					'key'     => 'last_downstream_sync',
					'compare' => 'NOT EXISTS',
				),
			)
		);

		foreach ( $products_without_downstream_sync as $wc_product ) {
			ImportProducts::save_from_dk( $wc_product->get_sku() );
		}
	}
}
