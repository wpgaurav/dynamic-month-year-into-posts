<?php
/**
 * Day shortcodes.
 *
 * @package DMYIP
 */

declare(strict_types=1);

namespace DMYIP\Shortcodes;

/**
 * Day-related shortcodes.
 */
class Day {

	/**
	 * Register shortcodes.
	 *
	 * @return void
	 */
	public function register(): void {
		add_shortcode( 'dt', [ $this, 'current_day' ] );
		add_shortcode( 'nd', [ $this, 'next_day' ] );
		add_shortcode( 'pd', [ $this, 'prev_day' ] );
		add_shortcode( 'weekday', [ $this, 'current_weekday' ] );
		add_shortcode( 'wd', [ $this, 'current_weekday_short' ] );
	}

	/**
	 * Current day of month.
	 *
	 * @return string
	 */
	public function current_day(): string {
		return esc_html( date_i18n( 'j' ) );
	}

	/**
	 * Next day number.
	 *
	 * @return string
	 */
	public function next_day(): string {
		return esc_html( date_i18n( 'j', strtotime( '+1 day' ) ) );
	}

	/**
	 * Previous day number.
	 *
	 * @return string
	 */
	public function prev_day(): string {
		return esc_html( date_i18n( 'j', strtotime( '-1 day' ) ) );
	}

	/**
	 * Current weekday full name.
	 *
	 * @return string
	 */
	public function current_weekday(): string {
		return esc_html( date_i18n( 'l' ) );
	}

	/**
	 * Current weekday short name.
	 *
	 * @return string
	 */
	public function current_weekday_short(): string {
		return esc_html( date_i18n( 'D' ) );
	}
}
