<?php

declare(strict_types = 1);

namespace NineteenEightyFour\NineteenEightyWoo\Cron;

/**
 * The cron task scheduling class
 */
class Schedule {
	/**
	 * The class constrcutor
	 *
	 * Creates the hooks/actions for the wp-cron events.
	 */
	public function __construct() {
		add_action(
			'1984_dk_woo_every_minute',
			array( 'NineteenEightyFour\NineteenEightyWoo\Cron\EveryMinute', 'run' ),
			10,
			0
		);

		add_action(
			'1984_dk_woo_hourly',
			array( 'NineteenEightyFour\NineteenEightyWoo\Cron\Hourly', 'run' ),
			10,
			0
		);
	}

	/**
	 * Activate scheduled events for the plugin
	 */
	public static function activate(): void {
		wp_schedule_event( time(), 'every_minute', '1984_dk_woo_every_minute' );
		wp_schedule_event( time(), 'hourly', '1984_dk_woo_hourly' );
	}

	/**
	 * Deactivate scheduled events for the plugin
	 */
	public static function deactivate(): void {
		wp_clear_scheduled_hook( '1984_dk_woo_every_minute' );
		wp_clear_scheduled_hook( '1984_dk_woo_hourly' );
	}
}
