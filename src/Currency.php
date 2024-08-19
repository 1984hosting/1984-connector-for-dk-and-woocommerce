<?php

declare(strict_types = 1);

namespace NineteenEightyFour\NineteenEightyWoo;

use NineteenEightyFour\NineteenEightyWoo\Brick\Math\BigDecimal;
use NineteenEightyFour\NineteenEightyWoo\Brick\Math\RoundingMode;
use WP_Error;

/**
 * The Currency Class
 *
 * Handles currenty conversion and rates. This one is important when the
 * WooCommerce uses a different currency from DK.
 */
class Currency {
	const BASE_CURRENCY       = 'ISK';
	const CURRENCY_CODE_REGEX = '/^[A-Z|a-z]{3}$/';

	/**
	 * Set a currency rate
	 *
	 * This sets a currency rate against the base currency (ISK)
	 *
	 * @param string    $currency The 3 digit ISO currency code such as EUR or USD.
	 * @param int|float $rate The currency rate against the base currency.
	 *
	 * @return bool|WP_Error True or false depending on if the value was
	 *                       successfully written into the database or not,
	 *                       WP_Error on error.
	 */
	public static function set_rate(
		string $currency,
		int|float $rate
	): bool|WP_Error {
		if ( preg_match( self::CURRENCY_CODE_REGEX, $currency ) !== 1 ) {
			return self::invalid_currency_code_error( $currency );
		}

		$option_name = '1984_woo_dk_currency_rate_' . mb_strtolower( $currency );

		$float_value = (float) $rate;

		return update_option( $option_name, $float_value );
	}

	/**
	 * Get a currency rate
	 *
	 * Gets a currency rate. The rate is against the base currency.
	 *
	 * @param string $currency The 3 digit ISO currency code such as EUR or USD.
	 *
	 * @return float|WP_Error A floating point representation of the currency
	 *                        rate on success. WP_Error on error.
	 */
	public static function get_rate(
		string $currency
	): float|WP_Error {
		if ( preg_match( self::CURRENCY_CODE_REGEX, $currency ) !== 1 ) {
			return self::invalid_currency_code_error( $currency );
		}

		$option_name = '1984_woo_dk_currency_rate_' . mb_strtolower( $currency );

		$rate = get_option( $option_name, 0 );

		if ( ! $rate ) {
			return self::rate_not_set_error( $currency );
		}

		return (float) get_option( $option_name, 0 );
	}

	/**
	 * Convert an amount between two different currencies
	 *
	 * @param int|float   $amount The currency amount.
	 * @param string      $from The 3 digit ISO code for the currency to convert from.
	 * @param string|null $to The 3 digit ISO code for the currency to convert to.
	 *
	 * @return float|WP_Error A floating point representation of the converted
	 *                        amount, or WP_Error in case of error.
	 */
	public static function convert(
		int|float $amount,
		string $from,
		string|null $to = null
	): float|WP_Error {
		if ( $from === $to ) {
			return (float) $amount;
		}

		if ( is_null( $to ) ) {
			$to = Config::get_dk_currency();
		}

		if ( preg_match( self::CURRENCY_CODE_REGEX, $from ) !== 1 ) {
			return self::invalid_currency_code_error( $from );
		}

		if ( preg_match( self::CURRENCY_CODE_REGEX, $to ) !== 1 ) {
			return self::invalid_currency_code_error( $to );
		}

		$from_rate = get_option(
			'1984_woo_dk_currency_rate_' . mb_strtolower( $from ),
		);

		if ( ! $from_rate ) {
			return self::rate_not_set_error( $from );
		}

		$amount_decimal    = BigDecimal::of( $amount );
		$from_rate_decimal = BigDecimal::of( $from_rate );

		$base_currency_amount = $amount_decimal->multipliedBy(
			$from_rate_decimal
		);

		if ( self::BASE_CURRENCY === $to ) {
			return $base_currency_amount->toFloat();
		}

		$to_rate = get_option(
			'1984_woo_dk_currency_rate_' . mb_strtolower( $to )
		);

		if ( ! $to_rate ) {
			return self::rate_not_set_error( $to );
		}

		return $base_currency_amount->multipliedBy(
			BigDecimal::of( 1 )->dividedBy(
				$to_rate,
				12,
				RoundingMode::HALF_UP
			)
		)->toFloat();
	}

	/**
	 * Generate a WP_Error about an invalid currency code
	 *
	 * @param string $currency_code The currency code as it was entered.
	 */
	public static function invalid_currency_code_error(
		string $currency_code
	): WP_Error {
		return new WP_Error(
			'currency-code-invalid',
			sprintf(
				// Translators: The %s stands for the currency code.
				__(
					'The currency code ‘%s’ is invalid.',
					'1984-dk-woo'
				),
				strtoupper( $currency_code )
			)
		);
	}

	/**
	 * Generate a WP_Error about a currency rate not having been set
	 *
	 * @param string $currency_code The currency code as it was entered.
	 */
	public static function rate_not_set_error(
		string $currency_code
	): WP_Error {
		return new WP_Error(
			'currency-rate-not-set',
			sprintf(
				// Translators: The %s stands for the currency code.
				__(
					'The currency rate for ‘%s’ has not been set.',
					'1984-dk-woo'
				),
				strtoupper( $currency_code )
			)
		);
	}
}
