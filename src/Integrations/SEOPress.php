<?php
/**
 * SEOPress integration.
 *
 * @package DMYIP
 */

declare(strict_types=1);

namespace DMYIP\Integrations;

use DMYIP\Shortcodes\Registry;

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
		add_filter( 'seopress_titles_title', [ Registry::class, 'render' ] );
		add_filter( 'seopress_titles_desc', [ Registry::class, 'render' ] );
	}
}
