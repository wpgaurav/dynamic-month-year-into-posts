<?php
/**
 * Shortcode tests.
 *
 * @package DMYIP
 */

declare(strict_types=1);

namespace DMYIP\Tests;

use DMYIP\Shortcodes\Year;
use DMYIP\Shortcodes\Month;
use DMYIP\Shortcodes\Day;
use DMYIP\Shortcodes\Date;
use DMYIP\Shortcodes\Events;
use DMYIP\Shortcodes\Countdown;
use DMYIP\Shortcodes\Season;
use DMYIP\Shortcodes\Registry;
use DMYIP\REST\DatesEndpoint;
use DMYIP\Date\DateRenderer;
use DateTimeImmutable;
use DateTimeZone;
use PHPUnit\Framework\TestCase;

/**
 * Test shortcode classes.
 */
class ShortcodesTest extends TestCase {

	/**
	 * The public shortcode API remains the original 36-tag set.
	 */
	public function test_registry_contains_only_legacy_shortcodes(): void {
		$legacy_tags = [
			'age',
			'blackfriday',
			'cmon',
			'cmonth',
			'cnmon',
			'cnmonth',
			'cpmon',
			'cpmonth',
			'cybermonday',
			'date',
			'datemodified',
			'datepublished',
			'dayssince',
			'daysuntil',
			'dt',
			'mm',
			'mn',
			'mon',
			'month',
			'monthyear',
			'nd',
			'nmon',
			'nmonth',
			'nmonthyear',
			'nnyear',
			'nyear',
			'pd',
			'pmon',
			'pmonth',
			'pmonthyear',
			'ppyear',
			'pyear',
			'season',
			'wd',
			'weekday',
			'year',
		];

		$actual_tags = Registry::TAGS;
		sort( $actual_tags );

		$this->assertSame( $legacy_tags, $actual_tags );
	}

	/**
	 * Year shortcode instance.
	 *
	 * @var Year
	 */
	private Year $year;

	/**
	 * Month shortcode instance.
	 *
	 * @var Month
	 */
	private Month $month;

	/**
	 * Day shortcode instance.
	 *
	 * @var Day
	 */
	private Day $day;

	/**
	 * Date shortcode instance.
	 *
	 * @var Date
	 */
	private Date $date;

	/**
	 * Events shortcode instance.
	 *
	 * @var Events
	 */
	private Events $events;

	/**
	 * Countdown shortcode instance.
	 *
	 * @var Countdown
	 */
	private Countdown $countdown;

	/**
	 * Season shortcode instance.
	 *
	 * @var Season
	 */
	private Season $season;

	/**
	 * Set up test fixtures.
	 */
	protected function setUp(): void {
		parent::setUp();
		$this->year      = new Year();
		$this->month     = new Month();
		$this->day       = new Day();
		$this->date      = new Date();
		$this->events    = new Events();
		$this->countdown = new Countdown();
		$this->season    = new Season();
	}

	/**
	 * Test current year shortcode.
	 */
	public function test_current_year(): void {
		$result   = $this->year->current_year( [] );
		$expected = current_datetime()->format( 'Y' );
		$this->assertEquals( $expected, $result );
	}

	/**
	 * Test year with positive offset.
	 */
	public function test_year_positive_offset(): void {
		$result   = $this->year->current_year( [ 'n' => 5 ] );
		$expected = (string) ( (int) current_datetime()->format( 'Y' ) + 5 );
		$this->assertEquals( $expected, $result );
	}

	/**
	 * Test year with negative offset.
	 */
	public function test_year_negative_offset(): void {
		$result   = $this->year->current_year( [ 'n' => -3 ] );
		$expected = (string) ( (int) current_datetime()->format( 'Y' ) - 3 );
		$this->assertEquals( $expected, $result );
	}

	/**
	 * Test REST year offset sanitizer preserves negative values.
	 */
	public function test_rest_sanitize_offset_preserves_negative_values(): void {
		$endpoint = new DatesEndpoint();
		$this->assertEquals( -3, $endpoint->sanitize_offset( '-3' ) );
		$this->assertEquals( 5, $endpoint->sanitize_offset( '5' ) );
	}

	/**
	 * Test REST type validation supports documented date types.
	 */
	public function test_rest_validate_type(): void {
		$endpoint = new DatesEndpoint();
		$this->assertTrue( $endpoint->validate_type( 'year' ) );
		$this->assertTrue( $endpoint->validate_type( 'season_south' ) );
		$this->assertFalse( $endpoint->validate_type( 'not_real' ) );
	}

	/**
	 * Test REST render endpoint accepts only plugin shortcodes.
	 */
	public function test_rest_validate_shortcode(): void {
		$endpoint = new DatesEndpoint();
		$this->assertTrue( $endpoint->validate_shortcode( '[year n="-3"]' ) );
		$this->assertTrue( $endpoint->validate_shortcode( '[daysuntil date="2026-12-25"]' ) );
		$this->assertFalse( $endpoint->validate_shortcode( '[gallery]' ) );
		$this->assertFalse( $endpoint->validate_shortcode( 'plain text [year]' ) );
	}

	/**
	 * Test next year shortcode.
	 */
	public function test_next_year(): void {
		$result   = $this->year->next_year();
		$expected = (string) ( (int) current_datetime()->format( 'Y' ) + 1 );
		$this->assertEquals( $expected, $result );
	}

	/**
	 * Test previous year shortcode.
	 */
	public function test_previous_year(): void {
		$result   = $this->year->previous_year();
		$expected = (string) ( (int) current_datetime()->format( 'Y' ) - 1 );
		$this->assertEquals( $expected, $result );
	}

	/**
	 * Test current month shortcode.
	 */
	public function test_current_month(): void {
		$result = $this->month->current_month();
		$this->assertNotEmpty( $result );
		$this->assertEquals( wp_date( 'F' ), $result );
	}

	/**
	 * Test current month short shortcode.
	 */
	public function test_current_month_short(): void {
		$result = $this->month->current_month_short();
		$this->assertEquals( wp_date( 'M' ), $result );
	}

	/**
	 * Test current month number with zero.
	 */
	public function test_current_month_number_zero(): void {
		$result = $this->month->current_month_number_zero();
		$this->assertEquals( wp_date( 'm' ), $result );
	}

	/**
	 * Test current month number without zero.
	 */
	public function test_current_month_number(): void {
		$result = $this->month->current_month_number();
		$this->assertEquals( wp_date( 'n' ), $result );
	}

	/**
	 * Test current day shortcode.
	 */
	public function test_current_day(): void {
		$result = $this->day->current_day();
		$this->assertEquals( wp_date( 'j' ), $result );
	}

	/**
	 * Test current weekday shortcode.
	 */
	public function test_current_weekday(): void {
		$result = $this->day->current_weekday();
		$this->assertEquals( wp_date( 'l' ), $result );
	}

	/**
	 * Test current weekday short shortcode.
	 */
	public function test_current_weekday_short(): void {
		$result = $this->day->current_weekday_short();
		$this->assertEquals( wp_date( 'D' ), $result );
	}

	/**
	 * Test current date shortcode.
	 */
	public function test_current_date(): void {
		$result = $this->date->current_date();
		$this->assertEquals( wp_date( 'F j, Y' ), $result );
	}

	/**
	 * Test month year shortcode.
	 */
	public function test_month_year(): void {
		$result = $this->date->month_year();
		$this->assertEquals( ucfirst( wp_date( 'F Y' ) ), $result );
	}

	/**
	 * Test Black Friday date calculation.
	 */
	public function test_black_friday(): void {
		$result = $this->events->black_friday();
		$this->assertNotEmpty( $result );
		$this->assertStringContainsString( 'November', $result );
	}

	/**
	 * Test Cyber Monday date calculation.
	 */
	public function test_cyber_monday(): void {
		$result = $this->events->cyber_monday();
		$this->assertNotEmpty( $result );
		// Cyber Monday can be in November or December.
		$this->assertTrue(
			strpos( $result, 'November' ) !== false || strpos( $result, 'December' ) !== false
		);
	}

	/**
	 * Test days until shortcode.
	 */
	public function test_days_until(): void {
		// Test with future date (10 days from now).
		$future_date = current_datetime()->modify( '+10 days' )->format( 'Y-m-d' );
		$result      = $this->countdown->days_until( [ 'date' => $future_date ] );
		$this->assertEquals( '10', $result );
	}

	/**
	 * Test days until with past date returns 0 or negative handled.
	 */
	public function test_days_until_past_date(): void {
		$past_date = current_datetime()->modify( '-5 days' )->format( 'Y-m-d' );
		$result    = $this->countdown->days_until( [ 'date' => $past_date ] );
		// Should return negative or the actual negative diff.
		$this->assertIsNumeric( $result );
	}

	/**
	 * Test days until with empty date.
	 */
	public function test_days_until_empty_date(): void {
		$result = $this->countdown->days_until( [] );
		$this->assertEquals( '', $result );
	}

	/**
	 * Test days since shortcode.
	 */
	public function test_days_since(): void {
		// Test with past date (10 days ago).
		$past_date = current_datetime()->modify( '-10 days' )->format( 'Y-m-d' );
		$result    = $this->countdown->days_since( [ 'date' => $past_date ] );
		$this->assertEquals( '10', $result );
	}

	/**
	 * Test days since with empty date.
	 */
	public function test_days_since_empty_date(): void {
		$result = $this->countdown->days_since( [] );
		$this->assertEquals( '', $result );
	}

	/**
	 * Test all outputs are escaped.
	 */
	public function test_outputs_are_escaped(): void {
		// All these should return escaped HTML.
		$year_result  = $this->year->current_year( [] );
		$month_result = $this->month->current_month();
		$day_result   = $this->day->current_day();

		// Check they don't contain unescaped HTML.
		$this->assertEquals( htmlspecialchars( $year_result, ENT_QUOTES, 'UTF-8' ), $year_result );
		$this->assertEquals( htmlspecialchars( $month_result, ENT_QUOTES, 'UTF-8' ), $month_result );
		$this->assertEquals( htmlspecialchars( $day_result, ENT_QUOTES, 'UTF-8' ), $day_result );
	}

	/**
	 * Test age shortcode with ordinal suffix.
	 */
	public function test_age_ordinal(): void {
		// Test with a date that would give age of 35.
		$birth_date = current_datetime()->modify( '-35 years' )->format( 'Y-m-d' );
		$result     = $this->countdown->age( [ 'date' => $birth_date, 'ordinal' => 'true' ] );
		$this->assertEquals( '35th', $result );
	}

	/**
	 * Test age shortcode with rank attribute (alias for ordinal).
	 */
	public function test_age_rank(): void {
		$birth_date = current_datetime()->modify( '-21 years' )->format( 'Y-m-d' );
		$result     = $this->countdown->age( [ 'date' => $birth_date, 'rank' => 'true' ] );
		$this->assertEquals( '21st', $result );
	}

	/**
	 * Test age ordinal with 2nd.
	 */
	public function test_age_ordinal_2nd(): void {
		$birth_date = current_datetime()->modify( '-2 years' )->format( 'Y-m-d' );
		$result     = $this->countdown->age( [ 'date' => $birth_date, 'ordinal' => 'true' ] );
		$this->assertEquals( '2nd', $result );
	}

	/**
	 * Test age ordinal with 3rd.
	 */
	public function test_age_ordinal_3rd(): void {
		$birth_date = current_datetime()->modify( '-3 years' )->format( 'Y-m-d' );
		$result     = $this->countdown->age( [ 'date' => $birth_date, 'ordinal' => 'true' ] );
		$this->assertEquals( '3rd', $result );
	}

	/**
	 * Test age ordinal with 11th (special case).
	 */
	public function test_age_ordinal_11th(): void {
		$birth_date = current_datetime()->modify( '-11 years' )->format( 'Y-m-d' );
		$result     = $this->countdown->age( [ 'date' => $birth_date, 'ordinal' => 'true' ] );
		$this->assertEquals( '11th', $result );
	}

	/**
	 * Test age ordinal with 12th (special case).
	 */
	public function test_age_ordinal_12th(): void {
		$birth_date = current_datetime()->modify( '-12 years' )->format( 'Y-m-d' );
		$result     = $this->countdown->age( [ 'date' => $birth_date, 'ordinal' => 'true' ] );
		$this->assertEquals( '12th', $result );
	}

	/**
	 * Test age ordinal with 13th (special case).
	 */
	public function test_age_ordinal_13th(): void {
		$birth_date = current_datetime()->modify( '-13 years' )->format( 'Y-m-d' );
		$result     = $this->countdown->age( [ 'date' => $birth_date, 'ordinal' => 'true' ] );
		$this->assertEquals( '13th', $result );
	}

	/**
	 * Test age without ordinal returns number only.
	 */
	public function test_age_without_ordinal(): void {
		$birth_date = current_datetime()->modify( '-25 years' )->format( 'Y-m-d' );
		$result     = $this->countdown->age( [ 'date' => $birth_date ] );
		$this->assertEquals( '25', $result );
	}

	/**
	 * Test current season returns valid season name.
	 */
	public function test_current_season(): void {
		$result        = $this->season->current_season( [] );
		$valid_seasons = [ 'Spring', 'Summer', 'Fall', 'Winter' ];
		$this->assertContains( $result, $valid_seasons );
	}

	/**
	 * Test season for northern hemisphere.
	 */
	public function test_season_north(): void {
		$result        = $this->season->current_season( [ 'region' => 'north' ] );
		$valid_seasons = [ 'Spring', 'Summer', 'Fall', 'Winter' ];
		$this->assertContains( $result, $valid_seasons );
	}

	/**
	 * Test season for southern hemisphere.
	 */
	public function test_season_south(): void {
		$result        = $this->season->current_season( [ 'region' => 'south' ] );
		$valid_seasons = [ 'Spring', 'Summer', 'Fall', 'Winter' ];
		$this->assertContains( $result, $valid_seasons );
	}

	/**
	 * Test that northern and southern seasons are opposite.
	 */
	public function test_season_hemispheres_opposite(): void {
		$north = $this->season->current_season( [ 'region' => 'north' ] );
		$south = $this->season->current_season( [ 'region' => 'south' ] );

		$opposites = [
			'Spring' => 'Fall',
			'Summer' => 'Winter',
			'Fall'   => 'Spring',
			'Winter' => 'Summer',
		];

		$this->assertEquals( $opposites[ $north ], $south );
	}

	/**
	 * Test season output is escaped.
	 */
	public function test_season_is_escaped(): void {
		$result = $this->season->current_season( [] );
		$this->assertEquals( htmlspecialchars( $result, ENT_QUOTES, 'UTF-8' ), $result );
	}

	/**
	 * Calendar-day differences stay correct across a DST transition.
	 */
	public function test_days_until_uses_site_calendar_across_dst(): void {
		$original_timezone = get_option( 'timezone_string' );
		update_option( 'timezone_string', 'America/New_York' );

		$clock = static function (): DateTimeImmutable {
			return new DateTimeImmutable( '2026-03-08 12:00:00', new DateTimeZone( 'America/New_York' ) );
		};
		add_filter( 'dmyip_current_datetime', $clock );

		try {
			$result = ( new DateRenderer() )->render( 'daysuntil', [ 'date' => '2026-03-09' ] );
			$this->assertSame( '1', $result );
		} finally {
			remove_filter( 'dmyip_current_datetime', $clock );
			update_option( 'timezone_string', $original_timezone );
		}
	}

	/**
	 * Annual dates roll to the next year after the event passes.
	 */
	public function test_next_occurrence_rolls_over_after_event(): void {
		$clock = static function (): DateTimeImmutable {
			return new DateTimeImmutable( '2026-07-20 08:00:00', new DateTimeZone( 'UTC' ) );
		};
		add_filter( 'dmyip_current_datetime', $clock );

		try {
			$renderer = new DateRenderer();
			$this->assertSame(
				'July 19, 2027',
				$renderer->render( 'nextoccurrence', [ 'date' => '07-19', 'format' => 'F j, Y' ] )
			);
			$this->assertSame(
				'January 31, 2027',
				$renderer->render(
					'nextoccurrence',
					[
						'rule'   => 'last sunday of january',
						'format' => 'F j, Y',
					]
				)
			);
		} finally {
			remove_filter( 'dmyip_current_datetime', $clock );
		}
	}

	/**
	 * Leap-day recurrences skip invalid calendar years.
	 */
	public function test_next_occurrence_skips_to_the_next_leap_day(): void {
		$clock = static function (): DateTimeImmutable {
			return new DateTimeImmutable( '2026-07-20 08:00:00', new DateTimeZone( 'UTC' ) );
		};
		add_filter( 'dmyip_current_datetime', $clock );

		try {
			$renderer = new DateRenderer();
			$this->assertSame(
				'February 29, 2028',
				$renderer->render( 'nextoccurrence', [ 'date' => '02-29', 'format' => 'F j, Y' ] )
			);
			$this->assertSame( '589', $renderer->render( 'daysuntilnext', [ 'date' => '02-29' ] ) );
		} finally {
			remove_filter( 'dmyip_current_datetime', $clock );
		}
	}

	/**
	 * Month cutoffs and case transforms work through the unified renderer.
	 */
	public function test_month_rollover_and_case_transform(): void {
		$clock = static function (): DateTimeImmutable {
			return new DateTimeImmutable( '2026-07-20 08:00:00', new DateTimeZone( 'UTC' ) );
		};
		add_filter( 'dmyip_current_datetime', $clock );

		try {
			$renderer = new DateRenderer();
			$this->assertSame( 'August', $renderer->render( 'month', [ 'rollover_day' => 20 ] ) );
			$this->assertSame( 'JULY', $renderer->render( 'month', [ 'case' => 'upper' ] ) );
		} finally {
			remove_filter( 'dmyip_current_datetime', $clock );
		}
	}
}
