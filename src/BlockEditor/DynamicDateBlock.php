<?php
/**
 * Dynamic Date block.
 *
 * @package DMYIP
 */

declare(strict_types=1);

namespace DMYIP\BlockEditor;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Dynamic Date Gutenberg block.
 */
class DynamicDateBlock {

	/**
	 * Plugin directory path.
	 *
	 * @var string
	 */
	private string $plugin_dir;

	/**
	 * Constructor.
	 *
	 * @param string $plugin_dir Plugin directory path.
	 */
	public function __construct( string $plugin_dir ) {
		$this->plugin_dir = $plugin_dir;
	}

	/**
	 * Register the block.
	 *
	 * @return void
	 */
	public function register(): void {
		add_action( 'init', [ $this, 'register_block' ] );
	}

	/**
	 * Register block type.
	 *
	 * @return void
	 */
	public function register_block(): void {
		// Register Dynamic Date block.
		$dynamic_date_dir = $this->plugin_dir . 'build/blocks/dynamic-date';

		if ( file_exists( $dynamic_date_dir . '/block.json' ) ) {
			register_block_type(
				$dynamic_date_dir,
				[
					'render_callback' => [ $this, 'render' ],
				]
			);
		}

		// Register Countdown block (with Interactivity API).
		$countdown_dir = $this->plugin_dir . 'build/blocks/countdown';

		if ( file_exists( $countdown_dir . '/block.json' ) ) {
			register_block_type( $countdown_dir );
		}
	}

	/**
	 * Render the block.
	 *
	 * @param array<string, mixed> $attributes Block attributes.
	 * @return string
	 */
	public function render( array $attributes ): string {
		$type   = $attributes['type'] ?? 'year';
		$format = $attributes['format'] ?? '';
		$offset = $attributes['offset'] ?? 0;
		$date   = $attributes['date'] ?? '';

		$output = $this->get_date_output( $type, $format, $offset, $date );

		$wrapper_attributes = get_block_wrapper_attributes();

		return sprintf(
			'<span %s>%s</span>',
			$wrapper_attributes,
			esc_html( $output )
		);
	}

	/**
	 * Get the date output based on type.
	 *
	 * @param string $type   Date type.
	 * @param string $format Custom format.
	 * @param int    $offset Offset value.
	 * @param string $date   Target date for countdown.
	 * @return string
	 */
	private function get_date_output( string $type, string $format, int $offset, string $date ): string {
		switch ( $type ) {
			case 'year':
				return $this->get_year( $offset );
			case 'nyear':
				return $this->get_year( 1 );
			case 'pyear':
				return $this->get_year( -1 );
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
				return $this->get_post_date( 'published' );
			case 'modified':
				return $this->get_post_date( 'modified' );
			case 'blackfriday':
				return $this->get_black_friday();
			case 'cybermonday':
				return $this->get_cyber_monday();
			case 'daysuntil':
				return $this->get_days_until( $date );
			case 'dayssince':
				return $this->get_days_since( $date );
			default:
				return date_i18n( 'Y' );
		}
	}

	/**
	 * Get year with offset.
	 *
	 * @param int $offset Year offset.
	 * @return string
	 */
	private function get_year( int $offset ): string {
		return (string) ( (int) date_i18n( 'Y' ) + $offset );
	}

	/**
	 * Get post date.
	 *
	 * @param string $type published or modified.
	 * @return string
	 */
	private function get_post_date( string $type ): string {
		$date_format = get_option( 'date_format' );

		if ( 'modified' === $type ) {
			$timestamp = get_the_modified_time( 'U' );
		} else {
			$timestamp = get_the_time( 'U' );
		}

		if ( ! $timestamp ) {
			return '';
		}

		return date_i18n( $date_format, (int) $timestamp );
	}

	/**
	 * Get Black Friday date.
	 *
	 * @return string
	 */
	private function get_black_friday(): string {
		$year         = gmdate( 'Y' );
		$thanksgiving = strtotime( "fourth thursday of november {$year}" );

		if ( ! $thanksgiving ) {
			return '';
		}

		return date_i18n( 'F j', strtotime( '+1 day', $thanksgiving ) );
	}

	/**
	 * Get Cyber Monday date.
	 *
	 * @return string
	 */
	private function get_cyber_monday(): string {
		$year         = gmdate( 'Y' );
		$thanksgiving = strtotime( "fourth thursday of november {$year}" );

		if ( ! $thanksgiving ) {
			return '';
		}

		return date_i18n( 'F j', strtotime( '+4 days', $thanksgiving ) );
	}

	/**
	 * Get days until a date.
	 *
	 * @param string $date Target date.
	 * @return string
	 */
	private function get_days_until( string $date ): string {
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
	}

	/**
	 * Get days since a date.
	 *
	 * @param string $date Target date.
	 * @return string
	 */
	private function get_days_since( string $date ): string {
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
	}
}
