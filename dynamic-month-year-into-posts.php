<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://gauravtiwari.org
 * @since             1.0.0
 * @package           Dynamic_Month_Year_Into_Posts
 *
 * @wordpress-plugin
 * Plugin Name:       Dynamic Month & Year into Posts
 * Plugin URI:        https://gauravtiwari.org/snippet/dynamic-month-year/
 * Description:       Insert dynamic year, month, dates, days, next and previous dates into content and meta using shortcodes. Use this plugin to boost your site’s SEO, automate your affiliate marketing, automatically updating blogging lists, offer dynamic coupon expiries and more, just by using these variables anywhere.
 * Version:           1.3.3
 * Author:            Gaurav Tiwari
 * Author URI:        https://gauravtiwari.org
 * License:           GPL-3.0+
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain:       dynamic-month-year-into-posts
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}
define( 'DYNAMIC_MONTH_YEAR_INTO_POSTS_VERSION', '1.3.3' );

// Registering shortcodes
add_shortcode( 'year' , 'dmyip_rmd_current_year' );
    function dmyip_rmd_current_year() {
    $year = date_i18n('Y');
    return esc_html($year);
}
add_shortcode( 'month' , 'dmyip_rmd_current_month' );
    function dmyip_rmd_current_month() {
    $month = date_i18n('F');
    return esc_html($month);
}
add_shortcode( 'cmonth' , 'dmyip_rmd_current_caps_month' );
    function dmyip_rmd_current_caps_month() {
    $ucmonth = date_i18n('F');
    $cmonth = ucfirst($ucmonth);
    return esc_html($cmonth);
}
add_shortcode( 'mon' , 'dmyip_rmd_current_mon' );
    function dmyip_rmd_current_mon() {
    $mon = date_i18n('M');
    return esc_html($mon);
}
add_shortcode( 'cmon' , 'dmyip_rmd_current_caps_mon' );
    function dmyip_rmd_current_caps_mon() {
    $ucmon = date_i18n('M');
    $cmon = ucfirst($ucmon);
    return esc_html($cmon);
}
add_shortcode( 'mm' , 'dmyip_rmd_current_mm' );
    function dmyip_rmd_current_mm() {
    $mm = date_i18n('m');
    return esc_html($mm);
}
add_shortcode( 'mn' , 'dmyip_rmd_current_mn' );
    function dmyip_rmd_current_mn() {
    $mn = date_i18n('n');
    return esc_html($mn);
}
add_shortcode( 'nmonth' , 'dmyip_rmd_next_month' );
    function dmyip_rmd_next_month() {
    // $nxtm = strtotime('next month');
    $nmonth = date_i18n('F', mktime(0, 0, 0, date('n') + 1, 1));
    return esc_html($nmonth);
}
add_shortcode( 'cnmonth' , 'dmyip_rmd_next_caps_month' );
    function dmyip_rmd_next_caps_month() {
    // $nxtm11 = strtotime('next month');
    $nmonth11 = date_i18n('F', mktime(0, 0, 0, date('n') + 1, 1));
    $cnmonth = ucfirst($nmonth11);
    return esc_html($cnmonth);
}
add_shortcode( 'pmonth' , 'dmyip_rmd_prev_month' );
    function dmyip_rmd_prev_month() {
    $pvsm = strtotime('previous month');
    $pmonth = date_i18n('F',$pvsm);
    return esc_html($pmonth);
}
add_shortcode( 'cpmonth' , 'dmyip_rmd_prev_caps_month' );
    function dmyip_rmd_prev_caps_month() {
    $pvsm11 = strtotime('previous month');
    $pmonth11 = date_i18n('F',$pvsm11);
    $cpmonth = ucfirst($pmonth11);
    return esc_html($cpmonth);
}
add_shortcode( 'nmon' , 'dmyip_rmd_next_month_short' );
    function dmyip_rmd_next_month_short() {
    // $nxtm1 = strtotime('next month');
    $nmon = date_i18n('M', mktime(0, 0, 0, date('n') + 1, 1));
    return esc_html($nmon);
}
add_shortcode( 'cnmon' , 'dmyip_rmd_next_month_short_caps' );
    function dmyip_rmd_next_month_short_caps() {
    // $nxtm13 = strtotime('next month');
    $nmon13 = date_i18n('M', mktime(0, 0, 0, date('n') + 1, 1));
    $cnmon = ucfirst($nmon13);
    return esc_html($cnmon);
}
add_shortcode( 'pmon' , 'dmyip_rmd_prev_month_short' );
    function dmyip_rmd_prev_month_short() {
    $pvsm1 = strtotime('previous month');
    $pmon = date_i18n('M',$pvsm1);
    return esc_html($pmon);
}
add_shortcode( 'cpmon' , 'dmyip_rmd_prev_month_short_caps' );
    function dmyip_rmd_prev_month_short_caps() {
    $pvsm13 = strtotime('previous month');
    $pmon13 = date_i18n('M',$pvsm13);
    $cpmon = ucfirst($pmon13);
    return esc_html($cpmon);
}
add_shortcode( 'date' , 'dmyip_rmd_current_date' );
    function dmyip_rmd_current_date() {
    $date = date_i18n('F j, Y');
    return esc_html($date);
}
add_shortcode( 'monthyear' , 'dmyip_rmd_monthyear' );
    function dmyip_rmd_monthyear() {
    $monthyear1 = date_i18n('F Y');
    $monthyear = ucfirst($monthyear1);
    return esc_html($monthyear);
}
add_shortcode( 'nyear' , 'dmyip_rmd_next_year' );
    function dmyip_rmd_next_year() {
    $currentyear1 = date_i18n('Y');
    $nyear = $currentyear1 + 1;
    return esc_html($nyear);
}
add_shortcode( 'nnyear' , 'dmyip_rmd_next_next_year' );
    function dmyip_rmd_next_next_year() {
    $currentyear11 = date_i18n('Y');
    $nnyear = $currentyear11 + 2;
    return esc_html($nnyear);
}
add_shortcode( 'pyear' , 'dmyip_rmd_previous_year' );
    function dmyip_rmd_previous_year() {
    $currentyear2 = date_i18n('Y');
    $pyear = $currentyear2 - 1;
    return esc_html($pyear);
}
add_shortcode( 'ppyear' , 'dmyip_rmd_previous_previous_year' );
    function dmyip_rmd_previous_previous_year() {
    $currentyear22 = date_i18n('Y');
    $ppyear = $currentyear22 - 2;
    return esc_html($ppyear);
}
add_shortcode( 'dt' , 'dmyip_rmd_current_dt' );
    function dmyip_rmd_current_dt() {
    $dt = date_i18n('j');
    return esc_html($dt);
}
add_shortcode( 'weekday' , 'dmyip_rmd_current_weekday' );
    function dmyip_rmd_current_weekday() {
    $weekday = date_i18n('l');
    return esc_html($weekday);
}
add_shortcode( 'wd' , 'dmyip_rmd_current_wd' );
    function dmyip_rmd_current_wd() {
    $wd = date_i18n('D');
    return esc_html($wd);
}
add_shortcode( 'blackfriday' , 'dmyip_rmd_blackfriday' );
    function dmyip_rmd_blackfriday() {
    $bfdate = date_i18n('F j', strtotime( '2023-11-24 00:00:00' ));
    return esc_html($bfdate);
}
add_shortcode( 'cybermonday' , 'dmyip_rmd_cybermonday' );
    function dmyip_rmd_cybermonday() {
    $cmdate = date_i18n('F j', strtotime( '2023-11-27 00:00:00' ));
    return esc_html($cmdate);
}
// Adding support to native WP elements

add_filter( 'the_title', 'do_shortcode' );
add_filter( 'single_post_title', 'do_shortcode' );
add_filter( 'wp_title', 'do_shortcode' );
add_filter('the_excerpt', 'do_shortcode');

// Rank Math Support
add_filter( 'rank_math/frontend/title', function( $title ) {
    return do_shortcode( $title );
});
add_filter( 'rank_math/frontend/description', function( $description ) {
    return do_shortcode( $description );
});
// add_filter( 'rank_math/paper/auto_generated_description/apply_shortcode', '__return_true' );
add_filter( 'rank_math/product_description/apply_shortcode', '__return_true' );
add_filter( 'rank_math/frontend/breadcrumb/html', 'do_shortcode' );
/* In Beta — Open Graph Testing for Rank Math */
// Add Shortcode support in Rankmath Schema
add_filter( 'rank_math/opengraph/facebook/og_title', 'do_shortcode' );
add_filter( 'rank_math/opengraph/facebook/og_description', 'do_shortcode' );
add_filter( 'rank_math/opengraph/twitter/title', 'do_shortcode' );
add_filter( 'rank_math/opengraph/twitter/description', 'do_shortcode' );
add_filter( 'rank_math/opengraph/facebook/og_title', function( $fbog ) {
    return do_shortcode( $fbog );
});
add_filter( 'rank_math/opengraph/facebook/og_description', function( $fbogdesc ) {
    return do_shortcode( $fbogdesc );
});
add_filter( 'rank_math/opengraph/twitter/title', function( $twtitle ) {
    return do_shortcode( $twtitle );
});
add_filter( 'rank_math/opengraph/twitter/description', function( $twdesc ) {
    return do_shortcode( $twdesc );
});
// Yoast SEO Support - not complete
add_filter( 'wpseo_title', 'do_shortcode' );
add_filter( 'wpseo_metadesc', 'do_shortcode' );
add_filter( 'wpseo_opengraph_title', 'do_shortcode' );
add_filter( 'wpseo_opengraph_desc', 'do_shortcode' );
// add_filter( 'wpseo_json_ld_output', 'do_shortcode' );
add_filter('wpseo_schema_webpage', function($data) {
    $data['name'] = do_shortcode($data['name']);
    return $data;
});

// SEOPress Support

add_filter( 'seopress_titles_title', 'do_shortcode'); // SEOPress Support
add_filter( 'seopress_titles_desc', 'do_shortcode'); // SEOPress Support

// Elementor Support - Should fix all rendering issues.
add_filter( 'elementor/widget/render_content', 'do_shortcode');

// Miscellaneous
add_filter( 'crp_title', 'do_shortcode'); // CRP Support
// add_filter( 'rank_math/snippet/breadcrumb', 'do_shortcode' ); @TODO

// Add Shortcodes
add_filter( 'plugin_action_links_dynamic-month-year-into-posts/dynamic-month-year-into-posts.php', 'dmyip_settings_link' );
function dmyip_settings_link( $links ) {
    // Create the link.
    $settings_link = '<a href="https://gauravtiwari.org/snippet/dynamic-month-year/#shortcodes">' . __( 'List of Shortcodes' ) . '</a>';
    // Adds the link to the end of the array.
    array_push(
        $links,
        $settings_link
    );
    return $links;
}