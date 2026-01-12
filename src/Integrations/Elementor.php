<?php
/**
 * Elementor integration.
 *
 * @package DMYIP
 */

declare(strict_types=1);

namespace DMYIP\Integrations;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Elementor page builder integration.
 */
class Elementor {

	/**
	 * Register filters.
	 *
	 * @return void
	 */
	public function register(): void {
		add_filter( 'elementor/widget/render_content', 'do_shortcode' );
	}
}
