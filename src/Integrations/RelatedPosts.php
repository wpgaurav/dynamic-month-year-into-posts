<?php
/**
 * Related posts plugins integration.
 *
 * @package DMYIP
 */

declare(strict_types=1);

namespace DMYIP\Integrations;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Related posts plugins integration.
 */
class RelatedPosts {

	/**
	 * Register filters.
	 *
	 * @return void
	 */
	public function register(): void {
		// Contextual Related Posts.
		add_filter( 'crp_title', 'do_shortcode' );
	}
}
