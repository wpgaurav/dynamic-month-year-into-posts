<?php
/**
 * Month shortcodes.
 *
 * @package DMYIP
 */

declare(strict_types=1);

namespace DMYIP\Shortcodes;

/**
 * Month-related shortcodes.
 */
class Month {

	/**
	 * Register shortcodes.
	 *
	 * @return void
	 */
	public function register(): void {
		add_shortcode( 'month', [ $this, 'current_month' ] );
		add_shortcode( 'cmonth', [ $this, 'current_month_caps' ] );
		add_shortcode( 'mon', [ $this, 'current_month_short' ] );
		add_shortcode( 'cmon', [ $this, 'current_month_short_caps' ] );
		add_shortcode( 'mm', [ $this, 'current_month_number_zero' ] );
		add_shortcode( 'mn', [ $this, 'current_month_number' ] );
		add_shortcode( 'nmonth', [ $this, 'next_month' ] );
		add_shortcode( 'cnmonth', [ $this, 'next_month_caps' ] );
		add_shortcode( 'nmon', [ $this, 'next_month_short' ] );
		add_shortcode( 'cnmon', [ $this, 'next_month_short_caps' ] );
		add_shortcode( 'pmonth', [ $this, 'prev_month' ] );
		add_shortcode( 'cpmonth', [ $this, 'prev_month_caps' ] );
		add_shortcode( 'pmon', [ $this, 'prev_month_short' ] );
		add_shortcode( 'cpmon', [ $this, 'prev_month_short_caps' ] );
	}

	/**
	 * Current month full name.
	 *
	 * @return string
	 */
	public function current_month(): string {
		return esc_html( date_i18n( 'F' ) );
	}

	/**
	 * Current month capitalized.
	 *
	 * @return string
	 */
	public function current_month_caps(): string {
		return esc_html( ucfirst( date_i18n( 'F' ) ) );
	}

	/**
	 * Current month short name.
	 *
	 * @return string
	 */
	public function current_month_short(): string {
		return esc_html( date_i18n( 'M' ) );
	}

	/**
	 * Current month short capitalized.
	 *
	 * @return string
	 */
	public function current_month_short_caps(): string {
		return esc_html( ucfirst( date_i18n( 'M' ) ) );
	}

	/**
	 * Current month number with leading zero.
	 *
	 * @return string
	 */
	public function current_month_number_zero(): string {
		return esc_html( date_i18n( 'm' ) );
	}

	/**
	 * Current month number without leading zero.
	 *
	 * @return string
	 */
	public function current_month_number(): string {
		return esc_html( date_i18n( 'n' ) );
	}

	/**
	 * Next month full name.
	 *
	 * @return string
	 */
	public function next_month(): string {
		return esc_html( date_i18n( 'F', mktime( 0, 0, 0, (int) gmdate( 'n' ) + 1, 1 ) ) );
	}

	/**
	 * Next month capitalized.
	 *
	 * @return string
	 */
	public function next_month_caps(): string {
		return esc_html( ucfirst( date_i18n( 'F', mktime( 0, 0, 0, (int) gmdate( 'n' ) + 1, 1 ) ) ) );
	}

	/**
	 * Next month short name.
	 *
	 * @return string
	 */
	public function next_month_short(): string {
		return esc_html( date_i18n( 'M', mktime( 0, 0, 0, (int) gmdate( 'n' ) + 1, 1 ) ) );
	}

	/**
	 * Next month short capitalized.
	 *
	 * @return string
	 */
	public function next_month_short_caps(): string {
		return esc_html( ucfirst( date_i18n( 'M', mktime( 0, 0, 0, (int) gmdate( 'n' ) + 1, 1 ) ) ) );
	}

	/**
	 * Previous month full name.
	 *
	 * @return string
	 */
	public function prev_month(): string {
		return esc_html( date_i18n( 'F', strtotime( 'previous month' ) ) );
	}

	/**
	 * Previous month capitalized.
	 *
	 * @return string
	 */
	public function prev_month_caps(): string {
		return esc_html( ucfirst( date_i18n( 'F', strtotime( 'previous month' ) ) ) );
	}

	/**
	 * Previous month short name.
	 *
	 * @return string
	 */
	public function prev_month_short(): string {
		return esc_html( date_i18n( 'M', strtotime( 'previous month' ) ) );
	}

	/**
	 * Previous month short capitalized.
	 *
	 * @return string
	 */
	public function prev_month_short_caps(): string {
		return esc_html( ucfirst( date_i18n( 'M', strtotime( 'previous month' ) ) ) );
	}
}
