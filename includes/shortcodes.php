<?php
// Prevent direct access.
if ( ! defined( 'WPINC' ) ) {
    die;
}

/**
 * Shortcodes Registration
 */

// [year] shortcode.
add_shortcode('year', 'dmyip_rmd_current_year');
function dmyip_rmd_current_year($atts) {
    $attributes   = shortcode_atts( array( 'n' => 0 ), $atts );
    $current_year = intval( date_i18n( 'Y' ) );
    $result       = $current_year + intval( $attributes['n'] );
    return esc_html( strval( $result ) );
}

// [month] shortcode.
add_shortcode( 'month', 'dmyip_rmd_current_month' );
function dmyip_rmd_current_month() {
    return esc_html( date_i18n( 'F' ) );
}

// [cmonth] shortcode.
add_shortcode( 'cmonth', 'dmyip_rmd_current_caps_month' );
function dmyip_rmd_current_caps_month() {
    return esc_html( ucfirst( date_i18n( 'F' ) ) );
}

// [mon] shortcode.
add_shortcode( 'mon', 'dmyip_rmd_current_mon' );
function dmyip_rmd_current_mon() {
    return esc_html( date_i18n( 'M' ) );
}

// [cmon] shortcode.
add_shortcode( 'cmon', 'dmyip_rmd_current_caps_mon' );
function dmyip_rmd_current_caps_mon() {
    return esc_html( ucfirst( date_i18n( 'M' ) ) );
}

// [mm] shortcode.
add_shortcode( 'mm', 'dmyip_rmd_current_mm' );
function dmyip_rmd_current_mm() {
    return esc_html( date_i18n( 'm' ) );
}

// [mn] shortcode.
add_shortcode( 'mn', 'dmyip_rmd_current_mn' );
function dmyip_rmd_current_mn() {
    return esc_html( date_i18n( 'n' ) );
}

// [nmonth] shortcode.
add_shortcode( 'nmonth', 'dmyip_rmd_next_month' );
function dmyip_rmd_next_month() {
    return esc_html( date_i18n( 'F', mktime( 0, 0, 0, (int) gmdate( 'n' ) + 1, 1 ) ) );
}

// [cnmonth] shortcode.
add_shortcode( 'cnmonth', 'dmyip_rmd_next_caps_month' );
function dmyip_rmd_next_caps_month() {
    return esc_html( ucfirst( date_i18n( 'F', mktime( 0, 0, 0, (int) gmdate( 'n' ) + 1, 1 ) ) ) );
}

// [pmonth] shortcode.
add_shortcode( 'pmonth', 'dmyip_rmd_prev_month' );
function dmyip_rmd_prev_month() {
    return esc_html( date_i18n( 'F', strtotime( 'previous month' ) ) );
}

// [cpmonth] shortcode.
add_shortcode( 'cpmonth', 'dmyip_rmd_prev_caps_month' );
function dmyip_rmd_prev_caps_month() {
    return esc_html( ucfirst( date_i18n( 'F', strtotime( 'previous month' ) ) ) );
}

// [nmon] shortcode.
add_shortcode( 'nmon', 'dmyip_rmd_next_month_short' );
function dmyip_rmd_next_month_short() {
    return esc_html( date_i18n( 'M', mktime( 0, 0, 0, (int) gmdate( 'n' ) + 1, 1 ) ) );
}

// [cnmon] shortcode.
add_shortcode( 'cnmon', 'dmyip_rmd_next_month_short_caps' );
function dmyip_rmd_next_month_short_caps() {
    return esc_html( ucfirst( date_i18n( 'M', mktime( 0, 0, 0, (int) gmdate( 'n' ) + 1, 1 ) ) ) );
}

// [pmon] shortcode.
add_shortcode( 'pmon', 'dmyip_rmd_prev_month_short' );
function dmyip_rmd_prev_month_short() {
    return esc_html( date_i18n( 'M', strtotime( 'previous month' ) ) );
}

// [cpmon] shortcode.
add_shortcode( 'cpmon', 'dmyip_rmd_prev_month_short_caps' );
function dmyip_rmd_prev_month_short_caps() {
    return esc_html( ucfirst( date_i18n( 'M', strtotime( 'previous month' ) ) ) );
}

// [date] shortcode.
add_shortcode( 'date', 'dmyip_rmd_current_date' );
function dmyip_rmd_current_date() {
    return esc_html( date_i18n( 'F j, Y' ) );
}

// [monthyear] shortcode.
add_shortcode( 'monthyear', 'dmyip_rmd_monthyear' );
function dmyip_rmd_monthyear() {
    return esc_html( ucfirst( date_i18n( 'F Y' ) ) );
}

// [nmonthyear] shortcode.
function dmyip_nmonthyear() {
    return esc_html( ucfirst( date_i18n( 'F Y', strtotime( "+1 month" ) ) ) );
}
add_shortcode( 'nmonthyear', 'dmyip_nmonthyear' );

// [pmonthyear] shortcode.
function dmyip_pmonthyear() {
    return esc_html( ucfirst( date_i18n( 'F Y', strtotime( "-1 month" ) ) ) );
}
add_shortcode( 'pmonthyear', 'dmyip_pmonthyear' );

// [nyear] shortcode.
add_shortcode( 'nyear', 'dmyip_rmd_next_year' );
function dmyip_rmd_next_year() {
    return esc_html( date_i18n( 'Y' ) + 1 );
}

// [nnyear] shortcode.
add_shortcode( 'nnyear', 'dmyip_rmd_next_next_year' );
function dmyip_rmd_next_next_year() {
    return esc_html( date_i18n( 'Y' ) + 2 );
}

// [pyear] shortcode.
add_shortcode( 'pyear', 'dmyip_rmd_previous_year' );
function dmyip_rmd_previous_year() {
    return esc_html( date_i18n( 'Y' ) - 1 );
}

// [ppyear] shortcode.
add_shortcode( 'ppyear', 'dmyip_rmd_previous_previous_year' );
function dmyip_rmd_previous_previous_year() {
    return esc_html( date_i18n( 'Y' ) - 2 );
}

// Day shortcodes.
add_shortcode( 'dt', 'dmyip_rmd_current_dt' );
function dmyip_rmd_current_dt() {
    return esc_html( date_i18n( 'j' ) );
}

add_shortcode( 'nd', 'dmyip_rmd_next_date' );
function dmyip_rmd_next_date() {
    return esc_html( date_i18n( 'j', strtotime( '+1 day' ) ) );
}

add_shortcode( 'pd', 'dmyip_rmd_prev_date' );
function dmyip_rmd_prev_date() {
    return esc_html( date_i18n( 'j', strtotime( '-1 day' ) ) );
}

// Weekday shortcodes.
add_shortcode( 'weekday', 'dmyip_rmd_current_weekday' );
function dmyip_rmd_current_weekday() {
    return esc_html( date_i18n( 'l' ) );
}

add_shortcode( 'wd', 'dmyip_rmd_current_wd' );
function dmyip_rmd_current_wd() {
    return esc_html( date_i18n( 'D' ) );
}

// Special event shortcodes.
add_shortcode( 'blackfriday', 'dmyip_rmd_blackfriday' );
function dmyip_rmd_blackfriday() {
    $year         = gmdate( 'Y' );
    $thanksgiving = strtotime( "fourth thursday of november $year" );
    $black_friday = strtotime( '+1 day', $thanksgiving );
    return esc_html( date_i18n( 'F j', $black_friday ) );
}

add_shortcode( 'cybermonday', 'dmyip_rmd_cybermonday' );
function dmyip_rmd_cybermonday() {
    $year         = gmdate( 'Y' );
    $thanksgiving = strtotime( "fourth thursday of november $year" );
    $cyber_monday = strtotime( "+4 day", $thanksgiving );
    return esc_html( date_i18n( 'F j', $cyber_monday ) );
}

// Countdown shortcodes.
add_shortcode( 'daysuntil', 'dmyip_rmd_daysuntil' );
/**
 * Calculate days until a specific date.
 * Usage: [daysuntil date="2025-12-31"] or [daysuntil date="December 31, 2025"]
 *
 * @param array $atts Shortcode attributes.
 * @return string Number of days until the target date.
 */
function dmyip_rmd_daysuntil( $atts ) {
    $attributes = shortcode_atts(
        array(
            'date' => '',
        ),
        $atts
    );

    if ( empty( $attributes['date'] ) ) {
        return '';
    }

    $target_timestamp = strtotime( $attributes['date'] );
    if ( false === $target_timestamp ) {
        return '';
    }

    $today_timestamp = strtotime( 'today' );
    $diff_seconds    = $target_timestamp - $today_timestamp;
    $diff_days       = intval( floor( $diff_seconds / DAY_IN_SECONDS ) );

    return esc_html( $diff_days );
}

add_shortcode( 'dayssince', 'dmyip_rmd_dayssince' );
/**
 * Calculate days since a specific date.
 * Usage: [dayssince date="2020-01-01"]
 *
 * @param array $atts Shortcode attributes.
 * @return string Number of days since the target date.
 */
function dmyip_rmd_dayssince( $atts ) {
    $attributes = shortcode_atts(
        array(
            'date' => '',
        ),
        $atts
    );

    if ( empty( $attributes['date'] ) ) {
        return '';
    }

    $target_timestamp = strtotime( $attributes['date'] );
    if ( false === $target_timestamp ) {
        return '';
    }

    $today_timestamp = strtotime( 'today' );
    $diff_seconds    = $today_timestamp - $target_timestamp;
    $diff_days       = intval( floor( $diff_seconds / DAY_IN_SECONDS ) );

    return esc_html( $diff_days );
}

// Age shortcode.
add_shortcode( 'age', 'dmyip_rmd_age' );
/**
 * Calculate age from a birth date.
 * Usage: [age date="1990-05-15"] - shows years only
 *        [age date="1990-05-15" format="ym"] - shows years and months
 *        [age date="1990-05-15" format="ymd"] - shows years, months, and days
 *
 * @param array $atts Shortcode attributes.
 * @return string Formatted age string.
 */
function dmyip_rmd_age( $atts ) {
    $attributes = shortcode_atts(
        array(
            'date'   => '',
            'format' => 'y',
        ),
        $atts
    );

    if ( empty( $attributes['date'] ) ) {
        return '';
    }

    $birth_date = strtotime( $attributes['date'] );
    if ( false === $birth_date ) {
        return '';
    }

    $birth = new DateTime( gmdate( 'Y-m-d', $birth_date ) );
    $today = new DateTime( gmdate( 'Y-m-d' ) );
    $diff  = $today->diff( $birth );

    $format = strtolower( $attributes['format'] );

    switch ( $format ) {
        case 'ymd':
            $parts = array();
            if ( $diff->y > 0 ) {
                $parts[] = sprintf(
                    /* translators: %d: number of years */
                    _n( '%d year', '%d years', $diff->y, 'dynamic-month-year-into-posts' ),
                    $diff->y
                );
            }
            if ( $diff->m > 0 ) {
                $parts[] = sprintf(
                    /* translators: %d: number of months */
                    _n( '%d month', '%d months', $diff->m, 'dynamic-month-year-into-posts' ),
                    $diff->m
                );
            }
            if ( $diff->d > 0 ) {
                $parts[] = sprintf(
                    /* translators: %d: number of days */
                    _n( '%d day', '%d days', $diff->d, 'dynamic-month-year-into-posts' ),
                    $diff->d
                );
            }
            return esc_html( implode( ', ', $parts ) );

        case 'ym':
            $parts = array();
            if ( $diff->y > 0 ) {
                $parts[] = sprintf(
                    /* translators: %d: number of years */
                    _n( '%d year', '%d years', $diff->y, 'dynamic-month-year-into-posts' ),
                    $diff->y
                );
            }
            if ( $diff->m > 0 ) {
                $parts[] = sprintf(
                    /* translators: %d: number of months */
                    _n( '%d month', '%d months', $diff->m, 'dynamic-month-year-into-posts' ),
                    $diff->m
                );
            }
            return esc_html( implode( ', ', $parts ) );

        case 'y':
        default:
            return esc_html( (string) $diff->y );
    }
}

// Published and Modified Date shortcodes.
add_shortcode( 'datepublished', 'dmyip_rmd_published' );
function dmyip_rmd_published() {
    $published_timestamp = get_the_time( 'U' );
    $date_format         = get_option( 'date_format' );
    return date_i18n( $date_format, $published_timestamp );
}

add_shortcode( 'datemodified', 'dmyip_rmd_modified' );
function dmyip_rmd_modified() {
    $last_modified_timestamp = get_the_modified_time( 'U' );
    $date_format             = get_option( 'date_format' );
    return date_i18n( $date_format, $last_modified_timestamp );
}

// Settings link for plugin action links.
add_filter( 'plugin_action_links_dynamic-month-year-into-posts/dynamic-month-year-into-posts.php', 'dmyip_settings_link' );
function dmyip_settings_link( $links ) {
    $settings_link = '<a href="https://gauravtiwari.org/snippet/dynamic-month-year/#shortcodes">' . __( 'List of Shortcodes', 'dynamic-month-year-into-posts' ) . '</a>';
    $links[] = $settings_link;
    return $links;
}
