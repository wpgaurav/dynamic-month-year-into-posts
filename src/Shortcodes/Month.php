<?php
/**
 * Month shortcodes.
 *
 * @package DMYIP
 */

declare(strict_types=1);

namespace DMYIP\Shortcodes;

use DMYIP\Date\DateRenderer;

/**
 * Month-related shortcodes.
 */
class Month {

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
		add_shortcode( 'month', [ $this, 'current_month' ] );
		add_shortcode( 'cmonth', [ $this, 'current_month_caps' ] );
		add_shortcode( 'mon', [ $this, 'current_month_short' ] );
		add_shortcode( 'cmon', [ $this, 'current_month_short_caps' ] );
		add_shortcode( 'mm', [ $this, 'current_month_number_zero' ] );
		add_shortcode( 'mn', [ $this, 'current_month_number' ] );
		add_shortcode( 'nmonth', [ $this, 'next_month' ] );
		add_shortcode( 'cnmonth', [ $this, 'next_month_caps' ] );
		add_shortcode( 'nmon', [ $this, 'next_month_short' ] );
		add_shortcode( 'cnmon', [ $this, 'next_month_short_caps' ] );
		add_shortcode( 'pmonth', [ $this, 'prev_month' ] );
		add_shortcode( 'cpmonth', [ $this, 'prev_month_caps' ] );
		add_shortcode( 'pmon', [ $this, 'prev_month_short' ] );
		add_shortcode( 'cpmon', [ $this, 'prev_month_short_caps' ] );
	}

	/**
	 * Current month full name.
	 *
	 * @return string
	 */
	public function current_month(): string {
		return esc_html( $this->renderer->render( 'month' ) );
	}

	/**
	 * Current month capitalized.
	 *
	 * @return string
	 */
	public function current_month_caps(): string {
		return esc_html( $this->renderer->render( 'month', [ 'case' => 'title' ] ) );
	}

	/**
	 * Current month short name.
	 *
	 * @return string
	 */
	public function current_month_short(): string {
		return esc_html( $this->renderer->render( 'month_short' ) );
	}

	/**
	 * Current month short capitalized.
	 *
	 * @return string
	 */
	public function current_month_short_caps(): string {
		return esc_html( $this->renderer->render( 'month_short', [ 'case' => 'title' ] ) );
	}

	/**
	 * Current month number with leading zero.
	 *
	 * @return string
	 */
	public function current_month_number_zero(): string {
		return esc_html( $this->renderer->render( 'month_number_zero' ) );
	}

	/**
	 * Current month number without leading zero.
	 *
	 * @return string
	 */
	public function current_month_number(): string {
		return esc_html( $this->renderer->render( 'month_number' ) );
	}

	/**
	 * Next month full name.
	 *
	 * @return string
	 */
	public function next_month(): string {
		return esc_html( $this->renderer->render( 'next_month' ) );
	}

	/**
	 * Next month capitalized.
	 *
	 * @return string
	 */
	public function next_month_caps(): string {
		return esc_html( $this->renderer->render( 'next_month', [ 'case' => 'title' ] ) );
	}

	/**
	 * Next month short name.
	 *
	 * @return string
	 */
	public function next_month_short(): string {
		return esc_html( $this->renderer->render( 'next_month_short' ) );
	}

	/**
	 * Next month short capitalized.
	 *
	 * @return string
	 */
	public function next_month_short_caps(): string {
		return esc_html( $this->renderer->render( 'next_month_short', [ 'case' => 'title' ] ) );
	}

	/**
	 * Previous month full name.
	 *
	 * @return string
	 */
	public function prev_month(): string {
		return esc_html( $this->renderer->render( 'previous_month' ) );
	}

	/**
	 * Previous month capitalized.
	 *
	 * @return string
	 */
	public function prev_month_caps(): string {
		return esc_html( $this->renderer->render( 'previous_month', [ 'case' => 'title' ] ) );
	}

	/**
	 * Previous month short name.
	 *
	 * @return string
	 */
	public function prev_month_short(): string {
		return esc_html( $this->renderer->render( 'previous_month_short' ) );
	}

	/**
	 * Previous month short capitalized.
	 *
	 * @return string
	 */
	public function prev_month_short_caps(): string {
		return esc_html( $this->renderer->render( 'previous_month_short', [ 'case' => 'title' ] ) );
	}
}
