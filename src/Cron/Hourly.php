<?php

declare(strict_types = 1);

namespace NineteenEightyFour\NineteenEightyWoo\Cron;

use NineteenEightyFour\NineteenEightyWoo\Import\Products as ImportProducts;

/**
 * The Hourly Cron class
 *
 * Handles running the hourly wp-cron job for the plugin.
 */
class Hourly {
	/**
	 * Run hourly task
	 *
	 * Saves all products from the DK API.
	 */
	public static function run(): void {
		ImportProducts::save_all_from_dk();
	}
}
