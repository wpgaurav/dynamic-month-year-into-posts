<?php
/**
 * Live Countdown block server-side render with Interactivity API.
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

// Wrap in IIFE to avoid global variable pollution.
call_user_func(
	static function ( $attributes ) {
		$dmyip_mode        = $attributes['mode'] ?? 'until';
		$dmyip_target_date = $attributes['targetDate'] ?? '';
		$dmyip_label       = $attributes['label'] ?? 'days';
		$dmyip_show_label  = $attributes['showLabel'] ?? true;

		// Calculate initial days.
		$dmyip_days = 0;
		if ( ! empty( $dmyip_target_date ) ) {
			$dmyip_target = strtotime( $dmyip_target_date );
			$dmyip_today  = strtotime( 'today' );

			if ( $dmyip_target && $dmyip_today ) {
				$dmyip_diff_seconds = 'until' === $dmyip_mode ? $dmyip_target - $dmyip_today : $dmyip_today - $dmyip_target;
				$dmyip_days         = max( 0, (int) floor( $dmyip_diff_seconds / DAY_IN_SECONDS ) );
			}
		}

		// Prepare display text.
		$dmyip_display_text = $dmyip_show_label ? $dmyip_days . ' ' . esc_html( $dmyip_label ) : (string) $dmyip_days;

		// Set up interactivity context.
		$dmyip_context = [
			'mode'       => $dmyip_mode,
			'targetDate' => $dmyip_target_date,
			'label'      => $dmyip_label,
			'showLabel'  => $dmyip_show_label,
			'days'       => $dmyip_days,
		];

		// Initialize the state for server-side rendering.
		wp_interactivity_state(
			'dmyip/countdown',
			[
				'displayText' => $dmyip_display_text,
			]
		);

		printf(
			'<span %s data-wp-interactive="dmyip/countdown" %s data-wp-init="callbacks.init" data-wp-text="state.displayText">%s</span>',
			wp_kses_post( get_block_wrapper_attributes() ),
			wp_kses_data( wp_interactivity_data_wp_context( $dmyip_context ) ),
			esc_html( $dmyip_display_text )
		);
	},
	$attributes
);
