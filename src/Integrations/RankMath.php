<?php
/**
 * Rank Math SEO integration.
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
		add_filter( 'rank_math/frontend/title', [ Registry::class, 'render' ] );
		add_filter( 'rank_math/frontend/description', [ Registry::class, 'render' ] );

		// Breadcrumbs.
		add_filter( 'rank_math/frontend/breadcrumb/html', [ Registry::class, 'render' ] );

		// Open Graph.
		add_filter( 'rank_math/opengraph/facebook/og_title', [ Registry::class, 'render' ] );
		add_filter( 'rank_math/opengraph/facebook/og_description', [ Registry::class, 'render' ] );
		add_filter( 'rank_math/opengraph/twitter/title', [ Registry::class, 'render' ] );
		add_filter( 'rank_math/opengraph/twitter/description', [ Registry::class, 'render' ] );

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
		unset( $context );

		array_walk_recursive(
			$data,
			function ( &$value ) {
				if ( is_string( $value ) ) {
					$value = Registry::render( $value );
				}
			}
		);
		return $data;
	}
}
