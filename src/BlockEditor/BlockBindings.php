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
		$type = $source_args['type'] ?? 'year';

		// Map binding types to shortcodes.
		$shortcode_map = [
			'year'          => '[year]',
			'nyear'         => '[nyear]',
			'pyear'         => '[pyear]',
			'month'         => '[month]',
			'month_short'   => '[mon]',
			'nmonth'        => '[nmonth]',
			'pmonth'        => '[pmonth]',
			'date'          => '[date]',
			'monthyear'     => '[monthyear]',
			'day'           => '[dt]',
			'weekday'       => '[weekday]',
			'weekday_short' => '[wd]',
			'published'     => '[datepublished]',
			'modified'      => '[datemodified]',
			'blackfriday'   => '[blackfriday]',
			'cybermonday'   => '[cybermonday]',
		];

		// Handle countdown types with date parameter.
		if ( 'daysuntil' === $type && ! empty( $source_args['date'] ) ) {
			return do_shortcode( '[daysuntil date="' . esc_attr( $source_args['date'] ) . '"]' );
		}

		if ( 'dayssince' === $type && ! empty( $source_args['date'] ) ) {
			return do_shortcode( '[dayssince date="' . esc_attr( $source_args['date'] ) . '"]' );
		}

		// Handle year with offset.
		if ( 'year' === $type && ! empty( $source_args['offset'] ) ) {
			return do_shortcode( '[year n="' . (int) $source_args['offset'] . '"]' );
		}

		$shortcode = $shortcode_map[ $type ] ?? '[year]';

		return do_shortcode( $shortcode );
	}
}
