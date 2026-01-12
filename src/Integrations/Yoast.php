<?php
/**
 * Yoast SEO integration.
 *
 * @package DMYIP
 */

declare(strict_types=1);

namespace DMYIP\Integrations;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Yoast SEO plugin integration.
 */
class Yoast {

	/**
	 * Register filters.
	 *
	 * @return void
	 */
	public function register(): void {
		add_filter( 'wpseo_title', 'do_shortcode' );
		add_filter( 'wpseo_metadesc', 'do_shortcode' );
		add_filter( 'wpseo_opengraph_title', 'do_shortcode' );
		add_filter( 'wpseo_opengraph_desc', 'do_shortcode' );
		add_filter( 'wpseo_schema_webpage', [ $this, 'process_schema_webpage' ] );
	}

	/**
	 * Process schema webpage data.
	 *
	 * @param array<string, mixed> $data Schema data.
	 * @return array<string, mixed>
	 */
	public function process_schema_webpage( array $data ): array {
		if ( isset( $data['name'] ) && is_string( $data['name'] ) ) {
			$data['name'] = do_shortcode( $data['name'] );
		}
		return $data;
	}
}
