<?php
/**
 * Site-timezone-aware date calculations.
 *
 * @package DMYIP
 */

declare(strict_types=1);

namespace DMYIP\Date;

use DateInterval;
use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use Exception;

/**
 * Shared date calculation engine.
 */
final class DateEngine {

	/**
	 * Get the configured WordPress timezone.
	 *
	 * @return DateTimeZone
	 */
	public function timezone(): DateTimeZone {
		return wp_timezone();
	}

	/**
	 * Get the current site-local time.
	 *
	 * A filter is provided so automated tests can exercise boundary conditions.
	 *
	 * @return DateTimeImmutable
	 */
	public function now(): DateTimeImmutable {
		$now = apply_filters( 'dmyip_current_datetime', current_datetime() );

		if ( $now instanceof DateTimeInterface ) {
			return ( new DateTimeImmutable( '@' . $now->getTimestamp() ) )->setTimezone( $this->timezone() );
		}

		return new DateTimeImmutable( 'now', $this->timezone() );
	}

	/**
	 * Get the current site-local date at midnight.
	 *
	 * @return DateTimeImmutable
	 */
	public function today(): DateTimeImmutable {
		return $this->now()->setTime( 0, 0, 0 );
	}

	/**
	 * Parse a date in the WordPress timezone.
	 *
	 * ISO calendar dates are parsed strictly. Other formats remain supported for
	 * backward compatibility with existing shortcode content.
	 *
	 * @param string $value Date value.
	 * @return DateTimeImmutable|null
	 */
	public function parse_date( string $value ): ?DateTimeImmutable {
		$value = trim( $value );

		if ( '' === $value ) {
			return null;
		}

		if ( 1 === preg_match( '/^\d{4}-\d{2}-\d{2}$/', $value ) ) {
			$date   = DateTimeImmutable::createFromFormat( '!Y-m-d', $value, $this->timezone() );
			$errors = DateTimeImmutable::getLastErrors();

			if (
				false === $date ||
				( is_array( $errors ) && ( $errors['warning_count'] > 0 || $errors['error_count'] > 0 ) )
			) {
				return null;
			}

			return $date;
		}

		try {
			return ( new DateTimeImmutable( $value, $this->timezone() ) )->setTime( 0, 0, 0 );
		} catch ( Exception $exception ) {
			unset( $exception );
			return null;
		}
	}

	/**
	 * Format a date using WordPress locale and timezone handling.
	 *
	 * @param DateTimeInterface $date   Date to format.
	 * @param string            $format PHP date format.
	 * @return string
	 */
	public function format( DateTimeInterface $date, string $format ): string {
		return wp_date( $format, $date->getTimestamp(), $this->timezone() );
	}

	/**
	 * Return signed calendar days from one date to another.
	 *
	 * Calendar-day arithmetic avoids daylight-saving 23/25-hour day errors.
	 *
	 * @param DateTimeInterface $from Starting date.
	 * @param DateTimeInterface $to   Ending date.
	 * @return int
	 */
	public function calendar_days( DateTimeInterface $from, DateTimeInterface $to ): int {
		$start = ( new DateTimeImmutable( '@' . $from->getTimestamp() ) )->setTimezone( $this->timezone() )->setTime( 0, 0, 0 );
		$end   = ( new DateTimeImmutable( '@' . $to->getTimestamp() ) )->setTimezone( $this->timezone() )->setTime( 0, 0, 0 );

		return (int) $start->diff( $end )->format( '%r%a' );
	}

	/**
	 * Calculate an age interval.
	 *
	 * @param string $birth_date Birth date.
	 * @return DateInterval|null
	 */
	public function age_interval( string $birth_date ): ?DateInterval {
		$birth = $this->parse_date( $birth_date );
		$today = $this->today();

		if ( null === $birth || $birth > $today ) {
			return null;
		}

		return $birth->diff( $today );
	}

	/**
	 * Format an age interval.
	 *
	 * @param DateInterval $interval Age interval.
	 * @param string       $format   y, ym, or ymd.
	 * @param bool         $ordinal  Whether to return an ordinal year.
	 * @return string
	 */
	public function format_age( DateInterval $interval, string $format = 'y', bool $ordinal = false ): string {
		if ( $ordinal ) {
			return $this->ordinal( $interval->y );
		}

		$format = strtolower( $format );

		if ( 'y' === $format ) {
			return (string) $interval->y;
		}

		$parts = [];

		if ( $interval->y > 0 ) {
			$parts[] = sprintf(
				/* translators: %d: number of years */
				_n( '%d year', '%d years', $interval->y, 'dynamic-month-year-into-posts' ),
				$interval->y
			);
		}

		if ( $interval->m > 0 ) {
			$parts[] = sprintf(
				/* translators: %d: number of months */
				_n( '%d month', '%d months', $interval->m, 'dynamic-month-year-into-posts' ),
				$interval->m
			);
		}

		if ( 'ymd' === $format && $interval->d > 0 ) {
			$parts[] = sprintf(
				/* translators: %d: number of days */
				_n( '%d day', '%d days', $interval->d, 'dynamic-month-year-into-posts' ),
				$interval->d
			);
		}

		if ( ! empty( $parts ) ) {
			return implode( ', ', $parts );
		}

		return 'ymd' === $format
			? _x( '0 days', 'zero-length age', 'dynamic-month-year-into-posts' )
			: _x( '0 months', 'zero-length age', 'dynamic-month-year-into-posts' );
	}

	/**
	 * Add an English ordinal suffix.
	 *
	 * @param int $number Number.
	 * @return string
	 */
	public function ordinal( int $number ): string {
		$absolute = abs( $number );

		if ( $absolute % 100 >= 11 && $absolute % 100 <= 13 ) {
			return $number . 'th';
		}

		switch ( $absolute % 10 ) {
			case 1:
				return $number . 'st';
			case 2:
				return $number . 'nd';
			case 3:
				return $number . 'rd';
			default:
				return $number . 'th';
		}
	}

	/**
	 * Calculate a named annual event.
	 *
	 * @param string $event Event name.
	 * @param int    $year  Calendar year.
	 * @return DateTimeImmutable|null
	 */
	public function event_date( string $event, int $year ): ?DateTimeImmutable {
		$thanksgiving = $this->date_from_rule( 'fourth thursday of november', $year );

		if ( null === $thanksgiving ) {
			return null;
		}

		if ( 'blackfriday' === $event ) {
			return $thanksgiving->modify( '+1 day' );
		}

		if ( 'cybermonday' === $event ) {
			return $thanksgiving->modify( '+4 days' );
		}

		return null;
	}

	/**
	 * Find the next annual occurrence from a month/day or supported rule.
	 *
	 * @param string $date Month/day (MM-DD) or a full date.
	 * @param string $rule Rule such as "last sunday of january".
	 * @return DateTimeImmutable|null
	 */
	public function next_occurrence( string $date = '', string $rule = '' ): ?DateTimeImmutable {
		$today = $this->today();
		$year  = (int) $today->format( 'Y' );

		if ( '' !== trim( $rule ) ) {
			$occurrence = $this->date_from_rule( $rule, $year );

			if ( null !== $occurrence && $occurrence < $today ) {
				$occurrence = $this->date_from_rule( $rule, $year + 1 );
			}

			return $occurrence;
		}

		$date = trim( $date );

		if ( 1 === preg_match( '/^(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01])$/', $date, $matches ) ) {
			// Search far enough ahead to support February 29 recurrences.
			for ( $year_offset = 0; $year_offset <= 8; $year_offset++ ) {
				$occurrence = $this->parse_date(
					sprintf( '%04d-%02d-%02d', $year + $year_offset, (int) $matches[1], (int) $matches[2] )
				);

				if ( null !== $occurrence && $occurrence >= $today ) {
					return $occurrence;
				}
			}

			return null;
		}

		$full_date = $this->parse_date( $date );

		if ( null === $full_date ) {
			return null;
		}

		$month_day = $full_date->format( 'm-d' );

		return $this->next_occurrence( $month_day );
	}

	/**
	 * Parse a safe annual weekday rule.
	 *
	 * @param string $rule Rule text.
	 * @param int    $year Calendar year.
	 * @return DateTimeImmutable|null
	 */
	public function date_from_rule( string $rule, int $year ): ?DateTimeImmutable {
		$rule = strtolower( trim( preg_replace( '/\s+/', ' ', $rule ) ?? '' ) );

		if (
			1 !== preg_match(
				'/^(first|second|third|fourth|last) (monday|tuesday|wednesday|thursday|friday|saturday|sunday) of (january|february|march|april|may|june|july|august|september|october|november|december)$/',
				$rule
			)
		) {
			return null;
		}

		try {
			return ( new DateTimeImmutable( "{$rule} {$year}", $this->timezone() ) )->setTime( 0, 0, 0 );
		} catch ( Exception $exception ) {
			unset( $exception );
			return null;
		}
	}

	/**
	 * Apply a safe text-case transform.
	 *
	 * @param string $value Output value.
	 * @param string $text_case none, upper, lower, or title.
	 * @return string
	 */
	public function transform_case( string $value, string $text_case ): string {
		switch ( strtolower( $text_case ) ) {
			case 'upper':
				return function_exists( 'mb_strtoupper' ) ? mb_strtoupper( $value, 'UTF-8' ) : strtoupper( $value );
			case 'lower':
				return function_exists( 'mb_strtolower' ) ? mb_strtolower( $value, 'UTF-8' ) : strtolower( $value );
			case 'title':
				if ( function_exists( 'mb_convert_case' ) ) {
					return mb_convert_case( $value, MB_CASE_TITLE, 'UTF-8' );
				}
				return ucwords( strtolower( $value ) );
			default:
				return $value;
		}
	}
}
