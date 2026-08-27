<?php
/**
 * Live Countdown block server-side render.
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

$dmyip_renderer    = new \DMYIP\Date\DateRenderer();
$dmyip_mode        = isset( $attributes['mode'] ) && 'since' === $attributes['mode'] ? 'since' : 'until';
$dmyip_target_date = isset( $attributes['targetDate'] ) ? sanitize_text_field( (string) $attributes['targetDate'] ) : '';
$dmyip_label       = isset( $attributes['label'] ) ? sanitize_text_field( (string) $attributes['label'] ) : 'days';
$dmyip_show_label  = ! isset( $attributes['showLabel'] ) || (bool) $attributes['showLabel'];
$dmyip_recurring   = ! empty( $attributes['recurring'] );

if ( $dmyip_recurring ) {
	$dmyip_days = $dmyip_renderer->render(
		'daysuntilnext',
		[ 'date' => $dmyip_target_date ]
	);
} else {
	$dmyip_days = $dmyip_renderer->render(
		'until' === $dmyip_mode ? 'daysuntil' : 'dayssince',
		[
			'date'  => $dmyip_target_date,
			'clamp' => 'true',
		]
	);
}

if ( '' === $dmyip_days ) {
	return;
}

$dmyip_day_count    = (int) $dmyip_days;
$dmyip_auto_label   = 'days' === strtolower( trim( $dmyip_label ) );
$dmyip_output_label = $dmyip_auto_label
	? _n( 'day', 'days', $dmyip_day_count, 'dynamic-month-year-into-posts' )
	: $dmyip_label;
$dmyip_display_text = $dmyip_show_label && '' !== $dmyip_output_label
	? $dmyip_days . ' ' . $dmyip_output_label
	: $dmyip_days;
$dmyip_context      = [
	'mode'          => $dmyip_mode,
	'targetDate'    => $dmyip_target_date,
	'label'         => $dmyip_label,
	'autoLabel'     => $dmyip_auto_label,
	'singularLabel' => __( 'day', 'dynamic-month-year-into-posts' ),
	'pluralLabel'   => __( 'days', 'dynamic-month-year-into-posts' ),
	'showLabel'     => $dmyip_show_label,
	'recurring'     => $dmyip_recurring,
	'timezone'      => wp_timezone()->getName(),
	'days'          => $dmyip_day_count,
	'displayText'   => $dmyip_display_text,
];

printf(
	'<span %s data-wp-interactive="dmyip/countdown" %s data-wp-init="callbacks.init" data-wp-text="context.displayText">%s</span>',
	wp_kses_data( get_block_wrapper_attributes() ),
	wp_kses_data( wp_interactivity_data_wp_context( $dmyip_context ) ),
	esc_html( $dmyip_display_text )
);
