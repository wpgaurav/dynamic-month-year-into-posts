<?php
/**
 * Block editor assets.
 *
 * @package DMYIP
 */

declare(strict_types=1);

namespace DMYIP\BlockEditor;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use DMYIP\Plugin;

/**
 * Block editor asset management.
 */
class Assets {

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
	 * Constructor.
	 *
	 * @param string $plugin_dir Plugin directory path.
	 * @param string $plugin_url Plugin directory URL.
	 */
	public function __construct( string $plugin_dir, string $plugin_url ) {
		$this->plugin_dir = $plugin_dir;
		$this->plugin_url = $plugin_url;
	}

	/**
	 * Register assets.
	 *
	 * @return void
	 */
	public function register(): void {
		add_action( 'enqueue_block_editor_assets', [ $this, 'enqueue_editor_assets' ] );
	}

	/**
	 * Enqueue block editor assets.
	 *
	 * @return void
	 */
	public function enqueue_editor_assets(): void {
		$asset_file = $this->plugin_dir . 'build/index.asset.php';

		if ( file_exists( $asset_file ) ) {
			$asset = require $asset_file;
		} else {
			$asset = [
				'dependencies' => [
					'wp-blocks',
					'wp-element',
					'wp-editor',
					'wp-components',
					'wp-plugins',
					'wp-edit-post',
					'wp-rich-text',
					'wp-block-editor',
					'wp-i18n',
					'wp-data',
				],
				'version'      => Plugin::VERSION,
			];
		}

		// Main editor script (built).
		if ( file_exists( $this->plugin_dir . 'build/index.js' ) ) {
			wp_enqueue_script(
				'dmyip-editor',
				$this->plugin_url . 'build/index.js',
				$asset['dependencies'],
				$asset['version'],
				true
			);

			wp_set_script_translations( 'dmyip-editor', 'dynamic-month-year-into-posts' );
		} else {
			// Fallback to legacy script.
			wp_enqueue_script(
				'dmyip-editor',
				$this->plugin_url . 'assets/js/editor.js',
				$asset['dependencies'],
				Plugin::VERSION,
				true
			);
		}

		// Editor styles.
		if ( file_exists( $this->plugin_dir . 'build/index.css' ) ) {
			wp_enqueue_style(
				'dmyip-editor',
				$this->plugin_url . 'build/index.css',
				[],
				$asset['version']
			);
		}

		// Pass data to JavaScript.
		wp_localize_script(
			'dmyip-editor',
			'dmyipData',
			[
				'currentYear'  => date_i18n( 'Y' ),
				'currentMonth' => date_i18n( 'F' ),
				'currentDate'  => date_i18n( 'F j, Y' ),
			]
		);
	}
}
