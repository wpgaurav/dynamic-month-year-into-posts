<?php
/**
 * Block patterns.
 *
 * @package DMYIP
 */

declare(strict_types=1);

namespace DMYIP\BlockEditor;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Block pattern registration.
 */
class Patterns {

	/**
	 * Register patterns.
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
				'description' => __( 'Patterns with dynamic date shortcodes', 'dynamic-month-year-into-posts' ),
			]
		);
	}

	/**
	 * Register all patterns.
	 *
	 * @return void
	 */
	public function register_patterns(): void {
		$this->register_copyright_footer();
		$this->register_last_updated();
		$this->register_affiliate_header();
		$this->register_sale_banner();
		$this->register_black_friday_banner();
		$this->register_countdown();
		$this->register_todays_date();
	}

	/**
	 * Copyright footer pattern.
	 *
	 * @return void
	 */
	private function register_copyright_footer(): void {
		register_block_pattern(
			'dmyip/copyright-footer',
			[
				'title'       => __( 'Copyright Footer', 'dynamic-month-year-into-posts' ),
				'description' => __( 'A copyright notice with auto-updating year.', 'dynamic-month-year-into-posts' ),
				'categories'  => [ 'dmyip-dynamic-dates' ],
				'keywords'    => [ 'copyright', 'footer', 'year' ],
				'content'     => '<!-- wp:paragraph {"align":"center","fontSize":"small"} -->
<p class="has-text-align-center has-small-font-size">&copy; [year] Your Company Name. All rights reserved.</p>
<!-- /wp:paragraph -->',
			]
		);
	}

	/**
	 * Last updated notice pattern.
	 *
	 * @return void
	 */
	private function register_last_updated(): void {
		register_block_pattern(
			'dmyip/last-updated',
			[
				'title'       => __( 'Last Updated Notice', 'dynamic-month-year-into-posts' ),
				'description' => __( 'Shows when the post was last updated.', 'dynamic-month-year-into-posts' ),
				'categories'  => [ 'dmyip-dynamic-dates' ],
				'keywords'    => [ 'updated', 'modified', 'date' ],
				'content'     => '<!-- wp:paragraph {"fontSize":"small","style":{"color":{"text":"#666666"}}} -->
<p class="has-small-font-size" style="color:#666666"><strong>Last updated:</strong> [datemodified]</p>
<!-- /wp:paragraph -->',
			]
		);
	}

	/**
	 * Affiliate post header pattern.
	 *
	 * @return void
	 */
	private function register_affiliate_header(): void {
		register_block_pattern(
			'dmyip/affiliate-header',
			[
				'title'       => __( 'Affiliate Post Header', 'dynamic-month-year-into-posts' ),
				'description' => __( 'Header for affiliate/review posts with dynamic date.', 'dynamic-month-year-into-posts' ),
				'categories'  => [ 'dmyip-dynamic-dates' ],
				'keywords'    => [ 'affiliate', 'review', 'best' ],
				'content'     => '<!-- wp:group {"style":{"spacing":{"padding":{"top":"20px","bottom":"20px","left":"20px","right":"20px"}},"border":{"radius":"8px"}},"backgroundColor":"pale-cyan-blue"} -->
<div class="wp-block-group has-pale-cyan-blue-background-color has-background" style="border-radius:8px;padding-top:20px;padding-right:20px;padding-bottom:20px;padding-left:20px">
<!-- wp:paragraph {"align":"center","fontSize":"medium"} -->
<p class="has-text-align-center has-medium-font-size"><strong>Updated for [month] [year]</strong> - Our team reviewed and tested all products</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->',
			]
		);
	}

	/**
	 * Monthly sale banner pattern.
	 *
	 * @return void
	 */
	private function register_sale_banner(): void {
		register_block_pattern(
			'dmyip/sale-banner',
			[
				'title'       => __( 'Monthly Sale Banner', 'dynamic-month-year-into-posts' ),
				'description' => __( 'A banner for monthly promotions.', 'dynamic-month-year-into-posts' ),
				'categories'  => [ 'dmyip-dynamic-dates' ],
				'keywords'    => [ 'sale', 'promotion', 'banner' ],
				'content'     => '<!-- wp:group {"style":{"spacing":{"padding":{"top":"30px","bottom":"30px","left":"30px","right":"30px"}},"border":{"radius":"12px"}},"backgroundColor":"vivid-red","textColor":"white"} -->
<div class="wp-block-group has-white-color has-vivid-red-background-color has-text-color has-background" style="border-radius:12px;padding-top:30px;padding-right:30px;padding-bottom:30px;padding-left:30px">
<!-- wp:heading {"textAlign":"center","level":3} -->
<h3 class="wp-block-heading has-text-align-center">[month] Sale - Limited Time!</h3>
<!-- /wp:heading -->
<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">Don\'t miss our exclusive [month] [year] deals. Offer ends soon!</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->',
			]
		);
	}

	/**
	 * Black Friday banner pattern.
	 *
	 * @return void
	 */
	private function register_black_friday_banner(): void {
		register_block_pattern(
			'dmyip/black-friday-banner',
			[
				'title'       => __( 'Black Friday Banner', 'dynamic-month-year-into-posts' ),
				'description' => __( 'Banner with auto-calculated Black Friday date.', 'dynamic-month-year-into-posts' ),
				'categories'  => [ 'dmyip-dynamic-dates' ],
				'keywords'    => [ 'black friday', 'cyber monday', 'sale' ],
				'content'     => '<!-- wp:group {"style":{"spacing":{"padding":{"top":"40px","bottom":"40px","left":"40px","right":"40px"}},"border":{"radius":"16px"}},"backgroundColor":"black","textColor":"white"} -->
<div class="wp-block-group has-white-color has-black-background-color has-text-color has-background" style="border-radius:16px;padding-top:40px;padding-right:40px;padding-bottom:40px;padding-left:40px">
<!-- wp:heading {"textAlign":"center","level":2} -->
<h2 class="wp-block-heading has-text-align-center">Black Friday [year]</h2>
<!-- /wp:heading -->
<!-- wp:paragraph {"align":"center","fontSize":"large"} -->
<p class="has-text-align-center has-large-font-size">Starts [blackfriday] - Don\'t miss out!</p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"align":"center","fontSize":"small"} -->
<p class="has-text-align-center has-small-font-size">Cyber Monday deals begin [cybermonday]</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->',
			]
		);
	}

	/**
	 * Countdown pattern.
	 *
	 * @return void
	 */
	private function register_countdown(): void {
		register_block_pattern(
			'dmyip/countdown',
			[
				'title'       => __( 'Days Until Countdown', 'dynamic-month-year-into-posts' ),
				'description' => __( 'Countdown to a specific date.', 'dynamic-month-year-into-posts' ),
				'categories'  => [ 'dmyip-dynamic-dates' ],
				'keywords'    => [ 'countdown', 'days', 'until' ],
				'content'     => '<!-- wp:group {"style":{"spacing":{"padding":{"top":"20px","bottom":"20px","left":"20px","right":"20px"}},"border":{"radius":"8px","width":"2px"}},"borderColor":"primary"} -->
<div class="wp-block-group has-border-color has-primary-border-color" style="border-width:2px;border-radius:8px;padding-top:20px;padding-right:20px;padding-bottom:20px;padding-left:20px">
<!-- wp:paragraph {"align":"center","fontSize":"large"} -->
<p class="has-text-align-center has-large-font-size"><strong>[daysuntil date="2025-12-31"]</strong> days left until the end of the year!</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->',
			]
		);
	}

	/**
	 * Today's date header pattern.
	 *
	 * @return void
	 */
	private function register_todays_date(): void {
		register_block_pattern(
			'dmyip/todays-date',
			[
				'title'       => __( 'Today\'s Date Header', 'dynamic-month-year-into-posts' ),
				'description' => __( 'Display today\'s date prominently.', 'dynamic-month-year-into-posts' ),
				'categories'  => [ 'dmyip-dynamic-dates' ],
				'keywords'    => [ 'today', 'date', 'current' ],
				'content'     => '<!-- wp:paragraph {"align":"right","fontSize":"small","style":{"color":{"text":"#888888"}}} -->
<p class="has-text-align-right has-small-font-size" style="color:#888888">[weekday], [date]</p>
<!-- /wp:paragraph -->',
			]
		);
	}
}
