<?php
/**
 * Countdown shortcodes.
 *
 * @package DMYIP
 */

declare(strict_types=1);

namespace DMYIP\Shortcodes;

use DMYIP\Date\DateRenderer;

/**
 * Countdown-related shortcodes.
 */
class Countdown {

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
		add_shortcode( 'daysuntil', [ $this, 'days_until' ] );
		add_shortcode( 'dayssince', [ $this, 'days_since' ] );
		add_shortcode( 'age', [ $this, 'age' ] );
	}

	/**
	 * Days until a specific date.
	 *
	 * @param array<string, mixed>|string $atts Shortcode attributes.
	 * @return string
	 */
	public function days_until( $atts ): string {
		$attributes = shortcode_atts(
			[
				'date'  => '',
				'clamp' => 'false',
			],
			$atts
		);
		return esc_html( $this->renderer->render( 'daysuntil', $attributes ) );
	}

	/**
	 * Days since a specific date.
	 *
	 * @param array<string, mixed>|string $atts Shortcode attributes.
	 * @return string
	 */
	public function days_since( $atts ): string {
		$attributes = shortcode_atts(
			[
				'date'  => '',
				'clamp' => 'false',
			],
			$atts
		);
		return esc_html( $this->renderer->render( 'dayssince', $attributes ) );
	}

	/**
	 * Calculate age from a birth date.
	 *
	 * @param array<string, mixed>|string $atts Shortcode attributes.
	 * @return string
	 */
	public function age( $atts ): string {
		$attributes = shortcode_atts(
			[
				'date'    => '',
				'format'  => 'y',
				'ordinal' => '',
				'rank'    => '',
			],
			$atts
		);

		return esc_html( $this->renderer->render( 'age', $attributes ) );
	}
}
