<?php
if ( ! defined( 'WPINC' ) ) {
    die;
}

// Elementor support.
add_filter( 'elementor/widget/render_content', 'do_shortcode' );

// Bricks Builder support.
add_filter( 'bricks/dynamic_data/render_content', function ( $content, $post, $context ) {
	if ( ! is_string( $content ) || strpos( $content, '[' ) === false ) {
		return $content;
	}
	return do_shortcode( $content );
}, 20, 3 );

// CRP support.
add_filter( 'crp_title', 'do_shortcode' );