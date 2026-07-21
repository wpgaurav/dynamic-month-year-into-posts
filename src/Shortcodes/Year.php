<?php
/**
 * Year shortcodes.
 *
 * @package DMYIP
 */

declare(strict_types=1);

namespace DMYIP\Shortcodes;

use DMYIP\Date\DateRenderer;

/**
 * Year-related shortcodes.
 */
class Year {

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
		add_shortcode( 'year', [ $this, 'current_year' ] );
		add_shortcode( 'nyear', [ $this, 'next_year' ] );
		add_shortcode( 'nnyear', [ $this, 'next_next_year' ] );
		add_shortcode( 'pyear', [ $this, 'previous_year' ] );
		add_shortcode( 'ppyear', [ $this, 'previous_previous_year' ] );
	}

	/**
	 * Current year with optional offset.
	 *
	 * @param array<string, mixed>|string $atts Shortcode attributes.
	 * @return string
	 */
	public function current_year( $atts ): string {
		$attributes = shortcode_atts( [ 'n' => 0 ], $atts );
		return esc_html( $this->renderer->render( 'year', $attributes ) );
	}

	/**
	 * Next year.
	 *
	 * @return string
	 */
	public function next_year(): string {
		return esc_html( $this->renderer->render( 'nyear' ) );
	}

	/**
	 * Year after next.
	 *
	 * @return string
	 */
	public function next_next_year(): string {
		return esc_html( $this->renderer->render( 'nnyear' ) );
	}

	/**
	 * Previous year.
	 *
	 * @return string
	 */
	public function previous_year(): string {
		return esc_html( $this->renderer->render( 'pyear' ) );
	}

	/**
	 * Year before previous.
	 *
	 * @return string
	 */
	public function previous_previous_year(): string {
		return esc_html( $this->renderer->render( 'ppyear' ) );
	}
}
