<?php
/**
 * Bricks Builder integration.
 *
 * @package DMYIP
 */

declare(strict_types=1);

namespace DMYIP\Integrations;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Bricks Builder page builder integration.
 *
 * Processes shortcodes inside Bricks dynamic data output
 * so DMYIP shortcodes render correctly in Bricks elements.
 */
class Bricks {

	/**
	 * Register filters.
	 *
	 * @return void
	 */
	public function register(): void {
		add_filter( 'bricks/dynamic_data/render_content', array( $this, 'render_shortcodes' ), 20, 3 );
	}

	/**
	 * Process shortcodes in Bricks dynamic data content.
	 *
	 * @param string $content The dynamic data content.
	 * @param mixed  $post    The post object.
	 * @param string $context The rendering context.
	 * @return string
	 */
	public function render_shortcodes( $content, $post, $context ): string {
		if ( ! is_string( $content ) || strpos( $content, '[' ) === false ) {
			return $content;
		}

		return do_shortcode( $content );
	}
}
