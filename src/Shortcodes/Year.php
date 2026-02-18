<?php
/**
 * Year shortcodes.
 *
 * @package DMYIP
 */

declare(strict_types=1);

namespace DMYIP\Shortcodes;

/**
 * Year-related shortcodes.
 */
class Year {

	/**
	 * Register shortcodes.
	 *
	 * @return void
	 */
	public function register(): void {
		add_shortcode( 'year', [ $this, 'current_year' ] );
		add_shortcode( 'nyear', [ $this, 'next_year' ] );
		add_shortcode( 'nnyear', [ $this, 'next_next_year' ] );
		add_shortcode( 'pyear', [ $this, 'previous_year' ] );
		add_shortcode( 'ppyear', [ $this, 'previous_previous_year' ] );
	}

	/**
	 * Current year with optional offset.
	 *
	 * @param array<string, mixed>|string $atts Shortcode attributes.
	 * @return string
	 */
	public function current_year( $atts ): string {
		$attributes   = shortcode_atts( [ 'n' => 0 ], $atts );
		$current_year = (int) date_i18n( 'Y' );
		$result       = $current_year + (int) $attributes['n'];

		return esc_html( (string) $result );
	}

	/**
	 * Next year.
	 *
	 * @return string
	 */
	public function next_year(): string {
		return esc_html( (string) ( (int) date_i18n( 'Y' ) + 1 ) );
	}

	/**
	 * Year after next.
	 *
	 * @return string
	 */
	public function next_next_year(): string {
		return esc_html( (string) ( (int) date_i18n( 'Y' ) + 2 ) );
	}

	/**
	 * Previous year.
	 *
	 * @return string
	 */
	public function previous_year(): string {
		return esc_html( (string) ( (int) date_i18n( 'Y' ) - 1 ) );
	}

	/**
	 * Year before previous.
	 *
	 * @return string
	 */
	public function previous_previous_year(): string {
		return esc_html( (string) ( (int) date_i18n( 'Y' ) - 2 ) );
	}
}
