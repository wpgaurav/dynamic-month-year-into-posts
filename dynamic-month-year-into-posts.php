<?php
/**
 * Plugin Name:       Dynamic Month & Year into Posts
 * Plugin URI:        https://gauravtiwari.org/snippet/dynamic-month-year/
 * Description:       Insert dynamic year, month, dates, days, next and previous dates into content and meta using shortcodes and blocks.
 * Version:           1.8.0-beta
 * Author:            Gaurav Tiwari
 * Author URI:        https://gauravtiwari.org
 * License:           GPL-3.0+
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain:       dynamic-month-year-into-posts
 * Requires at least: 6.5
 * Requires PHP:      7.4
 *
 * @package DMYIP
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'DYNAMIC_MONTH_YEAR_INTO_POSTS_VERSION', '1.8.0-beta' );
define( 'DMYIP_PLUGIN_FILE', __FILE__ );
define( 'DMYIP_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'DMYIP_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

/**
 * Load the plugin.
 *
 * Uses Composer when available and a small PSR-4 fallback for source installs.
 *
 * @return void
 */
function dmyip_load_plugin(): void {
	$autoloader = DMYIP_PLUGIN_DIR . 'vendor/autoload.php';

	if ( file_exists( $autoloader ) ) {
		require_once $autoloader;
	} else {
		spl_autoload_register( 'dmyip_fallback_autoload' );
	}

	\DMYIP\Plugin::instance( DMYIP_PLUGIN_FILE )->init();
}

/**
 * Load DMYIP classes when Composer is unavailable.
 *
 * @param string $class_name Fully-qualified class name.
 * @return void
 */
function dmyip_fallback_autoload( string $class_name ): void {
	$prefix = 'DMYIP\\';

	if ( 0 !== strpos( $class_name, $prefix ) ) {
		return;
	}

	$relative = substr( $class_name, strlen( $prefix ) );
	$file     = DMYIP_PLUGIN_DIR . 'src/' . str_replace( '\\', '/', $relative ) . '.php';

	if ( is_readable( $file ) ) {
		require_once $file;
	}
}

// Initialize the plugin.
add_action( 'plugins_loaded', 'dmyip_load_plugin' );

/**
 * Activation hook.
 */
function dmyip_activate(): void {
	// Nothing needed for now - plugin is zero-config.
	// Future: flush rewrite rules if REST endpoints need pretty permalinks.
}
register_activation_hook( __FILE__, 'dmyip_activate' );

/**
 * Deactivation hook.
 */
function dmyip_deactivate(): void {
	// Clean up if needed.
}
register_deactivation_hook( __FILE__, 'dmyip_deactivate' );
