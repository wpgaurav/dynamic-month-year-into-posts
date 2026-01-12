<?php
/**
 * Post date shortcodes.
 *
 * @package DMYIP
 */

declare(strict_types=1);

namespace DMYIP\Shortcodes;

/**
 * Post-specific date shortcodes.
 */
class PostDate {

	/**
	 * Register shortcodes.
	 *
	 * @return void
	 */
	public function register(): void {
		add_shortcode( 'datepublished', [ $this, 'published' ] );
		add_shortcode( 'datemodified', [ $this, 'modified' ] );
	}

	/**
	 * Post publication date.
	 *
	 * @return string
	 */
	public function published(): string {
		$published_timestamp = get_the_time( 'U' );
		$date_format         = get_option( 'date_format' );

		if ( ! $published_timestamp ) {
			return '';
		}

		return date_i18n( $date_format, (int) $published_timestamp );
	}

	/**
	 * Post modified date.
	 *
	 * @return string
	 */
	public function modified(): string {
		$modified_timestamp = get_the_modified_time( 'U' );
		$date_format        = get_option( 'date_format' );

		if ( ! $modified_timestamp ) {
			return '';
		}

		return date_i18n( $date_format, (int) $modified_timestamp );
	}
}
