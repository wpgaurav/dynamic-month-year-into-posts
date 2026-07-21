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
use DMYIP\Date\DateRenderer;

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

		if ( ! file_exists( $this->plugin_dir . 'build/index.js' ) ) {
			return;
		}

		wp_enqueue_script(
			'dmyip-editor',
			$this->plugin_url . 'build/index.js',
			$asset['dependencies'],
			$asset['version'],
			true
		);

		wp_set_script_translations( 'dmyip-editor', 'dynamic-month-year-into-posts' );

		// Editor styles.
		if ( file_exists( $this->plugin_dir . 'build/index.css' ) ) {
			wp_enqueue_style(
				'dmyip-editor',
				$this->plugin_url . 'build/index.css',
				[],
				$asset['version']
			);
		}

		$renderer = new DateRenderer();

		// Pass current server-rendered values to the Block Bindings editor source.
		wp_localize_script(
			'dmyip-editor',
			'dmyipEditorData',
			[
				'bindingValues' => [
					'year'        => $renderer->render( 'year' ),
					'month'       => $renderer->render( 'month' ),
					'date'        => $renderer->render( 'date' ),
					'monthyear'   => $renderer->render( 'monthyear' ),
					'weekday'     => $renderer->render( 'weekday' ),
					'blackfriday' => $renderer->render( 'blackfriday' ),
				],
			]
		);
	}
}
