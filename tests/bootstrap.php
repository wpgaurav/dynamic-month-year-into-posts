<?php
/**
 * PHPUnit bootstrap file.
 *
 * @package DMYIP
 */

// Composer autoloader.
require_once dirname( __DIR__ ) . '/vendor/autoload.php';

// Define test constants.
define( 'DMYIP_TESTS_DIR', __DIR__ );
define( 'DMYIP_PLUGIN_DIR', dirname( __DIR__ ) );

// Check for WordPress test library.
$_tests_dir = getenv( 'WP_TESTS_DIR' );

if ( ! $_tests_dir ) {
	$_tests_dir = rtrim( sys_get_temp_dir(), '/\\' ) . '/wordpress-tests-lib';
}

if ( ! file_exists( "{$_tests_dir}/includes/functions.php" ) ) {
	// Fall back to mock testing without WordPress test suite.
	echo "WordPress test library not found. Running standalone tests only.\n";

	// Define WordPress constants for standalone testing.
	if ( ! defined( 'ABSPATH' ) ) {
		define( 'ABSPATH', DMYIP_PLUGIN_DIR . '/' );
	}
	if ( ! defined( 'WPINC' ) ) {
		define( 'WPINC', 'wp-includes' );
	}
	if ( ! defined( 'DAY_IN_SECONDS' ) ) {
		define( 'DAY_IN_SECONDS', 86400 );
	}

	// Mock WordPress functions for standalone tests.
	if ( ! function_exists( 'date_i18n' ) ) {
		function date_i18n( $format, $timestamp = null ) {
			return date( $format, $timestamp ?? time() );
		}
	}
	if ( ! function_exists( 'esc_html' ) ) {
		function esc_html( $text ) {
			return htmlspecialchars( $text, ENT_QUOTES, 'UTF-8' );
		}
	}
	if ( ! function_exists( 'shortcode_atts' ) ) {
		function shortcode_atts( $defaults, $atts ) {
			$atts = (array) $atts;
			$out  = array();
			foreach ( $defaults as $name => $default ) {
				if ( array_key_exists( $name, $atts ) ) {
					$out[ $name ] = $atts[ $name ];
				} else {
					$out[ $name ] = $default;
				}
			}
			return $out;
		}
	}
	if ( ! function_exists( '__' ) ) {
		function __( $text, $domain = 'default' ) {
			return $text;
		}
	}
	if ( ! function_exists( 'esc_html__' ) ) {
		function esc_html__( $text, $domain = 'default' ) {
			return esc_html( $text );
		}
	}

	return;
}

// Give access to tests_add_filter() function.
require_once "{$_tests_dir}/includes/functions.php";

/**
 * Manually load the plugin being tested.
 */
function _manually_load_plugin() {
	require DMYIP_PLUGIN_DIR . '/dynamic-month-year-into-posts.php';
}

tests_add_filter( 'muplugins_loaded', '_manually_load_plugin' );

// Start up the WP testing environment.
require "{$_tests_dir}/includes/bootstrap.php";
