<?php
/**
 * Countdown shortcodes.
 *
 * @package DMYIP
 */

declare(strict_types=1);

namespace DMYIP\Shortcodes;

/**
 * Countdown-related shortcodes.
 */
class Countdown {

	/**
	 * Register shortcodes.
	 *
	 * @return void
	 */
	public function register(): void {
		add_shortcode( 'daysuntil', [ $this, 'days_until' ] );
		add_shortcode( 'dayssince', [ $this, 'days_since' ] );
		add_shortcode( 'age', [ $this, 'age' ] );
	}

	/**
	 * Days until a specific date.
	 *
	 * @param array<string, mixed>|string $atts Shortcode attributes.
	 * @return string
	 */
	public function days_until( $atts ): string {
		$attributes = shortcode_atts( [ 'date' => '' ], $atts );

		if ( empty( $attributes['date'] ) ) {
			return '';
		}

		$target_timestamp = strtotime( $attributes['date'] );

		if ( false === $target_timestamp ) {
			return '';
		}

		$today_timestamp = strtotime( 'today' );

		if ( false === $today_timestamp ) {
			return '';
		}

		$diff_seconds = $target_timestamp - $today_timestamp;
		$diff_days    = (int) floor( $diff_seconds / DAY_IN_SECONDS );

		return esc_html( (string) $diff_days );
	}

	/**
	 * Days since a specific date.
	 *
	 * @param array<string, mixed>|string $atts Shortcode attributes.
	 * @return string
	 */
	public function days_since( $atts ): string {
		$attributes = shortcode_atts( [ 'date' => '' ], $atts );

		if ( empty( $attributes['date'] ) ) {
			return '';
		}

		$target_timestamp = strtotime( $attributes['date'] );

		if ( false === $target_timestamp ) {
			return '';
		}

		$today_timestamp = strtotime( 'today' );

		if ( false === $today_timestamp ) {
			return '';
		}

		$diff_seconds = $today_timestamp - $target_timestamp;
		$diff_days    = (int) floor( $diff_seconds / DAY_IN_SECONDS );

		return esc_html( (string) $diff_days );
	}

	/**
	 * Calculate age from a birth date.
	 *
	 * Formats:
	 * - 'y' (default): Years only (e.g., "34")
	 * - 'ym': Years and months (e.g., "34 years, 5 months")
	 * - 'ymd': Years, months, and days (e.g., "34 years, 5 months, 12 days")
	 *
	 * Ordinal suffix:
	 * - 'ordinal' or 'rank': When "true", adds st/nd/rd/th suffix (e.g., "34th")
	 *
	 * @param array<string, mixed>|string $atts Shortcode attributes.
	 * @return string
	 */
	public function age( $atts ): string {
		$attributes = shortcode_atts(
			[
				'date'    => '',
				'format'  => 'y',
				'ordinal' => '',
				'rank'    => '',
			],
			$atts
		);

		if ( empty( $attributes['date'] ) ) {
			return '';
		}

		$birth_timestamp = strtotime( $attributes['date'] );

		if ( false === $birth_timestamp ) {
			return '';
		}

		$birth = new \DateTime( gmdate( 'Y-m-d', $birth_timestamp ) );
		$today = new \DateTime( gmdate( 'Y-m-d' ) );
		$diff  = $today->diff( $birth );

		$format = strtolower( (string) $attributes['format'] );

		// Check if ordinal suffix is requested (supports both "ordinal" and "rank" attributes).
		$show_ordinal = 'true' === strtolower( (string) $attributes['ordinal'] )
			|| 'true' === strtolower( (string) $attributes['rank'] );

		switch ( $format ) {
			case 'ymd':
				return $this->format_age_ymd( $diff );
			case 'ym':
				return $this->format_age_ym( $diff );
			default:
				if ( $show_ordinal ) {
					return esc_html( $this->get_ordinal_suffix( $diff->y ) );
				}
				return esc_html( (string) $diff->y );
		}
	}

	/**
	 * Get ordinal suffix for a number (1st, 2nd, 3rd, 4th, etc.).
	 *
	 * @param int $number The number to add suffix to.
	 * @return string Number with ordinal suffix.
	 */
	private function get_ordinal_suffix( int $number ): string {
		$abs_number = abs( $number );

		// Special case for 11, 12, 13 - always use "th".
		if ( $abs_number % 100 >= 11 && $abs_number % 100 <= 13 ) {
			return $number . 'th';
		}

		switch ( $abs_number % 10 ) {
			case 1:
				return $number . 'st';
			case 2:
				return $number . 'nd';
			case 3:
				return $number . 'rd';
			default:
				return $number . 'th';
		}
	}

	/**
	 * Format age as years, months, and days.
	 *
	 * @param \DateInterval $diff The date interval.
	 * @return string
	 */
	private function format_age_ymd( \DateInterval $diff ): string {
		$parts = [];

		if ( $diff->y > 0 ) {
			$parts[] = sprintf(
				/* translators: %d: number of years */
				_n( '%d year', '%d years', $diff->y, 'dynamic-month-year-into-posts' ),
				$diff->y
			);
		}

		if ( $diff->m > 0 ) {
			$parts[] = sprintf(
				/* translators: %d: number of months */
				_n( '%d month', '%d months', $diff->m, 'dynamic-month-year-into-posts' ),
				$diff->m
			);
		}

		if ( $diff->d > 0 ) {
			$parts[] = sprintf(
				/* translators: %d: number of days */
				_n( '%d day', '%d days', $diff->d, 'dynamic-month-year-into-posts' ),
				$diff->d
			);
		}

		return esc_html( implode( ', ', $parts ) );
	}

	/**
	 * Format age as years and months.
	 *
	 * @param \DateInterval $diff The date interval.
	 * @return string
	 */
	private function format_age_ym( \DateInterval $diff ): string {
		$parts = [];

		if ( $diff->y > 0 ) {
			$parts[] = sprintf(
				/* translators: %d: number of years */
				_n( '%d year', '%d years', $diff->y, 'dynamic-month-year-into-posts' ),
				$diff->y
			);
		}

		if ( $diff->m > 0 ) {
			$parts[] = sprintf(
				/* translators: %d: number of months */
				_n( '%d month', '%d months', $diff->m, 'dynamic-month-year-into-posts' ),
				$diff->m
			);
		}

		return esc_html( implode( ', ', $parts ) );
	}
}
