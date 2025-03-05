<?php
if ( ! defined( 'WPINC' ) ) {
    die;
}

// Elementor support.
add_filter( 'elementor/widget/render_content', 'do_shortcode' );

// CRP support.
add_filter( 'crp_title', 'do_shortcode' );