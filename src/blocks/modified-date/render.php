<?php
/**
 * Modified Date block server-side render.
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

$dmyip_renderer = new \DMYIP\Date\DateRenderer();
$dmyip_post_id  = isset( $block->context['postId'] ) ? (int) $block->context['postId'] : 0;
$dmyip_output   = $dmyip_renderer->render(
	'modified',
	[
		'format' => isset( $attributes['format'] ) ? sanitize_text_field( (string) $attributes['format'] ) : '',
	],
	$dmyip_post_id
);

if ( '' === $dmyip_output ) {
	return;
}

$dmyip_prefix  = isset( $attributes['prefix'] ) ? (string) $attributes['prefix'] : '';
$dmyip_suffix  = isset( $attributes['suffix'] ) ? (string) $attributes['suffix'] : '';
$dmyip_display = $dmyip_prefix . $dmyip_output . $dmyip_suffix;

printf(
	'<p %s>%s</p>',
	wp_kses_data( get_block_wrapper_attributes() ),
	esc_html( $dmyip_display )
);
