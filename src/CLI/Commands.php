<?php
/**
 * WP-CLI commands.
 *
 * @package DMYIP
 */

declare(strict_types=1);

namespace DMYIP\CLI;

use WP_CLI;
use WP_CLI\Utils;

/**
 * Dynamic Month Year Into Posts CLI commands.
 */
class Commands {

	/**
	 * Register CLI commands.
	 *
	 * @return void
	 */
	public function register(): void {
		WP_CLI::add_command( 'dmyip', self::class );
	}

	/**
	 * Get a specific date shortcode output.
	 *
	 * ## OPTIONS
	 *
	 * <type>
	 * : The shortcode type (year, month, date, etc.)
	 *
	 * [--offset=<offset>]
	 * : Offset for year shortcode.
	 * ---
	 * default: 0
	 * ---
	 *
	 * [--date=<date>]
	 * : Target date for countdown shortcodes (YYYY-MM-DD format).
	 *
	 * ## EXAMPLES
	 *
	 *     # Get current year
	 *     wp dmyip shortcode year
	 *
	 *     # Get year with offset
	 *     wp dmyip shortcode year --offset=5
	 *
	 *     # Get days until a date
	 *     wp dmyip shortcode daysuntil --date=2025-12-31
	 *
	 * @param array<int, string>   $args       Positional arguments.
	 * @param array<string, mixed> $assoc_args Associative arguments.
	 * @return void
	 */
	public function shortcode( array $args, array $assoc_args ): void {
		$type   = $args[0] ?? 'year';
		$offset = (int) ( $assoc_args['offset'] ?? 0 );
		$date   = $assoc_args['date'] ?? '';

		$shortcode_map = [
			'year'          => '[year]',
			'nyear'         => '[nyear]',
			'nnyear'        => '[nnyear]',
			'pyear'         => '[pyear]',
			'ppyear'        => '[ppyear]',
			'month'         => '[month]',
			'cmonth'        => '[cmonth]',
			'mon'           => '[mon]',
			'cmon'          => '[cmon]',
			'mm'            => '[mm]',
			'mn'            => '[mn]',
			'nmonth'        => '[nmonth]',
			'cnmonth'       => '[cnmonth]',
			'nmon'          => '[nmon]',
			'cnmon'         => '[cnmon]',
			'pmonth'        => '[pmonth]',
			'cpmonth'       => '[cpmonth]',
			'pmon'          => '[pmon]',
			'cpmon'         => '[cpmon]',
			'date'          => '[date]',
			'monthyear'     => '[monthyear]',
			'nmonthyear'    => '[nmonthyear]',
			'pmonthyear'    => '[pmonthyear]',
			'dt'            => '[dt]',
			'nd'            => '[nd]',
			'pd'            => '[pd]',
			'weekday'       => '[weekday]',
			'wd'            => '[wd]',
			'blackfriday'   => '[blackfriday]',
			'cybermonday'   => '[cybermonday]',
			'datepublished' => '[datepublished]',
			'datemodified'  => '[datemodified]',
		];

		// Handle special cases.
		if ( 'year' === $type && 0 !== $offset ) {
			$output = do_shortcode( '[year n="' . $offset . '"]' );
		} elseif ( 'daysuntil' === $type ) {
			if ( empty( $date ) ) {
				WP_CLI::error( 'The --date parameter is required for daysuntil shortcode.' );
				return;
			}
			$output = do_shortcode( '[daysuntil date="' . esc_attr( $date ) . '"]' );
		} elseif ( 'dayssince' === $type ) {
			if ( empty( $date ) ) {
				WP_CLI::error( 'The --date parameter is required for dayssince shortcode.' );
				return;
			}
			$output = do_shortcode( '[dayssince date="' . esc_attr( $date ) . '"]' );
		} elseif ( isset( $shortcode_map[ $type ] ) ) {
			$output = do_shortcode( $shortcode_map[ $type ] );
		} else {
			WP_CLI::error( "Unknown shortcode type: {$type}" );
			return;
		}

		WP_CLI::line( $output );
	}

	/**
	 * List all available shortcodes.
	 *
	 * ## OPTIONS
	 *
	 * [--format=<format>]
	 * : Output format.
	 * ---
	 * default: table
	 * options:
	 *   - table
	 *   - csv
	 *   - json
	 *   - yaml
	 * ---
	 *
	 * ## EXAMPLES
	 *
	 *     # List all shortcodes in table format
	 *     wp dmyip list
	 *
	 *     # List all shortcodes as JSON
	 *     wp dmyip list --format=json
	 *
	 * @param array<int, string>   $args       Positional arguments.
	 * @param array<string, mixed> $assoc_args Associative arguments.
	 * @return void
	 */
	public function list( array $args, array $assoc_args ): void {
		$format = $assoc_args['format'] ?? 'table';

		$shortcodes = [
			[
				'shortcode'   => '[year]',
				'category'    => 'Year',
				'description' => 'Current year',
				'example'     => do_shortcode( '[year]' ),
			],
			[
				'shortcode'   => '[year n=X]',
				'category'    => 'Year',
				'description' => 'Year with offset',
				'example'     => do_shortcode( '[year n=5]' ),
			],
			[
				'shortcode'   => '[nyear]',
				'category'    => 'Year',
				'description' => 'Next year',
				'example'     => do_shortcode( '[nyear]' ),
			],
			[
				'shortcode'   => '[nnyear]',
				'category'    => 'Year',
				'description' => 'Year after next',
				'example'     => do_shortcode( '[nnyear]' ),
			],
			[
				'shortcode'   => '[pyear]',
				'category'    => 'Year',
				'description' => 'Previous year',
				'example'     => do_shortcode( '[pyear]' ),
			],
			[
				'shortcode'   => '[ppyear]',
				'category'    => 'Year',
				'description' => 'Year before previous',
				'example'     => do_shortcode( '[ppyear]' ),
			],
			[
				'shortcode'   => '[month]',
				'category'    => 'Month',
				'description' => 'Current month (full)',
				'example'     => do_shortcode( '[month]' ),
			],
			[
				'shortcode'   => '[cmonth]',
				'category'    => 'Month',
				'description' => 'Current month (caps)',
				'example'     => do_shortcode( '[cmonth]' ),
			],
			[
				'shortcode'   => '[mon]',
				'category'    => 'Month',
				'description' => 'Current month (short)',
				'example'     => do_shortcode( '[mon]' ),
			],
			[
				'shortcode'   => '[mm]',
				'category'    => 'Month',
				'description' => 'Month number (01-12)',
				'example'     => do_shortcode( '[mm]' ),
			],
			[
				'shortcode'   => '[mn]',
				'category'    => 'Month',
				'description' => 'Month number (1-12)',
				'example'     => do_shortcode( '[mn]' ),
			],
			[
				'shortcode'   => '[nmonth]',
				'category'    => 'Month',
				'description' => 'Next month',
				'example'     => do_shortcode( '[nmonth]' ),
			],
			[
				'shortcode'   => '[pmonth]',
				'category'    => 'Month',
				'description' => 'Previous month',
				'example'     => do_shortcode( '[pmonth]' ),
			],
			[
				'shortcode'   => '[date]',
				'category'    => 'Date',
				'description' => 'Full date',
				'example'     => do_shortcode( '[date]' ),
			],
			[
				'shortcode'   => '[monthyear]',
				'category'    => 'Date',
				'description' => 'Month and year',
				'example'     => do_shortcode( '[monthyear]' ),
			],
			[
				'shortcode'   => '[dt]',
				'category'    => 'Day',
				'description' => 'Day of month',
				'example'     => do_shortcode( '[dt]' ),
			],
			[
				'shortcode'   => '[nd]',
				'category'    => 'Day',
				'description' => 'Tomorrow',
				'example'     => do_shortcode( '[nd]' ),
			],
			[
				'shortcode'   => '[pd]',
				'category'    => 'Day',
				'description' => 'Yesterday',
				'example'     => do_shortcode( '[pd]' ),
			],
			[
				'shortcode'   => '[weekday]',
				'category'    => 'Day',
				'description' => 'Day of week (full)',
				'example'     => do_shortcode( '[weekday]' ),
			],
			[
				'shortcode'   => '[wd]',
				'category'    => 'Day',
				'description' => 'Day of week (short)',
				'example'     => do_shortcode( '[wd]' ),
			],
			[
				'shortcode'   => '[blackfriday]',
				'category'    => 'Events',
				'description' => 'Black Friday date',
				'example'     => do_shortcode( '[blackfriday]' ),
			],
			[
				'shortcode'   => '[cybermonday]',
				'category'    => 'Events',
				'description' => 'Cyber Monday date',
				'example'     => do_shortcode( '[cybermonday]' ),
			],
			[
				'shortcode'   => '[daysuntil date="X"]',
				'category'    => 'Countdown',
				'description' => 'Days until date',
				'example'     => 'Requires date',
			],
			[
				'shortcode'   => '[dayssince date="X"]',
				'category'    => 'Countdown',
				'description' => 'Days since date',
				'example'     => 'Requires date',
			],
			[
				'shortcode'   => '[datepublished]',
				'category'    => 'Post',
				'description' => 'Publication date',
				'example'     => 'Requires post',
			],
			[
				'shortcode'   => '[datemodified]',
				'category'    => 'Post',
				'description' => 'Modified date',
				'example'     => 'Requires post',
			],
		];

		Utils\format_items( $format, $shortcodes, [ 'shortcode', 'category', 'description', 'example' ] );
	}

	/**
	 * Test all shortcodes and display their output.
	 *
	 * ## EXAMPLES
	 *
	 *     wp dmyip test
	 *
	 * @param array<int, string>   $args       Positional arguments.
	 * @param array<string, mixed> $assoc_args Associative arguments.
	 * @return void
	 */
	public function test( array $args, array $assoc_args ): void {
		WP_CLI::line( 'Testing all shortcodes...' );
		WP_CLI::line( '' );

		$tests = [
			'[year]'        => 'Current year',
			'[year n=5]'    => 'Year +5',
			'[nyear]'       => 'Next year',
			'[pyear]'       => 'Previous year',
			'[month]'       => 'Current month',
			'[mon]'         => 'Month (short)',
			'[mm]'          => 'Month number',
			'[nmonth]'      => 'Next month',
			'[pmonth]'      => 'Previous month',
			'[date]'        => 'Full date',
			'[monthyear]'   => 'Month and year',
			'[dt]'          => 'Day of month',
			'[weekday]'     => 'Weekday',
			'[wd]'          => 'Weekday (short)',
			'[blackfriday]' => 'Black Friday',
			'[cybermonday]' => 'Cyber Monday',
		];

		$all_passed = true;

		foreach ( $tests as $shortcode => $description ) {
			$output = do_shortcode( $shortcode );

			if ( empty( $output ) ) {
				WP_CLI::warning( sprintf( '%-15s %-20s => EMPTY', $shortcode, $description ) );
				$all_passed = false;
			} else {
				WP_CLI::line( sprintf( '%-15s %-20s => %s', $shortcode, $description, $output ) );
			}
		}

		WP_CLI::line( '' );

		if ( $all_passed ) {
			WP_CLI::success( 'All shortcodes working correctly!' );
		} else {
			WP_CLI::warning( 'Some shortcodes returned empty output.' );
		}
	}

	/**
	 * Display plugin information.
	 *
	 * ## EXAMPLES
	 *
	 *     wp dmyip info
	 *
	 * @param array<int, string>   $args       Positional arguments.
	 * @param array<string, mixed> $assoc_args Associative arguments.
	 * @return void
	 */
	public function info( array $args, array $assoc_args ): void {
		WP_CLI::line( 'Dynamic Month & Year into Posts' );
		WP_CLI::line( '================================' );
		WP_CLI::line( '' );
		WP_CLI::line( 'Version: ' . ( defined( 'DYNAMIC_MONTH_YEAR_INTO_POSTS_VERSION' ) ? DYNAMIC_MONTH_YEAR_INTO_POSTS_VERSION : 'Unknown' ) );
		WP_CLI::line( 'Author: Gaurav Tiwari' );
		WP_CLI::line( 'Documentation: https://gauravtiwari.org/snippet/dynamic-month-year/' );
		WP_CLI::line( '' );
		WP_CLI::line( 'Commands:' );
		WP_CLI::line( '  wp dmyip shortcode <type>  Get a shortcode output' );
		WP_CLI::line( '  wp dmyip list              List all shortcodes' );
		WP_CLI::line( '  wp dmyip test              Test all shortcodes' );
		WP_CLI::line( '  wp dmyip info              Display this information' );
	}
}
