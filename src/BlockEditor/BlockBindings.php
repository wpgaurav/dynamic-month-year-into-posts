<?php
/**
 * Block Bindings API support.
 *
 * @package DMYIP
 */

declare(strict_types=1);

namespace DMYIP\BlockEditor;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use DMYIP\Date\DateRenderer;

/**
 * Block Bindings API integration for dynamic dates.
 */
class BlockBindings {

	/**
	 * Register block bindings source.
	 *
	 * @return void
	 */
	public function register(): void {
		add_action( 'init', [ $this, 'register_bindings_source' ] );
	}

	/**
	 * Register the bindings source.
	 *
	 * @return void
	 */
	public function register_bindings_source(): void {
		// Block Bindings API was introduced in WP 6.5.
		if ( ! function_exists( 'register_block_bindings_source' ) ) {
			return;
		}

		register_block_bindings_source(
			'dmyip/date',
			[
				'label'              => __( 'Dynamic Date', 'dynamic-month-year-into-posts' ),
				'get_value_callback' => [ $this, 'get_binding_value' ],
				'uses_context'       => [ 'postId' ],
			]
		);
	}

	/**
	 * Get the binding value.
	 *
	 * @param array<string, mixed> $source_args Source arguments.
	 * @param object               $block_instance Block instance.
	 * @param string               $attribute_name Attribute name.
	 * @return string|null
	 */
	public function get_binding_value( array $source_args, $block_instance, string $attribute_name ): ?string {
		unset( $attribute_name );

		$type    = isset( $source_args['type'] ) ? sanitize_key( (string) $source_args['type'] ) : 'year';
		$post_id = isset( $block_instance->context['postId'] ) ? (int) $block_instance->context['postId'] : 0;

		return ( new DateRenderer() )->render( $type, $source_args, $post_id );
	}
}
