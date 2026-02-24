<?php
/**
 * Plugin Name:       Dynamic Month & Year into Posts
 * Plugin URI:        https://gauravtiwari.org/snippet/dynamic-month-year/
 * Description:       Insert dynamic year, month, dates, days, next and previous dates into content and meta using shortcodes and blocks.
 * Version:           1.7.2
 * Author:            Gaurav Tiwari
 * Author URI:        https://gauravtiwari.org
 * License:           GPL-3.0+
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain:       dynamic-month-year-into-posts
 * Requires at least: 6.0
 * Requires PHP:      7.4
 *
 * @package DMYIP
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'DYNAMIC_MONTH_YEAR_INTO_POSTS_VERSION', '1.7.2' );
define( 'DMYIP_PLUGIN_FILE', __FILE__ );
define( 'DMYIP_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'DMYIP_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

/**
 * Load the plugin.
 *
 * Uses the new namespaced architecture if Composer autoloader exists,
 * otherwise falls back to legacy includes for backward compatibility.
 */
function dmyip_load_plugin() {
	$autoloader = DMYIP_PLUGIN_DIR . 'vendor/autoload.php';

	if ( file_exists( $autoloader ) ) {
		require_once $autoloader;
		\DMYIP\Plugin::instance( DMYIP_PLUGIN_FILE )->init();
	} else {
		// Legacy loading for installations without Composer.
		dmyip_load_legacy();
	}
}

/**
 * Load legacy plugin components.
 *
 * This maintains backward compatibility for users who haven't run composer install.
 */
function dmyip_load_legacy() {
	require_once DMYIP_PLUGIN_DIR . 'includes/shortcodes.php';
	require_once DMYIP_PLUGIN_DIR . 'includes/core-filters.php';
	require_once DMYIP_PLUGIN_DIR . 'includes/rank-math-support.php';
	require_once DMYIP_PLUGIN_DIR . 'includes/seopress-support.php';
	require_once DMYIP_PLUGIN_DIR . 'includes/yoast-seo-support.php';
	require_once DMYIP_PLUGIN_DIR . 'includes/others.php';
	require_once DMYIP_PLUGIN_DIR . 'includes/block-editor.php';
}

// Initialize the plugin.
add_action( 'plugins_loaded', 'dmyip_load_plugin' );

/**
 * Activation hook.
 */
function dmyip_activate() {
	// Nothing needed for now - plugin is zero-config.
	// Future: flush rewrite rules if REST endpoints need pretty permalinks.
}
register_activation_hook( __FILE__, 'dmyip_activate' );

/**
 * Deactivation hook.
 */
function dmyip_deactivate() {
	// Clean up if needed.
}
register_deactivation_hook( __FILE__, 'dmyip_deactivate' );
