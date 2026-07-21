<?php
/**
 * WP-CLI commands.
 *
 * @package DMYIP
 */

declare(strict_types=1);

namespace DMYIP\CLI;

use DMYIP\Date\DateRenderer;
use WP_CLI;
use WP_CLI\Utils;

/**
 * Dynamic Month & Year WP-CLI commands.
 */
class Commands {

	/**
	 * Register commands.
	 *
	 * @return void
	 */
	public function register(): void {
		WP_CLI::add_command( 'dmyip', self::class );
	}

	/**
	 * Render a date type through the same engine used by blocks and shortcodes.
	 *
	 * ## OPTIONS
	 *
	 * <type>
	 * : Date type, for example year, month, or daysuntil.
	 *
	 * [--offset=<offset>]
	 * : Signed year or month offset.
	 *
	 * [--date=<date>]
	 * : Target date in YYYY-MM-DD or recurring MM-DD format.
	 *
	 * [--rule=<rule>]
	 * : Recurring rule, for example "last sunday of january".
	 *
	 * [--format=<format>]
	 * : PHP date format.
	 *
	 * [--case=<case>]
	 * : none, title, upper, or lower.
	 *
	 * [--rollover-day=<day>]
	 * : Switch month output after this day of the month.
	 *
	 * @param array<int, string>   $args       Positional arguments.
	 * @param array<string, mixed> $assoc_args Associative arguments.
	 * @return void
	 */
	public function shortcode( array $args, array $assoc_args ): void {
		$type  = isset( $args[0] ) ? sanitize_key( $args[0] ) : 'year';
		$data  = [
			'offset'       => isset( $assoc_args['offset'] ) ? (int) $assoc_args['offset'] : 0,
			'date'         => isset( $assoc_args['date'] ) ? (string) $assoc_args['date'] : '',
			'rule'         => isset( $assoc_args['rule'] ) ? (string) $assoc_args['rule'] : '',
			'format'       => isset( $assoc_args['format'] ) ? (string) $assoc_args['format'] : '',
			'case'         => isset( $assoc_args['case'] ) ? (string) $assoc_args['case'] : 'none',
			'rollover_day' => isset( $assoc_args['rollover-day'] ) ? (int) $assoc_args['rollover-day'] : 0,
		];
		$value = ( new DateRenderer() )->render( $type, $data );

		if ( '' === $value ) {
			WP_CLI::error( 'Unknown date type or missing/invalid date arguments.' );
			return;
		}

		WP_CLI::line( $value );
	}

	/**
	 * List representative shortcodes.
	 *
	 * ## OPTIONS
	 *
	 * [--format=<format>]
	 * : table, csv, json, or yaml.
	+ *
	 *
	 * @param array<int, string>   $args       Positional arguments.
	 * @param array<string, mixed> $assoc_args Associative arguments.
	 * @return void
	 */
	public function list( array $args, array $assoc_args ): void {
		unset( $args );

		$format = isset( $assoc_args['format'] ) ? (string) $assoc_args['format'] : 'table';
		$items  = [
			[
				'shortcode'   => '[year]',
				'category'    => 'Year',
				'description' => 'Current year',
			],
			[
				'shortcode'   => '[year n="5"]',
				'category'    => 'Year',
				'description' => 'Year with offset',
			],
			[
				'shortcode'   => '[month]',
				'category'    => 'Month',
				'description' => 'Current month',
			],
			[
				'shortcode'   => '[date]',
				'category'    => 'Date',
				'description' => 'Current date',
			],
			[
				'shortcode'   => '[datepublished]',
				'category'    => 'Post',
				'description' => 'Published date',
			],
			[
				'shortcode'   => '[datemodified]',
				'category'    => 'Post',
				'description' => 'Modified date',
			],
			[
				'shortcode'   => '[daysuntil date="YYYY-MM-DD"]',
				'category'    => 'Countdown',
				'description' => 'Days until date',
			],
			[
				'shortcode'   => '[age date="YYYY-MM-DD" format="ymd"]',
				'category'    => 'Age',
				'description' => 'Full age',
			],
			[
				'shortcode'   => '[blackfriday]',
				'category'    => 'Event',
				'description' => 'Black Friday date',
			],
			[
				'shortcode'   => '[cybermonday]',
				'category'    => 'Event',
				'description' => 'Cyber Monday date',
			],
			[
				'shortcode'   => '[season]',
				'category'    => 'Season',
				'description' => 'Current season',
			],
		];

		Utils\format_items( $format, $items, [ 'shortcode', 'category', 'description' ] );
	}

	/**
	 * Test representative registered shortcodes.
	 *
	 * @param array<int, string>   $args       Positional arguments.
	 * @param array<string, mixed> $assoc_args Associative arguments.
	 * @return void
	 */
	public function test( array $args, array $assoc_args ): void {
		unset( $args, $assoc_args );

		$next_year = (int) current_time( 'Y' ) + 1;
		$tests     = [
			'[year]',
			'[month]',
			'[date]',
			'[weekday]',
			'[blackfriday]',
			'[season]',
			'[daysuntil date="' . $next_year . '-12-31"]',
		];
		$failed    = [];

		foreach ( $tests as $shortcode ) {
			$value = do_shortcode( $shortcode );

			if ( '' === $value ) {
				$failed[] = $shortcode;
				WP_CLI::warning( "{$shortcode} returned an empty value." );
				continue;
			}

			WP_CLI::line( "{$shortcode} => {$value}" );
		}

		if ( ! empty( $failed ) ) {
			WP_CLI::error( sprintf( '%d shortcode checks failed.', count( $failed ) ) );
			return;
		}

		WP_CLI::success( 'All representative shortcode checks passed.' );
	}

	/**
	 * Display plugin information.
	 *
	 * @param array<int, string>   $args       Positional arguments.
	 * @param array<string, mixed> $assoc_args Associative arguments.
	 * @return void
	 */
	public function info( array $args, array $assoc_args ): void {
		unset( $args, $assoc_args );

		WP_CLI::line( 'Dynamic Month & Year into Posts' );
		WP_CLI::line( 'Version: ' . DYNAMIC_MONTH_YEAR_INTO_POSTS_VERSION );
		WP_CLI::line( 'Timezone: ' . wp_timezone()->getName() );
		WP_CLI::line( 'Documentation: https://gauravtiwari.org/snippet/dynamic-month-year/' );
	}
}
