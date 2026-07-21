<?php
/**
 * Plugin shortcode registry and scoped renderer.
 *
 * @package DMYIP
 */

declare(strict_types=1);

namespace DMYIP\Shortcodes;

/**
 * Central list of plugin-owned shortcode tags.
 */
final class Registry {

	/**
	 * Plugin shortcode tags.
	 */
	public const TAGS = [
		'year',
		'nyear',
		'nnyear',
		'pyear',
		'ppyear',
		'month',
		'cmonth',
		'mon',
		'cmon',
		'mm',
		'mn',
		'nmonth',
		'cnmonth',
		'nmon',
		'cnmon',
		'pmonth',
		'cpmonth',
		'pmon',
		'cpmon',
		'date',
		'monthyear',
		'nmonthyear',
		'pmonthyear',
		'dt',
		'nd',
		'pd',
		'weekday',
		'wd',
		'blackfriday',
		'cybermonday',
		'daysuntil',
		'dayssince',
		'datepublished',
		'datemodified',
		'age',
		'season',
	];

	/**
	 * Render only this plugin's shortcodes inside arbitrary text.
	 *
	 * @param mixed $content Content value.
	 * @return mixed
	 */
	public static function render( $content ) {
		if ( ! is_string( $content ) || false === strpos( $content, '[' ) ) {
			return $content;
		}

		$pattern = '/' . get_shortcode_regex( self::TAGS ) . '/s';
		$result  = preg_replace_callback(
			$pattern,
			static function ( array $matches ): string {
				return do_shortcode( $matches[0] );
			},
			$content
		);

		return null === $result ? $content : $result;
	}

	/**
	 * Check whether a value contains exactly one plugin shortcode.
	 *
	 * @param string $value Shortcode text.
	 * @return bool
	 */
	public static function is_single_shortcode( string $value ): bool {
		$value   = trim( $value );
		$pattern = '/^' . get_shortcode_regex( self::TAGS ) . '$/s';

		return 1 === preg_match( $pattern, $value );
	}
}
