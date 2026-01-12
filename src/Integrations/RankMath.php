<?php
/**
 * Rank Math SEO integration.
 *
 * @package DMYIP
 */

declare(strict_types=1);

namespace DMYIP\Integrations;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Rank Math SEO plugin integration.
 */
class RankMath {

	/**
	 * Register filters.
	 *
	 * @return void
	 */
	public function register(): void {
		// Title and description.
		add_filter( 'rank_math/frontend/title', 'do_shortcode' );
		add_filter( 'rank_math/frontend/description', 'do_shortcode' );

		// Product descriptions.
		add_filter( 'rank_math/product_description/apply_shortcode', '__return_true' );

		// Breadcrumbs.
		add_filter( 'rank_math/frontend/breadcrumb/html', 'do_shortcode' );

		// Open Graph.
		add_filter( 'rank_math/opengraph/facebook/og_title', 'do_shortcode' );
		add_filter( 'rank_math/opengraph/facebook/og_description', 'do_shortcode' );
		add_filter( 'rank_math/opengraph/twitter/title', 'do_shortcode' );
		add_filter( 'rank_math/opengraph/twitter/description', 'do_shortcode' );

		// JSON-LD Schema.
		add_filter( 'rank_math/json_ld', [ $this, 'process_json_ld' ], PHP_INT_MAX, 2 );
	}

	/**
	 * Process shortcodes in JSON-LD data recursively.
	 *
	 * @param array<string, mixed> $data    JSON-LD data.
	 * @param mixed                $context Context.
	 * @return array<string, mixed>
	 */
	public function process_json_ld( array $data, $context ): array {
		array_walk_recursive(
			$data,
			function ( &$value ) {
				if ( is_string( $value ) ) {
					$value = do_shortcode( $value );
				}
			}
		);
		return $data;
	}
}
