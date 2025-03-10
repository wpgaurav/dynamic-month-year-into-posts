<?php
if ( ! defined( 'WPINC' ) ) {
    die;
}

/**
 * Core Filters Registration
 */

// Allow shortcodes in titles, excerpts, and more.
add_filter( 'the_title', 'do_shortcode' );
add_filter( 'single_post_title', 'do_shortcode' );
add_filter( 'wp_title', 'do_shortcode' );
add_filter( 'the_excerpt', 'do_shortcode' );

// Process shortcodes in excerpt.
function dmyip_render_shortcodes_in_excerpt( $excerpt ) {
    return do_shortcode( $excerpt );
}
add_filter( 'get_the_excerpt', 'dmyip_render_shortcodes_in_excerpt' );

// Avoid shortcodes being stripped + thanks @meteorlxy
add_filter( 'strip_shortcodes_tagnames', function ( $tags_to_remove ) {
    return array_diff(
        $tags_to_remove,
        array(
            'year',
            'month',
            'cmonth',
            'mon',
            'cmon',
            'mm',
            'mn',
            'nmonth',
            'cnmonth',
            'pmonth',
            'cpmonth',
            'nmon',
            'cnmon',
            'pmon',
            'cpmon',
            'date',
            'monthyear',
            'nmonthyear',
            'pmonthyear',
            'nyear',
            'nnyear',
            'pyear',
            'ppyear',
            'dt',
            'nd',
            'pd',
            'weekday',
            'wd',
            'blackfriday',
            'cybermonday',
            'datepublished',
            'datemodified',
        )
    );
});

// Enable shortcodes in archive titles.
function dmyip_enable_shortcode_in_archive_titles( $title ) {
    if ( is_archive() ) {
        return do_shortcode( $title );
    }
    return $title;
}
add_filter( 'get_the_archive_title', 'dmyip_enable_shortcode_in_archive_titles' );
