<?php
/**
 * SEOPress integration.
 *
 * @package DMYIP
 */

declare(strict_types=1);

namespace DMYIP\Integrations;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * SEOPress plugin integration.
 */
class SEOPress {

	/**
	 * Register filters.
	 *
	 * @return void
	 */
	public function register(): void {
		add_filter( 'seopress_titles_title', 'do_shortcode' );
		add_filter( 'seopress_titles_desc', 'do_shortcode' );
	}
}
