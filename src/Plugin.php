<?php
/**
 * Main plugin class.
 *
 * @package DMYIP
 */

declare(strict_types=1);

namespace DMYIP;

/**
 * Plugin bootstrap class.
 */
final class Plugin {

	/**
	 * Plugin version.
	 */
	public const VERSION = '1.6.2';

	/**
	 * Plugin instance.
	 *
	 * @var self|null
	 */
	private static ?self $instance = null;

	/**
	 * Plugin directory path.
	 *
	 * @var string
	 */
	private string $plugin_dir;

	/**
	 * Plugin directory URL.
	 *
	 * @var string
	 */
	private string $plugin_url;

	/**
	 * Get plugin instance.
	 *
	 * @param string $plugin_file Main plugin file path.
	 * @return self
	 */
	public static function instance( string $plugin_file ): self {
		if ( null === self::$instance ) {
			self::$instance = new self( $plugin_file );
		}
		return self::$instance;
	}

	/**
	 * Constructor.
	 *
	 * @param string $plugin_file Main plugin file path.
	 */
	private function __construct( string $plugin_file ) {
		$this->plugin_dir = plugin_dir_path( $plugin_file );
		$this->plugin_url = plugin_dir_url( $plugin_file );
	}

	/**
	 * Initialize the plugin.
	 *
	 * @return void
	 */
	public function init(): void {
		$this->register_shortcodes();
		$this->register_integrations();
		$this->register_block_editor();
		$this->register_rest_api();
		$this->register_cli();
		$this->register_block_bindings();
	}

	/**
	 * Register all shortcodes.
	 *
	 * @return void
	 */
	private function register_shortcodes(): void {
		( new Shortcodes\Year() )->register();
		( new Shortcodes\Month() )->register();
		( new Shortcodes\Day() )->register();
		( new Shortcodes\Date() )->register();
		( new Shortcodes\PostDate() )->register();
		( new Shortcodes\Events() )->register();
		( new Shortcodes\Countdown() )->register();
		( new Shortcodes\CoreFilters() )->register();
	}

	/**
	 * Register SEO plugin integrations.
	 *
	 * @return void
	 */
	private function register_integrations(): void {
		( new Integrations\RankMath() )->register();
		( new Integrations\Yoast() )->register();
		( new Integrations\SEOPress() )->register();
		( new Integrations\Elementor() )->register();
		( new Integrations\RelatedPosts() )->register();
	}

	/**
	 * Register block editor assets and patterns.
	 *
	 * @return void
	 */
	private function register_block_editor(): void {
		( new BlockEditor\Assets( $this->plugin_dir, $this->plugin_url ) )->register();
		( new BlockEditor\Patterns() )->register();
		( new BlockEditor\DynamicDateBlock( $this->plugin_dir ) )->register();
	}

	/**
	 * Register REST API endpoints.
	 *
	 * @return void
	 */
	private function register_rest_api(): void {
		( new REST\DatesEndpoint() )->register();
	}

	/**
	 * Register WP-CLI commands.
	 *
	 * @return void
	 */
	private function register_cli(): void {
		if ( defined( 'WP_CLI' ) && WP_CLI ) {
			( new CLI\Commands() )->register();
		}
	}

	/**
	 * Register block bindings source.
	 *
	 * @return void
	 */
	private function register_block_bindings(): void {
		( new BlockEditor\BlockBindings() )->register();
	}

	/**
	 * Get plugin directory path.
	 *
	 * @return string
	 */
	public function get_plugin_dir(): string {
		return $this->plugin_dir;
	}

	/**
	 * Get plugin directory URL.
	 *
	 * @return string
	 */
	public function get_plugin_url(): string {
		return $this->plugin_url;
	}
}
