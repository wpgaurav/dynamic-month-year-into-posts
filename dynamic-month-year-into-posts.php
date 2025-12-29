<?php
/**
 * Plugin Name:       Dynamic Month & Year into Posts
 * Plugin URI:        https://gauravtiwari.org/snippet/dynamic-month-year/
 * Description:       Insert dynamic year, month, dates, days, next and previous dates into content and meta using shortcodes.
 * Version:           1.5.4
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

define( 'DYNAMIC_MONTH_YEAR_INTO_POSTS_VERSION', '1.5.4' );

// Load plugin components.
require_once plugin_dir_path( __FILE__ ) . 'includes/shortcodes.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/core-filters.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/rank-math-support.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/seopress-support.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/yoast-seo-support.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/others.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/block-editor.php';