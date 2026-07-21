<?php
/**
 * Dynamic Date block server-side render.
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
$dmyip_type     = isset( $attributes['type'] ) ? sanitize_key( (string) $attributes['type'] ) : 'year';
$dmyip_args     = [
	'date'         => isset( $attributes['date'] ) ? sanitize_text_field( (string) $attributes['date'] ) : '',
	'rule'         => isset( $attributes['rule'] ) ? sanitize_text_field( (string) $attributes['rule'] ) : '',
	'format'       => isset( $attributes['format'] ) ? sanitize_text_field( (string) $attributes['format'] ) : '',
	'offset'       => isset( $attributes['offset'] ) ? (int) $attributes['offset'] : 0,
	'rollover_day' => isset( $attributes['rolloverDay'] ) ? (int) $attributes['rolloverDay'] : 0,
	'case'         => isset( $attributes['case'] ) ? sanitize_key( (string) $attributes['case'] ) : 'none',
	'ordinal'      => 'age_ordinal' === $dmyip_type ? 'true' : 'false',
	'clamp'        => in_array( $dmyip_type, [ 'daysuntil', 'dayssince' ], true ) ? 'true' : 'false',
];
$dmyip_post_id  = isset( $block->context['postId'] ) ? (int) $block->context['postId'] : 0;
$dmyip_output   = $dmyip_renderer->render( $dmyip_type, $dmyip_args, $dmyip_post_id );

printf(
	'<p %s>%s</p>',
	wp_kses_data( get_block_wrapper_attributes() ),
	esc_html( $dmyip_output )
);
