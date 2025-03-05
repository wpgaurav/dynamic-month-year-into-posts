<?php
if ( ! defined( 'WPINC' ) ) {
    die;
}

/**
 * Rank Math Support Filters
 */
add_filter( 'rank_math/frontend/title', function( $title ) {
    return do_shortcode( $title );
});
add_filter( 'rank_math/frontend/description', function( $description ) {
    return do_shortcode( $description );
});
add_filter( 'rank_math/product_description/apply_shortcode', '__return_true' );
add_filter( 'rank_math/frontend/breadcrumb/html', 'do_shortcode' );

// Open Graph support.
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

// Process shortcodes recursively in Rank Math Schema/JSON-LD.
add_filter( 'rank_math/json_ld', function( $data, $context ) {
    if ( is_array( $data ) ) {
        array_walk_recursive( $data, function ( &$value ) {
            $value = do_shortcode( $value );
        });
    }
    return $data;
}, PHP_INT_MAX, 2 );
