<?php
/**
 * Date shortcodes.
 *
 * @package DMYIP
 */

declare(strict_types=1);

namespace DMYIP\Shortcodes;

/**
 * Combined date shortcodes.
 */
class Date {

	/**
	 * Register shortcodes.
	 *
	 * @return void
	 */
	public function register(): void {
		add_shortcode( 'date', [ $this, 'current_date' ] );
		add_shortcode( 'monthyear', [ $this, 'month_year' ] );
		add_shortcode( 'nmonthyear', [ $this, 'next_month_year' ] );
		add_shortcode( 'pmonthyear', [ $this, 'prev_month_year' ] );
	}

	/**
	 * Current full date.
	 *
	 * @return string
	 */
	public function current_date(): string {
		return esc_html( date_i18n( 'F j, Y' ) );
	}

	/**
	 * Current month and year.
	 *
	 * @return string
	 */
	public function month_year(): string {
		return esc_html( ucfirst( date_i18n( 'F Y' ) ) );
	}

	/**
	 * Next month and year.
	 *
	 * @return string
	 */
	public function next_month_year(): string {
		return esc_html( ucfirst( date_i18n( 'F Y', strtotime( '+1 month' ) ) ) );
	}

	/**
	 * Previous month and year.
	 *
	 * @return string
	 */
	public function prev_month_year(): string {
		return esc_html( ucfirst( date_i18n( 'F Y', strtotime( '-1 month' ) ) ) );
	}
}
