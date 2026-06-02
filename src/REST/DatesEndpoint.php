<?php
/**
 * REST API endpoint for dates.
 *
 * @package DMYIP
 */

declare(strict_types=1);

namespace DMYIP\REST;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;

/**
 * REST API endpoint for dynamic dates.
 */
class DatesEndpoint {

	/**
	 * Namespace.
	 */
	private const NAMESPACE = 'dmyip/v1';

	/**
	 * REST date types.
	 */
	private const VALID_TYPES = [
		'year',
		'nyear',
		'nnyear',
		'pyear',
		'ppyear',
		'month',
		'cmonth',
		'mon',
		'cmon',
		'mm',
		'mn',
		'nmonth',
		'cnmonth',
		'nmon',
		'cnmon',
		'pmonth',
		'cpmonth',
		'pmon',
		'cpmon',
		'date',
		'monthyear',
		'nmonthyear',
		'pmonthyear',
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
		'season_south',
	];

	/**
	 * Shortcodes allowed through the public render endpoint.
	 */
	private const RENDERABLE_SHORTCODES = [
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
	];

	/**
	 * Register the endpoint.
	 *
	 * @return void
	 */
	public function register(): void {
		add_action( 'rest_api_init', [ $this, 'register_routes' ] );
	}

	/**
	 * Register REST routes.
	 *
	 * @return void
	 */
	public function register_routes(): void {
		register_rest_route(
			self::NAMESPACE,
			'/dates',
			[
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => [ $this, 'get_all_dates' ],
				'permission_callback' => '__return_true',
			]
		);

		register_rest_route(
			self::NAMESPACE,
			'/date/(?P<type>[a-z_]+)',
			[
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => [ $this, 'get_single_date' ],
				'permission_callback' => '__return_true',
				'args'                => [
					'type'   => [
						'required'          => true,
						'validate_callback' => [ $this, 'validate_type' ],
					],
					'offset' => [
						'default'           => 0,
						'sanitize_callback' => [ $this, 'sanitize_offset' ],
					],
					'date'   => [
						'default'           => '',
						'sanitize_callback' => 'sanitize_text_field',
					],
				],
			]
		);

		register_rest_route(
			self::NAMESPACE,
			'/shortcodes',
			[
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => [ $this, 'get_shortcodes_list' ],
				'permission_callback' => '__return_true',
			]
		);

		register_rest_route(
			self::NAMESPACE,
			'/render',
			[
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => [ $this, 'render_shortcode' ],
				'permission_callback' => '__return_true',
				'args'                => [
					'shortcode' => [
						'required'          => true,
						'sanitize_callback' => 'sanitize_text_field',
						'validate_callback' => [ $this, 'validate_shortcode' ],
					],
				],
			]
		);
	}

	/**
	 * Get all dates.
	 *
	 * @return WP_REST_Response
	 */
	public function get_all_dates(): WP_REST_Response {
		$dates = [
			'year'        => [
				'current'  => do_shortcode( '[year]' ),
				'next'     => do_shortcode( '[nyear]' ),
				'previous' => do_shortcode( '[pyear]' ),
			],
			'month'       => [
				'current'       => do_shortcode( '[month]' ),
				'current_short' => do_shortcode( '[mon]' ),
				'next'          => do_shortcode( '[nmonth]' ),
				'previous'      => do_shortcode( '[pmonth]' ),
				'number'        => do_shortcode( '[mn]' ),
			],
			'day'         => [
				'current'       => do_shortcode( '[dt]' ),
				'next'          => do_shortcode( '[nd]' ),
				'previous'      => do_shortcode( '[pd]' ),
				'weekday'       => do_shortcode( '[weekday]' ),
				'weekday_short' => do_shortcode( '[wd]' ),
			],
			'combined'    => [
				'date'           => do_shortcode( '[date]' ),
				'monthyear'      => do_shortcode( '[monthyear]' ),
				'next_monthyear' => do_shortcode( '[nmonthyear]' ),
				'prev_monthyear' => do_shortcode( '[pmonthyear]' ),
			],
			'events'      => [
				'blackfriday' => do_shortcode( '[blackfriday]' ),
				'cybermonday' => do_shortcode( '[cybermonday]' ),
			],
			'season'      => [
				'north' => do_shortcode( '[season]' ),
				'south' => do_shortcode( '[season region="south"]' ),
			],
			'timezone'    => wp_timezone_string(),
			'timestamp'   => time(),
			'gmt_offset'  => get_option( 'gmt_offset' ),
			'date_format' => get_option( 'date_format' ),
			'time_format' => get_option( 'time_format' ),
		];

		return new WP_REST_Response( $dates, 200 );
	}

	/**
	 * Get a single date type.
	 *
	 * @param WP_REST_Request $request Request object.
	 * @return WP_REST_Response
	 */
	public function get_single_date( WP_REST_Request $request ): WP_REST_Response {
		$type   = (string) $request->get_param( 'type' );
		$offset = (int) $request->get_param( 'offset' );
		$date   = (string) $request->get_param( 'date' );

		$shortcode_map = [
			'year'          => '[year]',
			'nyear'         => '[nyear]',
			'nnyear'        => '[nnyear]',
			'pyear'         => '[pyear]',
			'ppyear'        => '[ppyear]',
			'month'         => '[month]',
			'cmonth'        => '[cmonth]',
			'mon'           => '[mon]',
			'cmon'          => '[cmon]',
			'mm'            => '[mm]',
			'mn'            => '[mn]',
			'nmonth'        => '[nmonth]',
			'cnmonth'       => '[cnmonth]',
			'nmon'          => '[nmon]',
			'cnmon'         => '[cnmon]',
			'pmonth'        => '[pmonth]',
			'cpmonth'       => '[cpmonth]',
			'pmon'          => '[pmon]',
			'cpmon'         => '[cpmon]',
			'date'          => '[date]',
			'monthyear'     => '[monthyear]',
			'nmonthyear'    => '[nmonthyear]',
			'pmonthyear'    => '[pmonthyear]',
			'dt'            => '[dt]',
			'nd'            => '[nd]',
			'pd'            => '[pd]',
			'weekday'       => '[weekday]',
			'wd'            => '[wd]',
			'blackfriday'   => '[blackfriday]',
			'cybermonday'   => '[cybermonday]',
			'datepublished' => '[datepublished]',
			'datemodified'  => '[datemodified]',
			'season'        => '[season]',
			'season_south'  => '[season region="south"]',
		];

		// Handle special cases.
		if ( 'year' === $type && 0 !== $offset ) {
			$value = do_shortcode( '[year n="' . $offset . '"]' );
		} elseif ( 'daysuntil' === $type && '' !== $date ) {
			$value = do_shortcode( '[daysuntil date="' . esc_attr( $date ) . '"]' );
		} elseif ( 'dayssince' === $type && '' !== $date ) {
			$value = do_shortcode( '[dayssince date="' . esc_attr( $date ) . '"]' );
		} elseif ( 'age' === $type && '' !== $date ) {
			$value = do_shortcode( '[age date="' . esc_attr( $date ) . '"]' );
		} elseif ( isset( $shortcode_map[ $type ] ) ) {
			$value = do_shortcode( $shortcode_map[ $type ] );
		} else {
			return new WP_REST_Response(
				[ 'error' => __( 'Invalid date type or missing required parameter.', 'dynamic-month-year-into-posts' ) ],
				400
			);
		}

		return new WP_REST_Response(
			[
				'type'  => $type,
				'value' => $value,
			],
			200
		);
	}

	/**
	 * Render one plugin shortcode.
	 *
	 * @param WP_REST_Request $request Request object.
	 * @return WP_REST_Response
	 */
	public function render_shortcode( WP_REST_Request $request ): WP_REST_Response {
		$shortcode = trim( (string) $request->get_param( 'shortcode' ) );

		if ( '' === $shortcode || ! $this->is_renderable_shortcode( $shortcode ) ) {
			return new WP_REST_Response(
				[ 'error' => __( 'Invalid shortcode.', 'dynamic-month-year-into-posts' ) ],
				400
			);
		}

		return new WP_REST_Response(
			[
				'shortcode' => $shortcode,
				'value'     => do_shortcode( $shortcode ),
			],
			200
		);
	}

	/**
	 * Get list of available shortcodes.
	 *
	 * @return WP_REST_Response
	 */
	public function get_shortcodes_list(): WP_REST_Response {
		$shortcodes = [
			'year'       => [
				[
					'shortcode'   => '[year]',
					'description' => 'Current year',
					'example'     => do_shortcode( '[year]' ),
				],
				[
					'shortcode'   => '[year n=5]',
					'description' => 'Year with offset',
					'example'     => do_shortcode( '[year n=5]' ),
				],
				[
					'shortcode'   => '[nyear]',
					'description' => 'Next year',
					'example'     => do_shortcode( '[nyear]' ),
				],
				[
					'shortcode'   => '[pyear]',
					'description' => 'Previous year',
					'example'     => do_shortcode( '[pyear]' ),
				],
			],
			'month'      => [
				[
					'shortcode'   => '[month]',
					'description' => 'Current month (full)',
					'example'     => do_shortcode( '[month]' ),
				],
				[
					'shortcode'   => '[mon]',
					'description' => 'Current month (short)',
					'example'     => do_shortcode( '[mon]' ),
				],
				[
					'shortcode'   => '[mm]',
					'description' => 'Month number (01-12)',
					'example'     => do_shortcode( '[mm]' ),
				],
				[
					'shortcode'   => '[nmonth]',
					'description' => 'Next month',
					'example'     => do_shortcode( '[nmonth]' ),
				],
				[
					'shortcode'   => '[pmonth]',
					'description' => 'Previous month',
					'example'     => do_shortcode( '[pmonth]' ),
				],
			],
			'day'        => [
				[
					'shortcode'   => '[dt]',
					'description' => 'Day of month',
					'example'     => do_shortcode( '[dt]' ),
				],
				[
					'shortcode'   => '[weekday]',
					'description' => 'Day of week (full)',
					'example'     => do_shortcode( '[weekday]' ),
				],
				[
					'shortcode'   => '[wd]',
					'description' => 'Day of week (short)',
					'example'     => do_shortcode( '[wd]' ),
				],
			],
			'combined'   => [
				[
					'shortcode'   => '[date]',
					'description' => 'Full date',
					'example'     => do_shortcode( '[date]' ),
				],
				[
					'shortcode'   => '[monthyear]',
					'description' => 'Month and year',
					'example'     => do_shortcode( '[monthyear]' ),
				],
			],
			'events'     => [
				[
					'shortcode'   => '[blackfriday]',
					'description' => 'Black Friday date',
					'example'     => do_shortcode( '[blackfriday]' ),
				],
				[
					'shortcode'   => '[cybermonday]',
					'description' => 'Cyber Monday date',
					'example'     => do_shortcode( '[cybermonday]' ),
				],
			],
			'countdown'  => [
				[
					'shortcode'   => '[daysuntil date="YYYY-MM-DD"]',
					'description' => 'Days until a date',
					'example'     => 'Requires date parameter',
				],
				[
					'shortcode'   => '[dayssince date="YYYY-MM-DD"]',
					'description' => 'Days since a date',
					'example'     => 'Requires date parameter',
				],
				[
					'shortcode'   => '[age date="YYYY-MM-DD"]',
					'description' => 'Age from a date (years)',
					'example'     => 'Requires date parameter',
				],
				[
					'shortcode'   => '[age date="YYYY-MM-DD" ordinal="true"]',
					'description' => 'Age with ordinal suffix (1st, 2nd, 3rd)',
					'example'     => 'Requires date parameter',
				],
			],
			'season'     => [
				[
					'shortcode'   => '[season]',
					'description' => 'Current season (Northern hemisphere)',
					'example'     => do_shortcode( '[season]' ),
				],
				[
					'shortcode'   => '[season region="south"]',
					'description' => 'Current season (Southern hemisphere)',
					'example'     => do_shortcode( '[season region="south"]' ),
				],
			],
			'post_dates' => [
				[
					'shortcode'   => '[datepublished]',
					'description' => 'Post publication date',
					'example'     => 'Requires post context',
				],
				[
					'shortcode'   => '[datemodified]',
					'description' => 'Post modified date',
					'example'     => 'Requires post context',
				],
			],
		];

		return new WP_REST_Response( $shortcodes, 200 );
	}

	/**
	 * Validate date type parameter.
	 *
	 * @param string $value Type value.
	 * @return bool
	 */
	public function validate_type( string $value ): bool {
		return in_array( $value, self::VALID_TYPES, true );
	}

	/**
	 * Sanitize a signed year offset.
	 *
	 * @param mixed $value Offset value.
	 * @return int
	 */
	public function sanitize_offset( $value ): int {
		return (int) $value;
	}

	/**
	 * Validate render endpoint shortcode.
	 *
	 * @param mixed $value Shortcode string.
	 * @return bool
	 */
	public function validate_shortcode( $value ): bool {
		return is_string( $value ) && $this->is_renderable_shortcode( trim( $value ) );
	}

	/**
	 * Check whether a shortcode is safe to render through REST.
	 *
	 * @param string $shortcode Shortcode string.
	 * @return bool
	 */
	private function is_renderable_shortcode( string $shortcode ): bool {
		$tag = $this->get_shortcode_tag( $shortcode );

		return null !== $tag && in_array( $tag, self::RENDERABLE_SHORTCODES, true );
	}

	/**
	 * Extract a shortcode tag from a single shortcode string.
	 *
	 * @param string $shortcode Shortcode string.
	 * @return string|null
	 */
	private function get_shortcode_tag( string $shortcode ): ?string {
		if ( 1 !== preg_match( '/^\[([A-Za-z0-9_-]+)(?:\s[^\]]*)?\]$/', $shortcode, $matches ) ) {
			return null;
		}

		return strtolower( $matches[1] );
	}
}
