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

// Get attributes with defaults.
$type   = $attributes['type'] ?? 'year';
$format = $attributes['format'] ?? '';
$offset = $attributes['offset'] ?? 0;
$date   = $attributes['date'] ?? '';

/**
 * Get the date output based on type.
 */
switch ( $type ) {
	case 'year':
		$output = (string) ( (int) date_i18n( 'Y' ) + (int) $offset );
		break;

	case 'nyear':
		$output = (string) ( (int) date_i18n( 'Y' ) + 1 );
		break;

	case 'pyear':
		$output = (string) ( (int) date_i18n( 'Y' ) - 1 );
		break;

	case 'month':
		$output = date_i18n( 'F' );
		break;

	case 'month_short':
		$output = date_i18n( 'M' );
		break;

	case 'month_number':
		$output = date_i18n( 'n' );
		break;

	case 'nmonth':
		$output = date_i18n( 'F', strtotime( '+1 month' ) );
		break;

	case 'pmonth':
		$output = date_i18n( 'F', strtotime( '-1 month' ) );
		break;

	case 'date':
		$date_format = ! empty( $format ) ? $format : 'F j, Y';
		$output      = date_i18n( $date_format );
		break;

	case 'monthyear':
		$output = date_i18n( 'F Y' );
		break;

	case 'day':
		$output = date_i18n( 'j' );
		break;

	case 'weekday':
		$output = date_i18n( 'l' );
		break;

	case 'weekday_short':
		$output = date_i18n( 'D' );
		break;

	case 'published':
		$timestamp = get_the_time( 'U' );
		$output    = $timestamp ? date_i18n( get_option( 'date_format' ), (int) $timestamp ) : '';
		break;

	case 'modified':
		$timestamp = get_the_modified_time( 'U' );
		$output    = $timestamp ? date_i18n( get_option( 'date_format' ), (int) $timestamp ) : '';
		break;

	case 'blackfriday':
		$year         = gmdate( 'Y' );
		$thanksgiving = strtotime( "fourth thursday of november {$year}" );
		$output       = $thanksgiving ? date_i18n( 'F j', strtotime( '+1 day', $thanksgiving ) ) : '';
		break;

	case 'cybermonday':
		$year         = gmdate( 'Y' );
		$thanksgiving = strtotime( "fourth thursday of november {$year}" );
		$output       = $thanksgiving ? date_i18n( 'F j', strtotime( '+4 days', $thanksgiving ) ) : '';
		break;

	case 'daysuntil':
		if ( empty( $date ) ) {
			$output = '0';
		} else {
			$target = strtotime( $date );
			$today  = strtotime( 'today' );
			$diff   = ( $target && $today ) ? (int) floor( ( $target - $today ) / DAY_IN_SECONDS ) : 0;
			$output = (string) max( 0, $diff );
		}
		break;

	case 'dayssince':
		if ( empty( $date ) ) {
			$output = '0';
		} else {
			$target = strtotime( $date );
			$today  = strtotime( 'today' );
			$diff   = ( $target && $today ) ? (int) floor( ( $today - $target ) / DAY_IN_SECONDS ) : 0;
			$output = (string) max( 0, $diff );
		}
		break;

	case 'age':
	case 'age_ym':
	case 'age_ymd':
		if ( empty( $date ) ) {
			$output = '0';
		} else {
			$birth_timestamp = strtotime( $date );
			if ( false === $birth_timestamp ) {
				$output = '0';
			} else {
				$birth = new DateTime( gmdate( 'Y-m-d', $birth_timestamp ) );
				$today = new DateTime( gmdate( 'Y-m-d' ) );
				$diff  = $today->diff( $birth );

				if ( 'age_ymd' === $type ) {
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
					$output = implode( ', ', $parts ) ?: '0 days';
				} elseif ( 'age_ym' === $type ) {
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
					$output = implode( ', ', $parts ) ?: '0 months';
				} else {
					$output = (string) $diff->y;
				}
			}
		}
		break;

	default:
		$output = date_i18n( 'Y' );
		break;
}

// Render the block.
printf(
	'<p %s>%s</p>',
	get_block_wrapper_attributes(),
	esc_html( $output )
);
