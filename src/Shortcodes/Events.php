<?php
/**
 * Event shortcodes.
 *
 * @package DMYIP
 */

declare(strict_types=1);

namespace DMYIP\Shortcodes;

use DMYIP\Date\DateRenderer;

/**
 * Special event date shortcodes.
 */
class Events {

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
		add_shortcode( 'blackfriday', [ $this, 'black_friday' ] );
		add_shortcode( 'cybermonday', [ $this, 'cyber_monday' ] );
	}

	/**
	 * Black Friday date (day after Thanksgiving).
	 *
	 * @return string
	 */
	public function black_friday(): string {
		return esc_html( $this->renderer->render( 'blackfriday' ) );
	}

	/**
	 * Cyber Monday date (Monday after Thanksgiving).
	 *
	 * @return string
	 */
	public function cyber_monday(): string {
		return esc_html( $this->renderer->render( 'cybermonday' ) );
	}
}
