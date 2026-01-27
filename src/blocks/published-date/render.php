<?php
/**
 * Published Date block server-side render.
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Block default content.
 * @var WP_Block $block      Block instance.
 *
 * @package DMYIP
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Wrap in closure to avoid global variable pollution.
$dmyip_render_published_date = static function ( $attributes, $block ) {
	$dmyip_format = $attributes['format'] ?? '';
	$dmyip_prefix = $attributes['prefix'] ?? '';
	$dmyip_suffix = $attributes['suffix'] ?? '';

	// Get post ID from context or current post.
	$dmyip_post_id = $block->context['postId'] ?? get_the_ID();

	if ( ! $dmyip_post_id ) {
		return '';
	}

	// Get the published date.
	$dmyip_timestamp = get_the_time( 'U', $dmyip_post_id );

	if ( ! $dmyip_timestamp ) {
		return '';
	}

	// Use custom format or WordPress default.
	$dmyip_date_format = ! empty( $dmyip_format ) ? $dmyip_format : get_option( 'date_format' );
	$dmyip_output      = date_i18n( $dmyip_date_format, (int) $dmyip_timestamp );

	// Add prefix and suffix.
	return esc_html( $dmyip_prefix ) . esc_html( $dmyip_output ) . esc_html( $dmyip_suffix );
};

$dmyip_display = $dmyip_render_published_date( $attributes, $block );

if ( empty( $dmyip_display ) ) {
	return;
}

// Render the block.
printf(
	'<p %s>%s</p>',
	wp_kses_post( get_block_wrapper_attributes() ),
	wp_kses_post( $dmyip_display )
);
