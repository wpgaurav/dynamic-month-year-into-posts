<?php
/**
 * Date shortcodes.
 *
 * @package DMYIP
 */

declare(strict_types=1);

namespace DMYIP\Shortcodes;

use DMYIP\Date\DateRenderer;

/**
 * Combined date shortcodes.
 */
class Date {

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
		add_shortcode( 'date', [ $this, 'current_date' ] );
		add_shortcode( 'monthyear', [ $this, 'month_year' ] );
		add_shortcode( 'nmonthyear', [ $this, 'next_month_year' ] );
		add_shortcode( 'pmonthyear', [ $this, 'prev_month_year' ] );
	}

	/**
	 * Current full date.
	 *
	 * @return string
	 */
	public function current_date(): string {
		return esc_html( $this->renderer->render( 'date' ) );
	}

	/**
	 * Current month and year.
	 *
	 * @return string
	 */
	public function month_year(): string {
		return esc_html( $this->renderer->render( 'monthyear', [ 'case' => 'title' ] ) );
	}

	/**
	 * Next month and year.
	 *
	 * @return string
	 */
	public function next_month_year(): string {
		return esc_html( $this->renderer->render( 'next_month_year', [ 'case' => 'title' ] ) );
	}

	/**
	 * Previous month and year.
	 *
	 * @return string
	 */
	public function prev_month_year(): string {
		return esc_html( $this->renderer->render( 'previous_month_year', [ 'case' => 'title' ] ) );
	}
}
