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
						'sanitize_callback' => 'absint',
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
		$type   = $request->get_param( 'type' );
		$offset = $request->get_param( 'offset' );
		$date   = $request->get_param( 'date' );

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
		];

		// Handle special cases.
		if ( 'year' === $type && $offset > 0 ) {
			$value = do_shortcode( '[year n="' . $offset . '"]' );
		} elseif ( 'daysuntil' === $type && ! empty( $date ) ) {
			$value = do_shortcode( '[daysuntil date="' . esc_attr( $date ) . '"]' );
		} elseif ( 'dayssince' === $type && ! empty( $date ) ) {
			$value = do_shortcode( '[dayssince date="' . esc_attr( $date ) . '"]' );
		} elseif ( isset( $shortcode_map[ $type ] ) ) {
			$value = do_shortcode( $shortcode_map[ $type ] );
		} else {
			return new WP_REST_Response(
				[ 'error' => 'Invalid date type' ],
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
		$valid_types = [
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
		];

		return in_array( $value, $valid_types, true );
	}
}
