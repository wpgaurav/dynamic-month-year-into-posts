<?php
/**
 * Event shortcodes.
 *
 * @package DMYIP
 */

declare(strict_types=1);

namespace DMYIP\Shortcodes;

/**
 * Special event date shortcodes.
 */
class Events {

	/**
	 * Register shortcodes.
	 *
	 * @return void
	 */
	public function register(): void {
		add_shortcode( 'blackfriday', [ $this, 'black_friday' ] );
		add_shortcode( 'cybermonday', [ $this, 'cyber_monday' ] );
	}

	/**
	 * Black Friday date (day after Thanksgiving).
	 *
	 * @return string
	 */
	public function black_friday(): string {
		$year         = gmdate( 'Y' );
		$thanksgiving = strtotime( "fourth thursday of november {$year}" );

		if ( ! $thanksgiving ) {
			return '';
		}

		$black_friday = strtotime( '+1 day', $thanksgiving );

		return esc_html( date_i18n( 'F j', (int) $black_friday ) );
	}

	/**
	 * Cyber Monday date (Monday after Thanksgiving).
	 *
	 * @return string
	 */
	public function cyber_monday(): string {
		$year         = gmdate( 'Y' );
		$thanksgiving = strtotime( "fourth thursday of november {$year}" );

		if ( ! $thanksgiving ) {
			return '';
		}

		$cyber_monday = strtotime( '+4 days', $thanksgiving );

		return esc_html( date_i18n( 'F j', (int) $cyber_monday ) );
	}
}
