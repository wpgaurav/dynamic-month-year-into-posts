<?php
/**
 * Season shortcodes.
 *
 * @package DMYIP
 */

declare(strict_types=1);

namespace DMYIP\Shortcodes;

/**
 * Season-related shortcodes.
 */
class Season {

	/**
	 * Register shortcodes.
	 *
	 * @return void
	 */
	public function register(): void {
		add_shortcode( 'season', [ $this, 'current_season' ] );
	}

	/**
	 * Get current season.
	 *
	 * Supports both Northern and Southern hemispheres.
	 * Northern hemisphere (default): Spring (Mar-May), Summer (Jun-Aug), Fall (Sep-Nov), Winter (Dec-Feb)
	 * Southern hemisphere: Seasons are reversed.
	 *
	 * @param array<string, mixed>|string $atts Shortcode attributes.
	 * @return string
	 */
	public function current_season( $atts ): string {
		$attributes = shortcode_atts(
			[
				'region' => 'north',
			],
			$atts
		);

		$region = strtolower( (string) $attributes['region'] );
		$month  = (int) date_i18n( 'n' );

		$season = $this->get_season_for_month( $month );

		// Reverse seasons for Southern hemisphere.
		if ( 'south' === $region || 'southern' === $region ) {
			$season = $this->get_opposite_season( $season );
		}

		return esc_html( $this->get_localized_season_name( $season ) );
	}

	/**
	 * Get season for a given month (Northern hemisphere).
	 *
	 * @param int $month Month number (1-12).
	 * @return string Season identifier.
	 */
	private function get_season_for_month( int $month ): string {
		switch ( $month ) {
			case 3:
			case 4:
			case 5:
				return 'spring';
			case 6:
			case 7:
			case 8:
				return 'summer';
			case 9:
			case 10:
			case 11:
				return 'fall';
			default: // 12, 1, 2.
				return 'winter';
		}
	}

	/**
	 * Get opposite season (for Southern hemisphere).
	 *
	 * @param string $season Season identifier.
	 * @return string Opposite season identifier.
	 */
	private function get_opposite_season( string $season ): string {
		$opposites = [
			'spring' => 'fall',
			'summer' => 'winter',
			'fall'   => 'spring',
			'winter' => 'summer',
		];

		return isset( $opposites[ $season ] ) ? $opposites[ $season ] : $season;
	}

	/**
	 * Get localized season name.
	 *
	 * @param string $season Season identifier.
	 * @return string Localized season name.
	 */
	private function get_localized_season_name( string $season ): string {
		switch ( $season ) {
			case 'spring':
				return __( 'Spring', 'dynamic-month-year-into-posts' );
			case 'summer':
				return __( 'Summer', 'dynamic-month-year-into-posts' );
			case 'fall':
				return __( 'Fall', 'dynamic-month-year-into-posts' );
			case 'winter':
				return __( 'Winter', 'dynamic-month-year-into-posts' );
			default:
				return '';
		}
	}
}
