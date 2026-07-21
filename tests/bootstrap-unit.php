<?php
/**
 * Explicit standalone unit-test bootstrap.
 *
 * @package DMYIP
 */

require_once dirname( __DIR__ ) . '/vendor/autoload.php';

define( 'DMYIP_STANDALONE_TESTS', true );
define( 'ABSPATH', dirname( __DIR__ ) . '/' );
define( 'WPINC', 'wp-includes' );
define( 'DAY_IN_SECONDS', 86400 );

$GLOBALS['dmyip_test_options'] = [
	'timezone_string' => 'UTC',
	'gmt_offset'      => 0,
	'date_format'     => 'F j, Y',
	'time_format'     => 'g:i a',
];
$GLOBALS['dmyip_test_filters'] = [];

function get_option( $name, $fallback = false ) {
	return $GLOBALS['dmyip_test_options'][ $name ] ?? $fallback;
}

function update_option( $name, $value ) {
	$GLOBALS['dmyip_test_options'][ $name ] = $value;
	return true;
}

function wp_timezone() {
	$name = (string) get_option( 'timezone_string', 'UTC' );
	return new DateTimeZone( '' === $name ? 'UTC' : $name );
}

function current_datetime() {
	return new DateTimeImmutable( 'now', wp_timezone() );
}

function wp_date( $format, $timestamp = null, $timezone = null ) {
	$timezone = $timezone instanceof DateTimeZone ? $timezone : wp_timezone();
	$date     = new DateTimeImmutable( '@' . ( $timestamp ?? time() ) );
	return $date->setTimezone( $timezone )->format( $format );
}

function date_i18n( $format, $timestamp = null ) {
	return wp_date( $format, $timestamp );
}

function add_filter( $hook, $callback ) {
	$GLOBALS['dmyip_test_filters'][ $hook ][] = $callback;
	return true;
}

function remove_filter( $hook, $callback ) {
	if ( empty( $GLOBALS['dmyip_test_filters'][ $hook ] ) ) {
		return false;
	}

	foreach ( $GLOBALS['dmyip_test_filters'][ $hook ] as $index => $registered ) {
		if ( $registered === $callback ) {
			unset( $GLOBALS['dmyip_test_filters'][ $hook ][ $index ] );
			return true;
		}
	}

	return false;
}

function apply_filters( $hook, $value ) {
	foreach ( $GLOBALS['dmyip_test_filters'][ $hook ] ?? [] as $callback ) {
		$value = $callback( $value );
	}
	return $value;
}

function esc_html( $text ) {
	return htmlspecialchars( (string) $text, ENT_QUOTES, 'UTF-8' );
}

function esc_html__( $text, $domain = 'default' ) {
	unset( $domain );
	return esc_html( $text );
}

function __( $text, $domain = 'default' ) {
	unset( $domain );
	return $text;
}

function _x( $text, $context, $domain = 'default' ) {
	unset( $context, $domain );
	return $text;
}

function _n( $single, $plural, $number, $domain = 'default' ) {
	unset( $domain );
	return 1 === (int) $number ? $single : $plural;
}

function shortcode_atts( $defaults, $atts ) {
	return array_merge( $defaults, array_intersect_key( (array) $atts, $defaults ) );
}

function sanitize_key( $key ) {
	return preg_replace( '/[^a-z0-9_\\-]/', '', strtolower( (string) $key ) );
}

function sanitize_locale_name( $locale_name ) {
	return preg_replace( '/[^A-Za-z0-9_@.\\-]/', '', (string) $locale_name );
}

function get_shortcode_regex( $tagnames = null ) {
	$tagregexp = implode( '|', array_map( 'preg_quote', $tagnames ?? [] ) );

	return '\\['
		. '(\\[?)'
		. "($tagregexp)"
		. '(?![\\w-])'
		. '('
		. '[^\\]\\/]*'
		. '(?:\\/(?!\\])[^\\]\\/]*)*?'
		. ')'
		. '(?:'
		. '(\\/)'
		. '\\]'
		. '|'
		. '\\]'
		. '(?:'
		. '('
		. '[^\\[]*+'
		. '(?:\\[(?!\\/\\2\\])[^\\[]*+)*+'
		. ')'
		. '\\[\\/\\2\\]'
		. ')?'
		. ')'
		. '(\\]?)';
}
