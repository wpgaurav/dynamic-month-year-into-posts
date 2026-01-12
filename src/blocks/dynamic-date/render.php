<?php
/**
 * Dynamic Date block server-side render.
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Block default content.
 * @var WP_Block $block      Block instance.
 *
 * @package DMYIP
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Wrap in IIFE to avoid global variable pollution.
call_user_func(
	static function ( $attributes ) {
		$dmyip_type   = $attributes['type'] ?? 'year';
		$dmyip_format = $attributes['format'] ?? '';
		$dmyip_offset = $attributes['offset'] ?? 0;
		$dmyip_date   = $attributes['date'] ?? '';

		/**
		 * Get the date output based on type.
		 *
		 * @param string $type   Date type.
		 * @param string $format Custom format.
		 * @param int    $offset Offset value.
		 * @param string $date   Target date for countdown.
		 * @return string
		 */
		$dmyip_get_output = static function ( $type, $format, $offset, $date ) {
			switch ( $type ) {
				case 'year':
					$year = (int) date_i18n( 'Y' ) + (int) $offset;
					return (string) $year;

				case 'nyear':
					return (string) ( (int) date_i18n( 'Y' ) + 1 );

				case 'pyear':
					return (string) ( (int) date_i18n( 'Y' ) - 1 );

				case 'month':
					return date_i18n( 'F' );

				case 'month_short':
					return date_i18n( 'M' );

				case 'month_number':
					return date_i18n( 'n' );

				case 'nmonth':
					return date_i18n( 'F', strtotime( '+1 month' ) );

				case 'pmonth':
					return date_i18n( 'F', strtotime( '-1 month' ) );

				case 'date':
					$date_format = ! empty( $format ) ? $format : 'F j, Y';
					return date_i18n( $date_format );

				case 'monthyear':
					return date_i18n( 'F Y' );

				case 'day':
					return date_i18n( 'j' );

				case 'weekday':
					return date_i18n( 'l' );

				case 'weekday_short':
					return date_i18n( 'D' );

				case 'published':
					$timestamp = get_the_time( 'U' );
					if ( ! $timestamp ) {
						return '';
					}
					return date_i18n( get_option( 'date_format' ), (int) $timestamp );

				case 'modified':
					$timestamp = get_the_modified_time( 'U' );
					if ( ! $timestamp ) {
						return '';
					}
					return date_i18n( get_option( 'date_format' ), (int) $timestamp );

				case 'blackfriday':
					$year         = gmdate( 'Y' );
					$thanksgiving = strtotime( "fourth thursday of november {$year}" );
					if ( ! $thanksgiving ) {
						return '';
					}
					return date_i18n( 'F j', strtotime( '+1 day', $thanksgiving ) );

				case 'cybermonday':
					$year         = gmdate( 'Y' );
					$thanksgiving = strtotime( "fourth thursday of november {$year}" );
					if ( ! $thanksgiving ) {
						return '';
					}
					return date_i18n( 'F j', strtotime( '+4 days', $thanksgiving ) );

				case 'daysuntil':
					if ( empty( $date ) ) {
						return '0';
					}
					$target = strtotime( $date );
					$today  = strtotime( 'today' );
					if ( ! $target || ! $today ) {
						return '0';
					}
					$diff = (int) floor( ( $target - $today ) / DAY_IN_SECONDS );
					return (string) max( 0, $diff );

				case 'dayssince':
					if ( empty( $date ) ) {
						return '0';
					}
					$target = strtotime( $date );
					$today  = strtotime( 'today' );
					if ( ! $target || ! $today ) {
						return '0';
					}
					$diff = (int) floor( ( $today - $target ) / DAY_IN_SECONDS );
					return (string) max( 0, $diff );

				default:
					return date_i18n( 'Y' );
			}
		};

		$dmyip_output = $dmyip_get_output( $dmyip_type, $dmyip_format, $dmyip_offset, $dmyip_date );

		printf(
			'<span %s>%s</span>',
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- get_block_wrapper_attributes() is safe.
			get_block_wrapper_attributes(),
			esc_html( $dmyip_output )
		);
	},
	$attributes
);
