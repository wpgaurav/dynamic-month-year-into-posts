<?php
/**
 * Season shortcodes.
 *
 * @package DMYIP
 */

declare(strict_types=1);

namespace DMYIP\Shortcodes;

use DMYIP\Date\DateRenderer;

/**
 * Season-related shortcodes.
 */
class Season {

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
		add_shortcode( 'season', [ $this, 'current_season' ] );
	}

	/**
	 * Get current season.
	 *
	 * Supports both Northern and Southern hemispheres.
	 * Northern hemisphere (default): Spring (Mar-May), Summer (Jun-Aug), Fall (Sep-Nov), Winter (Dec-Feb)
	 * Southern hemisphere: Seasons are reversed.
	 *
	 * @param array<string, mixed>|string $atts Shortcode attributes.
	 * @return string
	 */
	public function current_season( $atts ): string {
		$attributes = shortcode_atts(
			[
				'region' => 'north',
			],
			$atts
		);

		return esc_html( $this->renderer->render( 'season', $attributes ) );
	}
}
