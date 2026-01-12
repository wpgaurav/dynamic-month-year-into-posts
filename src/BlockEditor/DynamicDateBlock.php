<?php
/**
 * Dynamic Date block.
 *
 * @package DMYIP
 */

declare(strict_types=1);

namespace DMYIP\BlockEditor;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Dynamic Date Gutenberg block.
 */
class DynamicDateBlock {

	/**
	 * Plugin directory path.
	 *
	 * @var string
	 */
	private string $plugin_dir;

	/**
	 * Constructor.
	 *
	 * @param string $plugin_dir Plugin directory path.
	 */
	public function __construct( string $plugin_dir ) {
		$this->plugin_dir = $plugin_dir;
	}

	/**
	 * Register the block.
	 *
	 * @return void
	 */
	public function register(): void {
		add_action( 'init', [ $this, 'register_block' ] );
	}

	/**
	 * Register block type.
	 *
	 * @return void
	 */
	public function register_block(): void {
		// Register Dynamic Date block - uses render.php from block.json.
		$dynamic_date_dir = $this->plugin_dir . 'build/blocks/dynamic-date';

		if ( file_exists( $dynamic_date_dir . '/block.json' ) ) {
			register_block_type( $dynamic_date_dir );
		}

		// Register Countdown block (with Interactivity API).
		$countdown_dir = $this->plugin_dir . 'build/blocks/countdown';

		if ( file_exists( $countdown_dir . '/block.json' ) ) {
			register_block_type( $countdown_dir );
		}
	}
}
