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

use DMYIP\Date\DateRenderer;
use DMYIP\Shortcodes\Registry;
use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;

/**
 * Public, read-only dynamic date endpoints.
 */
class DatesEndpoint {

	private const NAMESPACE = 'dmyip/v1';

	private const VALID_TYPES = [
		'year',
		'nyear',
		'nnyear',
		'pyear',
		'ppyear',
		'month',
		'month_short',
		'month_number',
		'month_number_zero',
		'nmonth',
		'pmonth',
		'date',
		'monthyear',
		'nmonthyear',
		'pmonthyear',
		'day',
		'weekday',
		'weekday_short',
		'published',
		'modified',
		'blackfriday',
		'cybermonday',
		'daysuntil',
		'dayssince',
		'age',
		'age_ym',
		'age_ymd',
		'age_ordinal',
		'season',
		'season_south',
		'nextoccurrence',
		'daysuntilnext',
		'occurrenceyear',
	];

	/**
	 * Shared renderer.
	 *
	 * @var DateRenderer
	 */
	private DateRenderer $renderer;

	/**
	 * Constructor.
	 *
	 * @param DateRenderer|null $renderer Shared renderer.
	 */
	public function __construct( ?DateRenderer $renderer = null ) {
		$this->renderer = $renderer ?? new DateRenderer();
	}

	/**
	 * Register REST hook.
	 *
	 * @return void
	 */
	public function register(): void {
		add_action( 'rest_api_init', [ $this, 'register_routes' ] );
	}

	/**
	 * Register routes.
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
					'type'         => [
						'required'          => true,
						'validate_callback' => [ $this, 'validate_type' ],
					],
					'offset'       => [
						'default'           => 0,
						'sanitize_callback' => [ $this, 'sanitize_offset' ],
					],
					'rollover_day' => [
						'default'           => 0,
						'sanitize_callback' => 'absint',
					],
					'date'         => [
						'default'           => '',
						'sanitize_callback' => 'sanitize_text_field',
					],
					'rule'         => [
						'default'           => '',
						'sanitize_callback' => 'sanitize_text_field',
					],
					'format'       => [
						'default'           => '',
						'sanitize_callback' => 'sanitize_text_field',
					],
					'case'         => [
						'default'           => 'none',
						'sanitize_callback' => 'sanitize_key',
					],
					'locale'       => [
						'default'           => '',
						'sanitize_callback' => 'sanitize_locale_name',
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
	 * Get commonly used current values.
	 *
	 * @return WP_REST_Response
	 */
	public function get_all_dates(): WP_REST_Response {
		$values = [
			'year'        => [
				'current'  => $this->renderer->render( 'year' ),
				'next'     => $this->renderer->render( 'nyear' ),
				'previous' => $this->renderer->render( 'pyear' ),
			],
			'month'       => [
				'current'       => $this->renderer->render( 'month' ),
				'current_short' => $this->renderer->render( 'month_short' ),
				'next'          => $this->renderer->render( 'nmonth' ),
				'previous'      => $this->renderer->render( 'pmonth' ),
				'number'        => $this->renderer->render( 'month_number' ),
			],
			'day'         => [
				'current'       => $this->renderer->render( 'day' ),
				'weekday'       => $this->renderer->render( 'weekday' ),
				'weekday_short' => $this->renderer->render( 'weekday_short' ),
			],
			'combined'    => [
				'date'       => $this->renderer->render( 'date' ),
				'monthyear'  => $this->renderer->render( 'monthyear' ),
				'next_month' => $this->renderer->render( 'nmonthyear' ),
				'prev_month' => $this->renderer->render( 'pmonthyear' ),
			],
			'events'      => [
				'blackfriday' => $this->renderer->render( 'blackfriday' ),
				'cybermonday' => $this->renderer->render( 'cybermonday' ),
			],
			'season'      => [
				'north' => $this->renderer->render( 'season' ),
				'south' => $this->renderer->render( 'season_south' ),
			],
			'timezone'    => wp_timezone()->getName(),
			'timestamp'   => time(),
			'date_format' => get_option( 'date_format' ),
			'time_format' => get_option( 'time_format' ),
		];

		return new WP_REST_Response( $values, 200 );
	}

	/**
	 * Render a single date type.
	 *
	 * @param WP_REST_Request $request Request.
	 * @return WP_REST_Response
	 */
	public function get_single_date( WP_REST_Request $request ): WP_REST_Response {
		$type  = sanitize_key( (string) $request->get_param( 'type' ) );
		$args  = [
			'offset'       => (int) $request->get_param( 'offset' ),
			'rollover_day' => min( 31, (int) $request->get_param( 'rollover_day' ) ),
			'date'         => (string) $request->get_param( 'date' ),
			'rule'         => (string) $request->get_param( 'rule' ),
			'format'       => (string) $request->get_param( 'format' ),
			'case'         => (string) $request->get_param( 'case' ),
			'locale'       => (string) $request->get_param( 'locale' ),
		];
		$value = $this->renderer->render( $type, $args );

		if (
			'' === $value &&
			in_array( $type, [ 'daysuntil', 'dayssince', 'age', 'age_ym', 'age_ymd', 'age_ordinal', 'nextoccurrence', 'daysuntilnext', 'occurrenceyear' ], true )
		) {
			return new WP_REST_Response(
				[ 'error' => __( 'A valid date or recurring rule is required for this type.', 'dynamic-month-year-into-posts' ) ],
				400
			);
		}

		return new WP_REST_Response(
			[
				'type'     => $type,
				'value'    => $value,
				'timezone' => wp_timezone()->getName(),
			],
			200
		);
	}

	/**
	 * Render exactly one allowlisted plugin shortcode.
	 *
	 * @param WP_REST_Request $request Request.
	 * @return WP_REST_Response
	 */
	public function render_shortcode( WP_REST_Request $request ): WP_REST_Response {
		$shortcode = trim( (string) $request->get_param( 'shortcode' ) );

		if ( ! Registry::is_single_shortcode( $shortcode ) ) {
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
	 * Return the documented shortcode catalog.
	 *
	 * @return WP_REST_Response
	 */
	public function get_shortcodes_list(): WP_REST_Response {
		$catalog = [
			'year'      => [
				'[year]',
				'[year n="5"]',
				'[nyear]',
				'[pyear]',
			],
			'month'     => [
				'[month]',
				'[mon]',
				'[nmonth]',
				'[pmonth]',
			],
			'date'      => [
				'[date]',
				'[monthyear]',
				'[weekday]',
				'[datepublished]',
				'[datemodified]',
			],
			'countdown' => [
				'[daysuntil date="YYYY-MM-DD"]',
				'[dayssince date="YYYY-MM-DD"]',
				'[age date="YYYY-MM-DD" format="ymd"]',
			],
			'events'    => [
				'[blackfriday]',
				'[cybermonday]',
				'[season]',
				'[season region="south"]',
			],
		];

		return new WP_REST_Response( $catalog, 200 );
	}

	/**
	 * Validate a date type.
	 *
	 * @param string $value Type.
	 * @return bool
	 */
	public function validate_type( string $value ): bool {
		return in_array( $value, self::VALID_TYPES, true );
	}

	/**
	 * Sanitize a signed offset.
	 *
	 * @param mixed $value Offset.
	 * @return int
	 */
	public function sanitize_offset( $value ): int {
		return max( -1000, min( 1000, (int) $value ) );
	}

	/**
	 * Validate a render shortcode.
	 *
	 * @param mixed $value Shortcode.
	 * @return bool
	 */
	public function validate_shortcode( $value ): bool {
		return is_string( $value ) && Registry::is_single_shortcode( trim( $value ) );
	}
}
