<?php
/**
 * WordPress integration-test bootstrap.
 *
 * @package DMYIP
 */

$dmyip_plugin_dir = dirname( __DIR__ );

require_once $dmyip_plugin_dir . '/vendor/autoload.php';

$dmyip_tests_dir = getenv( 'WP_TESTS_DIR' );

if ( ! $dmyip_tests_dir ) {
	$dmyip_tests_dir = rtrim( sys_get_temp_dir(), '/\\' ) . '/wordpress-tests-lib';
}

if ( ! file_exists( $dmyip_tests_dir . '/includes/functions.php' ) ) {
	throw new RuntimeException(
		'WordPress test library not found. Set WP_TESTS_DIR or run the standalone suite with composer test.'
	);
}

require_once $dmyip_tests_dir . '/includes/functions.php';

tests_add_filter(
	'muplugins_loaded',
	static function () use ( $dmyip_plugin_dir ): void {
		require $dmyip_plugin_dir . '/dynamic-month-year-into-posts.php';
	}
);

require $dmyip_tests_dir . '/includes/bootstrap.php';
