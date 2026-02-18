<?php
if ( ! defined( 'WPINC' ) ) {
    die;
}

/**
 * Core Filters Registration
 */

// Process shortcodes in excerpt.
function dmyip_render_shortcodes_in_excerpt( $excerpt ) {
    return do_shortcode( $excerpt );
}

// Enable shortcodes in archive titles.
function dmyip_enable_shortcode_in_archive_titles( $title ) {
    if ( is_archive() ) {
        return do_shortcode( $title );
    }
    return $title;
}

/**
 * Register core filters for shortcode processing.
 *
 * Deferred to `init` so themes can filter via `dmyip_core_filters`.
 */
function dmyip_register_core_filters() {
    $enabled_filters = apply_filters( 'dmyip_core_filters', array(
        'the_title'             => true,
        'single_post_title'     => true,
        'wp_title'              => true,
        'the_excerpt'           => true,
        'get_the_excerpt'       => true,
        'get_the_archive_title' => true,
    ) );

    if ( ! is_array( $enabled_filters ) ) {
        return;
    }

    if ( ! empty( $enabled_filters['the_title'] ) ) {
        add_filter( 'the_title', 'do_shortcode' );
    }
    if ( ! empty( $enabled_filters['single_post_title'] ) ) {
        add_filter( 'single_post_title', 'do_shortcode' );
    }
    if ( ! empty( $enabled_filters['wp_title'] ) ) {
        add_filter( 'wp_title', 'do_shortcode' );
    }
    if ( ! empty( $enabled_filters['the_excerpt'] ) ) {
        add_filter( 'the_excerpt', 'do_shortcode' );
    }
    if ( ! empty( $enabled_filters['get_the_excerpt'] ) ) {
        add_filter( 'get_the_excerpt', 'dmyip_render_shortcodes_in_excerpt' );
    }
    if ( ! empty( $enabled_filters['get_the_archive_title'] ) ) {
        add_filter( 'get_the_archive_title', 'dmyip_enable_shortcode_in_archive_titles' );
    }
}
add_action( 'init', 'dmyip_register_core_filters' );

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
            'daysuntil',
            'dayssince',
            'datepublished',
            'datemodified',
            'age',
            'season',
        )
    );
});
