<?php
/**
 * Day shortcodes.
 *
 * @package DMYIP
 */

declare(strict_types=1);

namespace DMYIP\Shortcodes;

use DMYIP\Date\DateRenderer;

/**
 * Day-related shortcodes.
 */
class Day {

	/**
	 * Shared renderer.
	 *
	 * @var DateRenderer
	 */
	private DateRenderer $renderer;

	/**
	 * Constructor.
	 *
	 * @param DateRenderer|null $renderer Shared renderer.
	 */
	public function __construct( ?DateRenderer $renderer = null ) {
		$this->renderer = $renderer ?? new DateRenderer();
	}

	/**
	 * Register shortcodes.
	 *
	 * @return void
	 */
	public function register(): void {
		add_shortcode( 'dt', [ $this, 'current_day' ] );
		add_shortcode( 'nd', [ $this, 'next_day' ] );
		add_shortcode( 'pd', [ $this, 'prev_day' ] );
		add_shortcode( 'weekday', [ $this, 'current_weekday' ] );
		add_shortcode( 'wd', [ $this, 'current_weekday_short' ] );
	}

	/**
	 * Current day of month.
	 *
	 * @return string
	 */
	public function current_day(): string {
		return esc_html( $this->renderer->render( 'day' ) );
	}

	/**
	 * Next day number.
	 *
	 * @return string
	 */
	public function next_day(): string {
		return esc_html( $this->renderer->render( 'next_day' ) );
	}

	/**
	 * Previous day number.
	 *
	 * @return string
	 */
	public function prev_day(): string {
		return esc_html( $this->renderer->render( 'previous_day' ) );
	}

	/**
	 * Current weekday full name.
	 *
	 * @return string
	 */
	public function current_weekday(): string {
		return esc_html( $this->renderer->render( 'weekday' ) );
	}

	/**
	 * Current weekday short name.
	 *
	 * @return string
	 */
	public function current_weekday_short(): string {
		return esc_html( $this->renderer->render( 'weekday_short' ) );
	}
}
