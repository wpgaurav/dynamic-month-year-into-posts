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
use PHPUnit\Framework\TestCase;

/**
 * Test shortcode classes.
 */
class ShortcodesTest extends TestCase {

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
	}

	/**
	 * Test current year shortcode.
	 */
	public function test_current_year(): void {
		$result   = $this->year->current_year( [] );
		$expected = date( 'Y' );
		$this->assertEquals( $expected, $result );
	}

	/**
	 * Test year with positive offset.
	 */
	public function test_year_positive_offset(): void {
		$result   = $this->year->current_year( [ 'n' => 5 ] );
		$expected = (string) ( (int) date( 'Y' ) + 5 );
		$this->assertEquals( $expected, $result );
	}

	/**
	 * Test year with negative offset.
	 */
	public function test_year_negative_offset(): void {
		$result   = $this->year->current_year( [ 'n' => -3 ] );
		$expected = (string) ( (int) date( 'Y' ) - 3 );
		$this->assertEquals( $expected, $result );
	}

	/**
	 * Test next year shortcode.
	 */
	public function test_next_year(): void {
		$result   = $this->year->next_year();
		$expected = (string) ( (int) date( 'Y' ) + 1 );
		$this->assertEquals( $expected, $result );
	}

	/**
	 * Test previous year shortcode.
	 */
	public function test_previous_year(): void {
		$result   = $this->year->previous_year();
		$expected = (string) ( (int) date( 'Y' ) - 1 );
		$this->assertEquals( $expected, $result );
	}

	/**
	 * Test current month shortcode.
	 */
	public function test_current_month(): void {
		$result = $this->month->current_month();
		$this->assertNotEmpty( $result );
		$this->assertEquals( date( 'F' ), $result );
	}

	/**
	 * Test current month short shortcode.
	 */
	public function test_current_month_short(): void {
		$result = $this->month->current_month_short();
		$this->assertEquals( date( 'M' ), $result );
	}

	/**
	 * Test current month number with zero.
	 */
	public function test_current_month_number_zero(): void {
		$result = $this->month->current_month_number_zero();
		$this->assertEquals( date( 'm' ), $result );
	}

	/**
	 * Test current month number without zero.
	 */
	public function test_current_month_number(): void {
		$result = $this->month->current_month_number();
		$this->assertEquals( date( 'n' ), $result );
	}

	/**
	 * Test current day shortcode.
	 */
	public function test_current_day(): void {
		$result = $this->day->current_day();
		$this->assertEquals( date( 'j' ), $result );
	}

	/**
	 * Test current weekday shortcode.
	 */
	public function test_current_weekday(): void {
		$result = $this->day->current_weekday();
		$this->assertEquals( date( 'l' ), $result );
	}

	/**
	 * Test current weekday short shortcode.
	 */
	public function test_current_weekday_short(): void {
		$result = $this->day->current_weekday_short();
		$this->assertEquals( date( 'D' ), $result );
	}

	/**
	 * Test current date shortcode.
	 */
	public function test_current_date(): void {
		$result = $this->date->current_date();
		$this->assertEquals( date( 'F j, Y' ), $result );
	}

	/**
	 * Test month year shortcode.
	 */
	public function test_month_year(): void {
		$result = $this->date->month_year();
		$this->assertEquals( ucfirst( date( 'F Y' ) ), $result );
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
			str_contains( $result, 'November' ) || str_contains( $result, 'December' )
		);
	}

	/**
	 * Test days until shortcode.
	 */
	public function test_days_until(): void {
		// Test with future date (10 days from now).
		$future_date = date( 'Y-m-d', strtotime( '+10 days' ) );
		$result      = $this->countdown->days_until( [ 'date' => $future_date ] );
		$this->assertEquals( '10', $result );
	}

	/**
	 * Test days until with past date returns 0 or negative handled.
	 */
	public function test_days_until_past_date(): void {
		$past_date = date( 'Y-m-d', strtotime( '-5 days' ) );
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
		$past_date = date( 'Y-m-d', strtotime( '-10 days' ) );
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
}
