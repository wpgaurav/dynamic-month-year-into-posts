<?php
/**
 * Unified dynamic-date renderer.
 *
 * @package DMYIP
 */

declare(strict_types=1);

namespace DMYIP\Date;

use DateTimeImmutable;

/**
 * Render every public date type through one implementation.
 */
final class DateRenderer {

	/**
	 * Date engine.
	 *
	 * @var DateEngine
	 */
	private DateEngine $engine;

	/**
	 * Constructor.
	 *
	 * @param DateEngine|null $engine Date engine.
	 */
	public function __construct( ?DateEngine $engine = null ) {
		$this->engine = $engine ?? new DateEngine();
	}

	/**
	 * Render a date type.
	 *
	 * @param string               $type    Date type or shortcode alias.
	 * @param array<string, mixed> $args    Render arguments.
	 * @param int                  $post_id Optional post ID.
	 * @return string
	 */
	public function render( string $type, array $args = [], int $post_id = 0 ): string {
		$locale   = isset( $args['locale'] ) ? sanitize_locale_name( (string) $args['locale'] ) : '';
		$switched = '' !== $locale && function_exists( 'switch_to_locale' ) && switch_to_locale( $locale );

		try {
			$output = $this->render_value( strtolower( $type ), $args, $post_id );
		} finally {
			if ( $switched ) {
				restore_previous_locale();
			}
		}

		$case = isset( $args['case'] ) ? (string) $args['case'] : 'none';

		return $this->engine->transform_case( $output, $case );
	}

	/**
	 * Render the untransformed value.
	 *
	 * @param string               $type    Date type.
	 * @param array<string, mixed> $args    Render arguments.
	 * @param int                  $post_id Optional post ID.
	 * @return string
	 */
	private function render_value( string $type, array $args, int $post_id ): string {
		$aliases = [
			'cmonth'        => 'month',
			'mon'           => 'month_short',
			'cmon'          => 'month_short',
			'mm'            => 'month_number_zero',
			'mn'            => 'month_number',
			'nmon'          => 'next_month_short',
			'cnmon'         => 'next_month_short',
			'pmon'          => 'previous_month_short',
			'cpmon'         => 'previous_month_short',
			'nmonth'        => 'next_month',
			'cnmonth'       => 'next_month',
			'pmonth'        => 'previous_month',
			'cpmonth'       => 'previous_month',
			'nmonthyear'    => 'next_month_year',
			'pmonthyear'    => 'previous_month_year',
			'dt'            => 'day',
			'nd'            => 'next_day',
			'pd'            => 'previous_day',
			'wd'            => 'weekday_short',
			'datepublished' => 'published',
			'datemodified'  => 'modified',
		];

		$type = $aliases[ $type ] ?? $type;
		$now  = $this->engine->now();

		switch ( $type ) {
			case 'year':
				return (string) ( (int) $now->format( 'Y' ) + $this->integer_arg( $args, 'offset', $this->integer_arg( $args, 'n' ) ) );
			case 'nyear':
				return (string) ( (int) $now->format( 'Y' ) + 1 );
			case 'nnyear':
				return (string) ( (int) $now->format( 'Y' ) + 2 );
			case 'pyear':
				return (string) ( (int) $now->format( 'Y' ) - 1 );
			case 'ppyear':
				return (string) ( (int) $now->format( 'Y' ) - 2 );
			case 'month':
				return $this->format_month( $now, $args, 'F' );
			case 'month_short':
				return $this->format_month( $now, $args, 'M' );
			case 'month_number':
				return $this->format_month( $now, $args, 'n' );
			case 'month_number_zero':
				return $this->format_month( $now, $args, 'm' );
			case 'next_month':
				return $this->format_month( $now, $args, 'F', 1 );
			case 'next_month_short':
				return $this->format_month( $now, $args, 'M', 1 );
			case 'previous_month':
				return $this->format_month( $now, $args, 'F', -1 );
			case 'previous_month_short':
				return $this->format_month( $now, $args, 'M', -1 );
			case 'date':
				return $this->engine->format( $now, $this->string_arg( $args, 'format', 'F j, Y' ) );
			case 'monthyear':
				return $this->format_month( $now, $args, 'F Y' );
			case 'next_month_year':
				return $this->format_month( $now, $args, 'F Y', 1 );
			case 'previous_month_year':
				return $this->format_month( $now, $args, 'F Y', -1 );
			case 'day':
				return $this->engine->format( $now, 'j' );
			case 'next_day':
				return $this->engine->format( $now->modify( '+1 day' ), 'j' );
			case 'previous_day':
				return $this->engine->format( $now->modify( '-1 day' ), 'j' );
			case 'weekday':
				return $this->engine->format( $now, 'l' );
			case 'weekday_short':
				return $this->engine->format( $now, 'D' );
			case 'published':
				return $this->render_post_date( $post_id, 'date', $args );
			case 'modified':
				return $this->render_post_date( $post_id, 'modified', $args );
			case 'blackfriday':
			case 'cybermonday':
				$event_date = $this->engine->event_date( $type, (int) $now->format( 'Y' ) );
				return null === $event_date ? '' : $this->engine->format( $event_date, $this->string_arg( $args, 'format', 'F j' ) );
			case 'daysuntil':
				return $this->render_day_difference( $args, true );
			case 'dayssince':
				return $this->render_day_difference( $args, false );
			case 'age':
			case 'age_ym':
			case 'age_ymd':
			case 'age_ordinal':
				return $this->render_age( $type, $args );
			case 'season':
			case 'season_south':
				return $this->render_season( $type, $args, (int) $now->format( 'n' ) );
			case 'nextoccurrence':
				return $this->render_next_occurrence( $args );
			case 'daysuntilnext':
				return $this->render_days_until_next( $args );
			case 'occurrenceyear':
				$occurrence = $this->occurrence_from_args( $args );
				return null === $occurrence ? '' : $occurrence->format( 'Y' );
			default:
				return '';
		}
	}

	/**
	 * Format a month-based value with optional cutoff rollover.
	 *
	 * @param DateTimeImmutable   $now            Current date.
	 * @param array<string,mixed> $args           Arguments.
	 * @param string              $format         Date format.
	 * @param int                 $default_offset Default month offset.
	 * @return string
	 */
	private function format_month( DateTimeImmutable $now, array $args, string $format, int $default_offset = 0 ): string {
		$offset       = $this->integer_arg( $args, 'offset', $default_offset );
		$rollover_day = max( 0, min( 31, $this->integer_arg( $args, 'rollover_day', $this->integer_arg( $args, 'rolloverDay' ) ) ) );

		if ( $rollover_day > 0 && (int) $now->format( 'j' ) >= $rollover_day ) {
			++$offset;
		}

		$date = $now->modify( 'first day of this month' );

		if ( 0 !== $offset ) {
			$date = $date->modify( sprintf( '%+d months', $offset ) );
		}

		return $this->engine->format( $date, $this->string_arg( $args, 'format', $format ) );
	}

	/**
	 * Render a post date.
	 *
	 * @param int                  $post_id Post ID.
	 * @param string               $field   date or modified.
	 * @param array<string, mixed> $args    Arguments.
	 * @return string
	 */
	private function render_post_date( int $post_id, string $field, array $args ): string {
		$post_id = $post_id > 0 ? $post_id : (int) get_the_ID();

		if ( $post_id <= 0 ) {
			return '';
		}

		$date = get_post_datetime( $post_id, $field );

		if ( false === $date ) {
			return '';
		}

		return $this->engine->format(
			$date,
			$this->string_arg( $args, 'format', (string) get_option( 'date_format' ) )
		);
	}

	/**
	 * Render a day difference.
	 *
	 * @param array<string, mixed> $args  Arguments.
	 * @param bool                 $until Until or since.
	 * @return string
	 */
	private function render_day_difference( array $args, bool $until ): string {
		$target = $this->engine->parse_date( $this->string_arg( $args, 'date' ) );

		if ( null === $target ) {
			return '';
		}

		$days  = $until
			? $this->engine->calendar_days( $this->engine->today(), $target )
			: $this->engine->calendar_days( $target, $this->engine->today() );
		$clamp = $this->boolean_arg( $args, 'clamp', false );

		return (string) ( $clamp ? max( 0, $days ) : $days );
	}

	/**
	 * Render age output.
	 *
	 * @param string               $type Type.
	 * @param array<string, mixed> $args Arguments.
	 * @return string
	 */
	private function render_age( string $type, array $args ): string {
		$interval = $this->engine->age_interval( $this->string_arg( $args, 'date' ) );

		if ( null === $interval ) {
			return '';
		}

		$format = $this->string_arg( $args, 'format', 'y' );

		if ( 'age_ym' === $type ) {
			$format = 'ym';
		} elseif ( 'age_ymd' === $type ) {
			$format = 'ymd';
		}

		$ordinal = 'age_ordinal' === $type ||
			$this->boolean_arg( $args, 'ordinal', $this->boolean_arg( $args, 'rank', false ) );

		return $this->engine->format_age( $interval, $format, $ordinal );
	}

	/**
	 * Render a season.
	 *
	 * @param string               $type  Type.
	 * @param array<string, mixed> $args  Arguments.
	 * @param int                  $month Current month.
	 * @return string
	 */
	private function render_season( string $type, array $args, int $month ): string {
		$region = strtolower( $this->string_arg( $args, 'region', 'season_south' === $type ? 'south' : 'north' ) );

		if ( $month >= 3 && $month <= 5 ) {
			$season = 'spring';
		} elseif ( $month >= 6 && $month <= 8 ) {
			$season = 'summer';
		} elseif ( $month >= 9 && $month <= 11 ) {
			$season = 'fall';
		} else {
			$season = 'winter';
		}

		if ( 'south' === $region || 'southern' === $region ) {
			$opposites = [
				'spring' => 'fall',
				'summer' => 'winter',
				'fall'   => 'spring',
				'winter' => 'summer',
			];
			$season    = $opposites[ $season ];
		}

		$labels = [
			'spring' => __( 'Spring', 'dynamic-month-year-into-posts' ),
			'summer' => __( 'Summer', 'dynamic-month-year-into-posts' ),
			'fall'   => __( 'Fall', 'dynamic-month-year-into-posts' ),
			'winter' => __( 'Winter', 'dynamic-month-year-into-posts' ),
		];

		return $labels[ $season ];
	}

	/**
	 * Render the next recurring occurrence.
	 *
	 * @param array<string, mixed> $args Arguments.
	 * @return string
	 */
	private function render_next_occurrence( array $args ): string {
		$occurrence = $this->occurrence_from_args( $args );

		return null === $occurrence
			? ''
			: $this->engine->format( $occurrence, $this->string_arg( $args, 'format', (string) get_option( 'date_format' ) ) );
	}

	/**
	 * Render days until the next recurring occurrence.
	 *
	 * @param array<string, mixed> $args Arguments.
	 * @return string
	 */
	private function render_days_until_next( array $args ): string {
		$occurrence = $this->occurrence_from_args( $args );

		if ( null === $occurrence ) {
			return '';
		}

		return (string) max( 0, $this->engine->calendar_days( $this->engine->today(), $occurrence ) );
	}

	/**
	 * Resolve recurring occurrence arguments.
	 *
	 * @param array<string, mixed> $args Arguments.
	 * @return DateTimeImmutable|null
	 */
	private function occurrence_from_args( array $args ): ?DateTimeImmutable {
		return $this->engine->next_occurrence(
			$this->string_arg( $args, 'date' ),
			$this->string_arg( $args, 'rule' )
		);
	}

	/**
	 * Read a string argument.
	 *
	 * @param array<string, mixed> $args    Arguments.
	 * @param string               $key     Argument key.
	 * @param string               $fallback Default value.
	 * @return string
	 */
	private function string_arg( array $args, string $key, string $fallback = '' ): string {
		if ( ! isset( $args[ $key ] ) || '' === (string) $args[ $key ] ) {
			return $fallback;
		}

		return (string) $args[ $key ];
	}

	/**
	 * Read an integer argument.
	 *
	 * @param array<string, mixed> $args    Arguments.
	 * @param string               $key     Argument key.
	 * @param int                  $fallback Default value.
	 * @return int
	 */
	private function integer_arg( array $args, string $key, int $fallback = 0 ): int {
		return isset( $args[ $key ] ) ? (int) $args[ $key ] : $fallback;
	}

	/**
	 * Read a boolean-like argument.
	 *
	 * @param array<string, mixed> $args    Arguments.
	 * @param string               $key     Argument key.
	 * @param bool                 $fallback Default value.
	 * @return bool
	 */
	private function boolean_arg( array $args, string $key, bool $fallback = false ): bool {
		if ( ! isset( $args[ $key ] ) || '' === (string) $args[ $key ] ) {
			return $fallback;
		}

		return in_array( strtolower( (string) $args[ $key ] ), [ '1', 'true', 'yes', 'on' ], true );
	}
}
