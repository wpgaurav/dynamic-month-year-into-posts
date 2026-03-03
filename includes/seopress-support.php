<?php
if ( ! defined( 'WPINC' ) ) {
    die;
}

/**
 * SEO Support Filters
 */

// SEOPress support.
add_filter( 'seopress_titles_title', 'do_shortcode' );
add_filter( 'seopress_titles_desc', 'do_shortcode' );
