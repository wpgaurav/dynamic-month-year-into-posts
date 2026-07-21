<?php
/**
 * Block patterns.
 *
 * @package DMYIP
 */

declare(strict_types=1);

namespace DMYIP\BlockEditor;

use DMYIP\Date\DateEngine;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register practical, theme-neutral dynamic-date patterns.
 */
class Patterns {

	/**
	 * Register hooks.
	 *
	 * @return void
	 */
	public function register(): void {
		add_action( 'init', [ $this, 'register_category' ] );
		add_action( 'init', [ $this, 'register_patterns' ] );
	}

	/**
	 * Register pattern category.
	 *
	 * @return void
	 */
	public function register_category(): void {
		register_block_pattern_category(
			'dmyip-dynamic-dates',
			[
				'label'       => __( 'Dynamic Dates', 'dynamic-month-year-into-posts' ),
				'description' => __( 'Theme-neutral patterns with dates that update automatically.', 'dynamic-month-year-into-posts' ),
			]
		);
	}

	/**
	 * Register patterns.
	 *
	 * @return void
	 */
	public function register_patterns(): void {
		$engine       = new DateEngine();
		$thirty_days  = $engine->today()->modify( '+30 days' )->format( 'Y-m-d' );
		$pattern_args = [
			'categories' => [ 'dmyip-dynamic-dates' ],
		];

		register_block_pattern(
			'dmyip/copyright-footer',
			$pattern_args + [
				'title'       => __( 'Copyright Footer', 'dynamic-month-year-into-posts' ),
				'description' => __( 'A centered copyright line with the current year.', 'dynamic-month-year-into-posts' ),
				'keywords'    => [ 'copyright', 'footer', 'year' ],
				'content'     => '<!-- wp:paragraph {"align":"center","fontSize":"small"} -->
<p class="has-text-align-center has-small-font-size">&copy; [year] Your Company Name. All rights reserved.</p>
<!-- /wp:paragraph -->',
			]
		);

		register_block_pattern(
			'dmyip/last-updated',
			$pattern_args + [
				'title'       => __( 'Last Updated', 'dynamic-month-year-into-posts' ),
				'description' => __( 'A native Modified Date block with an editable prefix.', 'dynamic-month-year-into-posts' ),
				'keywords'    => [ 'updated', 'modified', 'date' ],
				'content'     => '<!-- wp:dmyip/modified-date {"prefix":"Last updated: "} /-->',
			]
		);

		register_block_pattern(
			'dmyip/current-period-heading',
			$pattern_args + [
				'title'       => __( 'Current Month Heading', 'dynamic-month-year-into-posts' ),
				'description' => __( 'A heading that always displays the current month and year.', 'dynamic-month-year-into-posts' ),
				'keywords'    => [ 'month', 'year', 'heading' ],
				'content'     => '<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">What to know in [month] [year]</h2>
<!-- /wp:heading -->',
			]
		);

		register_block_pattern(
			'dmyip/event-dates',
			$pattern_args + [
				'title'       => __( 'Black Friday and Cyber Monday Dates', 'dynamic-month-year-into-posts' ),
				'description' => __( 'A compact list of the current year’s Black Friday and Cyber Monday dates.', 'dynamic-month-year-into-posts' ),
				'keywords'    => [ 'black friday', 'cyber monday', 'events' ],
				'content'     => '<!-- wp:list -->
<ul class="wp-block-list"><!-- wp:list-item -->
<li><strong>Black Friday:</strong> [blackfriday]</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><strong>Cyber Monday:</strong> [cybermonday]</li>
<!-- /wp:list-item --></ul>
<!-- /wp:list -->',
			]
		);

		register_block_pattern(
			'dmyip/countdown',
			$pattern_args + [
				'title'       => __( '30-Day Countdown', 'dynamic-month-year-into-posts' ),
				'description' => __( 'A Live Countdown block set 30 days from insertion.', 'dynamic-month-year-into-posts' ),
				'keywords'    => [ 'countdown', 'days', 'deadline' ],
				'content'     => sprintf(
					'<!-- wp:group {"style":{"border":{"width":"1px"},"spacing":{"padding":{"top":"var:preset|spacing|30","right":"var:preset|spacing|30","bottom":"var:preset|spacing|30","left":"var:preset|spacing|30"}}}} -->
<div class="wp-block-group" style="border-width:1px;padding-top:var(--wp--preset--spacing--30);padding-right:var(--wp--preset--spacing--30);padding-bottom:var(--wp--preset--spacing--30);padding-left:var(--wp--preset--spacing--30)"><!-- wp:paragraph -->
<p><strong>Time remaining</strong></p>
<!-- /wp:paragraph -->

<!-- wp:dmyip/countdown {"targetDate":"%s"} /--></div>
<!-- /wp:group -->',
					esc_attr( $thirty_days )
				),
			]
		);

		register_block_pattern(
			'dmyip/birthday-countdown',
			$pattern_args + [
				'title'       => __( 'Recurring Birthday Countdown', 'dynamic-month-year-into-posts' ),
				'description' => __( 'A yearly countdown. Change the example date to the birthday.', 'dynamic-month-year-into-posts' ),
				'keywords'    => [ 'birthday', 'annual', 'countdown' ],
				'content'     => '<!-- wp:group {"style":{"border":{"width":"1px"},"spacing":{"padding":{"top":"var:preset|spacing|30","right":"var:preset|spacing|30","bottom":"var:preset|spacing|30","left":"var:preset|spacing|30"}}}} -->
<div class="wp-block-group" style="border-width:1px;padding-top:var(--wp--preset--spacing--30);padding-right:var(--wp--preset--spacing--30);padding-bottom:var(--wp--preset--spacing--30);padding-left:var(--wp--preset--spacing--30)"><!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Birthday countdown</h3>
<!-- /wp:heading -->

<!-- wp:dmyip/countdown {"targetDate":"2000-01-15","recurring":true} /--></div>
<!-- /wp:group -->',
			]
		);

		register_block_pattern(
			'dmyip/new-year-countdown',
			$pattern_args + [
				'title'       => __( 'Recurring New Year Countdown', 'dynamic-month-year-into-posts' ),
				'description' => __( 'A cache-aware countdown that rolls over every January 1.', 'dynamic-month-year-into-posts' ),
				'keywords'    => [ 'new year', 'annual', 'countdown' ],
				'content'     => '<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">New Year countdown</h3>
<!-- /wp:heading -->

<!-- wp:dmyip/countdown {"targetDate":"2000-01-01","recurring":true} /-->',
			]
		);
	}
}
