<?php
/**
 * Block Editor Integration
 *
 * Registers editor assets, block patterns, and block bindings.
 *
 * @package Dynamic_Month_Year_Into_Posts
 */

// Prevent direct access.
if ( ! defined( 'WPINC' ) ) {
    die;
}

/**
 * Enqueue block editor assets.
 */
function dmyip_enqueue_block_editor_assets() {
    $plugin_url = plugin_dir_url( dirname( __FILE__ ) );
    $plugin_dir = plugin_dir_path( dirname( __FILE__ ) );

    // Editor JavaScript.
    wp_enqueue_script(
        'dmyip-editor',
        $plugin_url . 'assets/js/editor.js',
        array(
            'wp-blocks',
            'wp-element',
            'wp-editor',
            'wp-components',
            'wp-plugins',
            'wp-edit-post',
            'wp-rich-text',
            'wp-block-editor',
            'wp-i18n',
            'wp-data',
        ),
        DYNAMIC_MONTH_YEAR_INTO_POSTS_VERSION,
        true
    );

    // Editor CSS.
    wp_enqueue_style(
        'dmyip-editor',
        $plugin_url . 'assets/css/editor.css',
        array(),
        DYNAMIC_MONTH_YEAR_INTO_POSTS_VERSION
    );

    // Pass data to JavaScript.
    wp_localize_script(
        'dmyip-editor',
        'dmyipData',
        array(
            'currentYear'  => date_i18n( 'Y' ),
            'currentMonth' => date_i18n( 'F' ),
            'currentDate'  => date_i18n( 'F j, Y' ),
        )
    );
}
add_action( 'enqueue_block_editor_assets', 'dmyip_enqueue_block_editor_assets' );

/**
 * Register block pattern category.
 */
function dmyip_register_block_pattern_category() {
    register_block_pattern_category(
        'dmyip-dynamic-dates',
        array(
            'label'       => __( 'Dynamic Dates', 'dynamic-month-year-into-posts' ),
            'description' => __( 'Patterns with dynamic date shortcodes', 'dynamic-month-year-into-posts' ),
        )
    );
}
add_action( 'init', 'dmyip_register_block_pattern_category' );

/**
 * Register block patterns.
 */
function dmyip_register_block_patterns() {
    // Copyright Footer Pattern.
    register_block_pattern(
        'dmyip/copyright-footer',
        array(
            'title'       => __( 'Copyright Footer', 'dynamic-month-year-into-posts' ),
            'description' => __( 'A copyright notice with auto-updating year.', 'dynamic-month-year-into-posts' ),
            'categories'  => array( 'dmyip-dynamic-dates' ),
            'keywords'    => array( 'copyright', 'footer', 'year' ),
            'content'     => '<!-- wp:paragraph {"align":"center","fontSize":"small"} -->
<p class="has-text-align-center has-small-font-size">&copy; [year] Your Company Name. All rights reserved.</p>
<!-- /wp:paragraph -->',
        )
    );

    // Last Updated Header Pattern.
    register_block_pattern(
        'dmyip/last-updated',
        array(
            'title'       => __( 'Last Updated Notice', 'dynamic-month-year-into-posts' ),
            'description' => __( 'Shows when the post was last updated.', 'dynamic-month-year-into-posts' ),
            'categories'  => array( 'dmyip-dynamic-dates' ),
            'keywords'    => array( 'updated', 'modified', 'date' ),
            'content'     => '<!-- wp:paragraph {"fontSize":"small","style":{"color":{"text":"#666666"}}} -->
<p class="has-small-font-size" style="color:#666666"><strong>Last updated:</strong> [datemodified]</p>
<!-- /wp:paragraph -->',
        )
    );

    // Affiliate Post Header Pattern.
    register_block_pattern(
        'dmyip/affiliate-header',
        array(
            'title'       => __( 'Affiliate Post Header', 'dynamic-month-year-into-posts' ),
            'description' => __( 'Header for affiliate/review posts with dynamic date.', 'dynamic-month-year-into-posts' ),
            'categories'  => array( 'dmyip-dynamic-dates' ),
            'keywords'    => array( 'affiliate', 'review', 'best' ),
            'content'     => '<!-- wp:group {"style":{"spacing":{"padding":{"top":"20px","bottom":"20px","left":"20px","right":"20px"}},"border":{"radius":"8px"}},"backgroundColor":"pale-cyan-blue"} -->
<div class="wp-block-group has-pale-cyan-blue-background-color has-background" style="border-radius:8px;padding-top:20px;padding-right:20px;padding-bottom:20px;padding-left:20px">
<!-- wp:paragraph {"align":"center","fontSize":"medium"} -->
<p class="has-text-align-center has-medium-font-size"><strong>Updated for [month] [year]</strong> - Our team reviewed and tested all products</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->',
        )
    );

    // Sale Banner Pattern.
    register_block_pattern(
        'dmyip/sale-banner',
        array(
            'title'       => __( 'Monthly Sale Banner', 'dynamic-month-year-into-posts' ),
            'description' => __( 'A banner for monthly promotions.', 'dynamic-month-year-into-posts' ),
            'categories'  => array( 'dmyip-dynamic-dates' ),
            'keywords'    => array( 'sale', 'promotion', 'banner' ),
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
        )
    );

    // Black Friday Banner Pattern.
    register_block_pattern(
        'dmyip/black-friday-banner',
        array(
            'title'       => __( 'Black Friday Banner', 'dynamic-month-year-into-posts' ),
            'description' => __( 'Banner with auto-calculated Black Friday date.', 'dynamic-month-year-into-posts' ),
            'categories'  => array( 'dmyip-dynamic-dates' ),
            'keywords'    => array( 'black friday', 'cyber monday', 'sale' ),
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
        )
    );

    // Countdown Pattern.
    register_block_pattern(
        'dmyip/countdown',
        array(
            'title'       => __( 'Days Until Countdown', 'dynamic-month-year-into-posts' ),
            'description' => __( 'Countdown to a specific date.', 'dynamic-month-year-into-posts' ),
            'categories'  => array( 'dmyip-dynamic-dates' ),
            'keywords'    => array( 'countdown', 'days', 'until' ),
            'content'     => '<!-- wp:group {"style":{"spacing":{"padding":{"top":"20px","bottom":"20px","left":"20px","right":"20px"}},"border":{"radius":"8px","width":"2px"}},"borderColor":"primary"} -->
<div class="wp-block-group has-border-color has-primary-border-color" style="border-width:2px;border-radius:8px;padding-top:20px;padding-right:20px;padding-bottom:20px;padding-left:20px">
<!-- wp:paragraph {"align":"center","fontSize":"large"} -->
<p class="has-text-align-center has-large-font-size"><strong>[daysuntil date="2025-12-31"]</strong> days left until the end of the year!</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->',
        )
    );

    // Today's Date Header Pattern.
    register_block_pattern(
        'dmyip/todays-date',
        array(
            'title'       => __( 'Today\'s Date Header', 'dynamic-month-year-into-posts' ),
            'description' => __( 'Display today\'s date prominently.', 'dynamic-month-year-into-posts' ),
            'categories'  => array( 'dmyip-dynamic-dates' ),
            'keywords'    => array( 'today', 'date', 'current' ),
            'content'     => '<!-- wp:paragraph {"align":"right","fontSize":"small","style":{"color":{"text":"#888888"}}} -->
<p class="has-text-align-right has-small-font-size" style="color:#888888">[weekday], [date]</p>
<!-- /wp:paragraph -->',
        )
    );
}
add_action( 'init', 'dmyip_register_block_patterns' );
