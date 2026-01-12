<?php
/**
 * Core WordPress filters for shortcode processing.
 *
 * @package DMYIP
 */

declare(strict_types=1);

namespace DMYIP\Shortcodes;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WordPress core filters to enable shortcodes in various contexts.
 */
class CoreFilters {

	/**
	 * List of plugin shortcode tags.
	 *
	 * @var array<string>
	 */
	private const SHORTCODE_TAGS = [
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
	];

	/**
	 * Register filters.
	 *
	 * @return void
	 */
	public function register(): void {
		// Enable shortcodes in titles and excerpts.
		add_filter( 'the_title', 'do_shortcode' );
		add_filter( 'single_post_title', 'do_shortcode' );
		add_filter( 'wp_title', 'do_shortcode' );
		add_filter( 'the_excerpt', 'do_shortcode' );
		add_filter( 'get_the_excerpt', [ $this, 'render_shortcodes_in_excerpt' ] );

		// Prevent shortcodes from being stripped.
		add_filter( 'strip_shortcodes_tagnames', [ $this, 'preserve_shortcodes' ] );

		// Enable shortcodes in archive titles.
		add_filter( 'get_the_archive_title', [ $this, 'archive_title_shortcodes' ] );

		// Plugin action links.
		add_filter(
			'plugin_action_links_dynamic-month-year-into-posts/dynamic-month-year-into-posts.php',
			[ $this, 'add_action_links' ]
		);
	}

	/**
	 * Render shortcodes in excerpt.
	 *
	 * @param string $excerpt The excerpt.
	 * @return string
	 */
	public function render_shortcodes_in_excerpt( string $excerpt ): string {
		return do_shortcode( $excerpt );
	}

	/**
	 * Prevent plugin shortcodes from being stripped.
	 *
	 * @param array<string> $tags_to_remove Tags to remove.
	 * @return array<string>
	 */
	public function preserve_shortcodes( array $tags_to_remove ): array {
		return array_diff( $tags_to_remove, self::SHORTCODE_TAGS );
	}

	/**
	 * Enable shortcodes in archive titles.
	 *
	 * @param string $title Archive title.
	 * @return string
	 */
	public function archive_title_shortcodes( string $title ): string {
		if ( is_archive() ) {
			return do_shortcode( $title );
		}
		return $title;
	}

	/**
	 * Add plugin action links.
	 *
	 * @param array<string> $links Existing links.
	 * @return array<string>
	 */
	public function add_action_links( array $links ): array {
		$links[] = '<a href="https://gauravtiwari.org/snippet/dynamic-month-year/#shortcodes">' .
			esc_html__( 'List of Shortcodes', 'dynamic-month-year-into-posts' ) .
			'</a>';
		return $links;
	}
}
