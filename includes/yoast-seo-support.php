<?php
if ( ! defined( 'WPINC' ) ) {
    die;
}

/**
 * SEO Support Filters
 */

// Yoast SEO support.
add_filter( 'wpseo_title', 'do_shortcode' );
add_filter( 'wpseo_metadesc', 'do_shortcode' );
add_filter( 'wpseo_opengraph_title', 'do_shortcode' );
add_filter( 'wpseo_opengraph_desc', 'do_shortcode' );
// Uncomment if you need JSON-LD output support.
// add_filter( 'wpseo_json_ld_output', 'do_shortcode' );
add_filter( 'wpseo_schema_webpage', function( $data ) {
    $data['name'] = do_shortcode( $data['name'] );
    return $data;
});
