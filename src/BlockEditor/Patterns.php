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
		$this->register_age_display();
		$this->register_birthday_countdown();
		$this->register_experience_badge();
		$this->register_new_year_countdown();
		$this->register_days_since_milestone();
		$this->register_promo_with_cta();
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

	/**
	 * Age display pattern.
	 *
	 * @return void
	 */
	private function register_age_display(): void {
		register_block_pattern(
			'dmyip/age-display',
			[
				'title'       => __( 'Age Display Card', 'dynamic-month-year-into-posts' ),
				'description' => __( 'Display someone\'s age with automatic updates.', 'dynamic-month-year-into-posts' ),
				'categories'  => [ 'dmyip-dynamic-dates' ],
				'keywords'    => [ 'age', 'birthday', 'years' ],
				'content'     => '<!-- wp:group {"style":{"spacing":{"padding":{"top":"24px","bottom":"24px","left":"24px","right":"24px"}},"border":{"radius":"12px"}},"backgroundColor":"luminous-vivid-amber","textColor":"black"} -->
<div class="wp-block-group has-black-color has-luminous-vivid-amber-background-color has-text-color has-background" style="border-radius:12px;padding-top:24px;padding-right:24px;padding-bottom:24px;padding-left:24px">
<!-- wp:paragraph {"align":"center","fontSize":"small"} -->
<p class="has-text-align-center has-small-font-size">Current Age</p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"align":"center","style":{"typography":{"fontSize":"48px","fontStyle":"normal","fontWeight":"700"}}} -->
<p class="has-text-align-center" style="font-size:48px;font-style:normal;font-weight:700">[age date="1990-01-15"]</p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"align":"center","fontSize":"small"} -->
<p class="has-text-align-center has-small-font-size">years old</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->',
			]
		);
	}

	/**
	 * Birthday countdown pattern.
	 *
	 * @return void
	 */
	private function register_birthday_countdown(): void {
		register_block_pattern(
			'dmyip/birthday-countdown',
			[
				'title'       => __( 'Birthday Countdown', 'dynamic-month-year-into-posts' ),
				'description' => __( 'Countdown to the next birthday.', 'dynamic-month-year-into-posts' ),
				'categories'  => [ 'dmyip-dynamic-dates' ],
				'keywords'    => [ 'birthday', 'countdown', 'celebration' ],
				'content'     => '<!-- wp:group {"style":{"spacing":{"padding":{"top":"32px","bottom":"32px","left":"32px","right":"32px"}},"border":{"radius":"16px"}},"gradient":"luminous-vivid-amber-to-luminous-vivid-orange"} -->
<div class="wp-block-group has-luminous-vivid-amber-to-luminous-vivid-orange-gradient-background has-background" style="border-radius:16px;padding-top:32px;padding-right:32px;padding-bottom:32px;padding-left:32px">
<!-- wp:heading {"textAlign":"center","level":3,"textColor":"black"} -->
<h3 class="wp-block-heading has-text-align-center has-black-color has-text-color">🎂 Birthday Countdown</h3>
<!-- /wp:heading -->
<!-- wp:paragraph {"align":"center","style":{"typography":{"fontSize":"56px","fontStyle":"normal","fontWeight":"700"}},"textColor":"black"} -->
<p class="has-text-align-center has-black-color has-text-color" style="font-size:56px;font-style:normal;font-weight:700">[daysuntil date="2026-01-15"]</p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"align":"center","textColor":"black"} -->
<p class="has-text-align-center has-black-color has-text-color">days until the big day!</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->',
			]
		);
	}

	/**
	 * Experience badge pattern.
	 *
	 * @return void
	 */
	private function register_experience_badge(): void {
		register_block_pattern(
			'dmyip/experience-badge',
			[
				'title'       => __( 'Experience Badge', 'dynamic-month-year-into-posts' ),
				'description' => __( 'Display years of experience with auto-calculation.', 'dynamic-month-year-into-posts' ),
				'categories'  => [ 'dmyip-dynamic-dates' ],
				'keywords'    => [ 'experience', 'years', 'professional' ],
				'content'     => '<!-- wp:group {"style":{"spacing":{"padding":{"top":"20px","bottom":"20px","left":"32px","right":"32px"}},"border":{"radius":"50px"}},"backgroundColor":"vivid-cyan-blue","textColor":"white","layout":{"type":"flex","justifyContent":"center","flexWrap":"nowrap"}} -->
<div class="wp-block-group has-white-color has-vivid-cyan-blue-background-color has-text-color has-background" style="border-radius:50px;padding-top:20px;padding-right:32px;padding-bottom:20px;padding-left:32px">
<!-- wp:paragraph {"style":{"typography":{"fontStyle":"normal","fontWeight":"600"}}} -->
<p style="font-style:normal;font-weight:600">✨ [age date="2010-06-01"] Years of Experience</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->',
			]
		);
	}

	/**
	 * New Year countdown pattern.
	 *
	 * @return void
	 */
	private function register_new_year_countdown(): void {
		register_block_pattern(
			'dmyip/new-year-countdown',
			[
				'title'       => __( 'New Year Countdown', 'dynamic-month-year-into-posts' ),
				'description' => __( 'Countdown to the new year with festive styling.', 'dynamic-month-year-into-posts' ),
				'categories'  => [ 'dmyip-dynamic-dates' ],
				'keywords'    => [ 'new year', 'countdown', 'celebration' ],
				'content'     => '<!-- wp:group {"style":{"spacing":{"padding":{"top":"40px","bottom":"40px","left":"40px","right":"40px"}},"border":{"radius":"20px"}},"gradient":"vivid-cyan-blue-to-vivid-purple"} -->
<div class="wp-block-group has-vivid-cyan-blue-to-vivid-purple-gradient-background has-background" style="border-radius:20px;padding-top:40px;padding-right:40px;padding-bottom:40px;padding-left:40px">
<!-- wp:heading {"textAlign":"center","textColor":"white"} -->
<h2 class="wp-block-heading has-text-align-center has-white-color has-text-color">🎉 New Year [nyear]</h2>
<!-- /wp:heading -->
<!-- wp:paragraph {"align":"center","style":{"typography":{"fontSize":"72px","fontStyle":"normal","fontWeight":"800"}},"textColor":"white"} -->
<p class="has-text-align-center has-white-color has-text-color" style="font-size:72px;font-style:normal;font-weight:800">[daysuntil date="[nyear]-01-01"]</p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"align":"center","fontSize":"large","textColor":"white"} -->
<p class="has-text-align-center has-white-color has-text-color has-large-font-size">days to go!</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->',
			]
		);
	}

	/**
	 * Days since milestone pattern.
	 *
	 * @return void
	 */
	private function register_days_since_milestone(): void {
		register_block_pattern(
			'dmyip/days-since-milestone',
			[
				'title'       => __( 'Days Since Milestone', 'dynamic-month-year-into-posts' ),
				'description' => __( 'Track days since an important event.', 'dynamic-month-year-into-posts' ),
				'categories'  => [ 'dmyip-dynamic-dates' ],
				'keywords'    => [ 'milestone', 'tracking', 'days since' ],
				'content'     => '<!-- wp:group {"style":{"spacing":{"padding":{"top":"24px","bottom":"24px","left":"24px","right":"24px"}},"border":{"radius":"12px","color":"#10b981","width":"3px"}},"backgroundColor":"white"} -->
<div class="wp-block-group has-border-color has-white-background-color has-background" style="border-color:#10b981;border-width:3px;border-radius:12px;padding-top:24px;padding-right:24px;padding-bottom:24px;padding-left:24px">
<!-- wp:columns {"verticalAlignment":"center"} -->
<div class="wp-block-columns are-vertically-aligned-center">
<!-- wp:column {"verticalAlignment":"center","width":"30%"} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:30%">
<!-- wp:paragraph {"align":"center","style":{"typography":{"fontSize":"42px","fontStyle":"normal","fontWeight":"700"},"color":{"text":"#10b981"}}} -->
<p class="has-text-align-center" style="color:#10b981;font-size:42px;font-style:normal;font-weight:700">[dayssince date="2024-01-01"]</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:column -->
<!-- wp:column {"verticalAlignment":"center","width":"70%"} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:70%">
<!-- wp:paragraph {"style":{"typography":{"fontStyle":"normal","fontWeight":"600"}},"fontSize":"medium"} -->
<p class="has-medium-font-size" style="font-style:normal;font-weight:600">Days Since Launch</p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"fontSize":"small","style":{"color":{"text":"#6b7280"}}} -->
<p class="has-small-font-size" style="color:#6b7280">Tracking our journey since day one</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->
</div>
<!-- /wp:group -->',
			]
		);
	}

	/**
	 * Promotional banner with CTA button pattern.
	 *
	 * @return void
	 */
	private function register_promo_with_cta(): void {
		register_block_pattern(
			'dmyip/promo-with-cta',
			[
				'title'       => __( 'Promotional Banner with CTA', 'dynamic-month-year-into-posts' ),
				'description' => __( 'Eye-catching promo banner with button.', 'dynamic-month-year-into-posts' ),
				'categories'  => [ 'dmyip-dynamic-dates' ],
				'keywords'    => [ 'promotion', 'cta', 'button', 'sale' ],
				'content'     => '<!-- wp:group {"style":{"spacing":{"padding":{"top":"40px","bottom":"40px","left":"40px","right":"40px"}},"border":{"radius":"16px"}},"gradient":"blush-light-purple"} -->
<div class="wp-block-group has-blush-light-purple-gradient-background has-background" style="border-radius:16px;padding-top:40px;padding-right:40px;padding-bottom:40px;padding-left:40px">
<!-- wp:heading {"textAlign":"center","level":2} -->
<h2 class="wp-block-heading has-text-align-center">[month] Special Offer</h2>
<!-- /wp:heading -->
<!-- wp:paragraph {"align":"center","fontSize":"large"} -->
<p class="has-text-align-center has-large-font-size">Get 30% off all products this [month] [year]!</p>
<!-- /wp:paragraph -->
<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons">
<!-- wp:button {"backgroundColor":"vivid-purple","textColor":"white","style":{"border":{"radius":"8px"},"typography":{"fontStyle":"normal","fontWeight":"600"}}} -->
<div class="wp-block-button" style="font-style:normal;font-weight:600"><a class="wp-block-button__link has-white-color has-vivid-purple-background-color has-text-color has-background wp-element-button" style="border-radius:8px">Claim Your Discount</a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->
<!-- wp:paragraph {"align":"center","fontSize":"small","style":{"color":{"text":"#666666"}}} -->
<p class="has-text-align-center has-small-font-size" style="color:#666666">Offer valid until the end of [month]</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->',
			]
		);
	}
}
