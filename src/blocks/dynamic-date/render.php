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

/**
 * Calculate age from a birth date.
 *
 * @param string $date   Birth date string.
 * @param string $format Format: 'y', 'ym', or 'ymd'.
 * @return string Formatted age string.
 */
function dmyip_block_render_age( $date, $format = 'y' ) {
	if ( empty( $date ) ) {
		return '0';
	}

	$birth_timestamp = strtotime( $date );
	if ( false === $birth_timestamp ) {
		return '0';
	}

	$birth = new DateTime( gmdate( 'Y-m-d', $birth_timestamp ) );
	$today = new DateTime( gmdate( 'Y-m-d' ) );
	$diff  = $today->diff( $birth );

	switch ( $format ) {
		case 'ymd':
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
			return implode( ', ', $parts ) ?: '0 days';

		case 'ym':
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
			return implode( ', ', $parts ) ?: '0 months';

		case 'y':
		default:
			return (string) $diff->y;
	}
}

/**
 * Get the date output based on type.
 *
 * @param string $type   Date type.
 * @param string $format Custom format.
 * @param int    $offset Offset value.
 * @param string $date   Target date.
 * @return string
 */
function dmyip_block_get_output( $type, $format, $offset, $date ) {
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

		case 'age':
			return dmyip_block_render_age( $date, 'y' );

		case 'age_ym':
			return dmyip_block_render_age( $date, 'ym' );

		case 'age_ymd':
			return dmyip_block_render_age( $date, 'ymd' );

		default:
			return date_i18n( 'Y' );
	}
}

// Get attributes with defaults.
$type   = $attributes['type'] ?? 'year';
$format = $attributes['format'] ?? '';
$offset = $attributes['offset'] ?? 0;
$date   = $attributes['date'] ?? '';

// Get the output.
$output = dmyip_block_get_output( $type, $format, $offset, $date );

// Render the block.
printf(
	'<p %s>%s</p>',
	get_block_wrapper_attributes(),
	esc_html( $output )
);
