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
 * Description:       Insert Dynamic Year and Month into content and meta using shortcodes.
 * Version:           1.0.8
 * Author:            Gaurav Tiwari
 * Author URI:        https://gauravtiwari.org
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       dynamic-month-year-into-posts
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'DYNAMIC_MONTH_YEAR_INTO_POSTS_VERSION', '1.0.8' );
add_shortcode( 'year' , 'rmd_current_year' );
    function rmd_current_year() {
    $year = date_i18n("Y");
    return "$year";
}
add_shortcode( 'month' , 'rmd_current_month' );
    function rmd_current_month() {
    $month = date_i18n("F");
    return "$month";
}
add_shortcode( 'date' , 'rmd_current_date' );
    function rmd_current_date() {
    $date = date_i18n("d F Y");
    return "$date";
}
add_shortcode( 'nyear' , 'rmd_next_year' );
    function rmd_next_year() {
    $currentyear1 = date_i18n("Y");
    $nyear = $currentyear1 + 1;
    return "$nyear";
}
add_shortcode( 'pyear' , 'rmd_previous_year' );
    function rmd_previous_year() {
    $currentyear2 = date_i18n("Y");
    $pyear = $currentyear2 - 1;
    return "$pyear";
}
add_filter( 'the_title', 'do_shortcode' );
add_filter( 'single_post_title', 'do_shortcode' );
add_filter('the_excerpt', 'do_shortcode');
add_filter( 'rank_math/frontend/title', function( $title ) {
    return do_shortcode( $title );
});
add_filter( 'rank_math/paper/auto_generated_description/apply_shortcode', '__return_true' );
add_filter( 'rank_math/frontend/breadcrumb/html', 'do_shortcode' );
add_filter( 'wpseo_title', 'do_shortcode' ); // Yoast SEO Title Support Beta
add_filter( 'wpseo_metadesc', 'do_shortcode' ); // Yoast Meta Description Support
add_filter( 'seopress_titles_title', 'do_shortcode'); // SEOPress Support
add_filter( 'seopress_titles_desc', 'do_shortcode'); // SEOPress Support
add_filter( 'crp_title', 'do_shortcode'); // CRP Support
// add_filter( 'rank_math/snippet/breadcrumb', 'do_shortcode' ); @TODO