<?php
/**
 * Post date shortcodes.
 *
 * @package DMYIP
 */

declare(strict_types=1);

namespace DMYIP\Shortcodes;

use DMYIP\Date\DateRenderer;

/**
 * Post-specific date shortcodes.
 */
class PostDate {

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
		add_shortcode( 'datepublished', [ $this, 'published' ] );
		add_shortcode( 'datemodified', [ $this, 'modified' ] );
	}

	/**
	 * Post publication date.
	 *
	 * @return string
	 */
	public function published(): string {
		return esc_html( $this->renderer->render( 'published' ) );
	}

	/**
	 * Post modified date.
	 *
	 * @return string
	 */
	public function modified(): string {
		return esc_html( $this->renderer->render( 'modified' ) );
	}
}
