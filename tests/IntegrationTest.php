<?php
/**
 * Real WordPress integration tests.
 *
 * @package DMYIP
 */

declare(strict_types=1);

namespace DMYIP\Tests;

use DMYIP\CLI\Commands;
use DMYIP\Shortcodes\Registry;

if ( ! class_exists( '\WP_UnitTestCase' ) ) {
	return;
}

/**
 * Verify runtime registration and scoped rendering inside WordPress.
 */
class IntegrationTest extends \WP_UnitTestCase {

	/**
	 * Verify all block types register on the supported WordPress floor.
	 */
	public function test_blocks_are_registered(): void {
		$registry = \WP_Block_Type_Registry::get_instance();

		$this->assertTrue( $registry->is_registered( 'dmyip/dynamic-date' ) );
		$this->assertTrue( $registry->is_registered( 'dmyip/countdown' ) );
		$this->assertTrue( $registry->is_registered( 'dmyip/published-date' ) );
		$this->assertTrue( $registry->is_registered( 'dmyip/modified-date' ) );
	}

	/**
	 * Verify unrelated shortcodes are not executed by scoped integrations.
	 */
	public function test_scoped_renderer_leaves_unrelated_shortcodes_untouched(): void {
		add_shortcode(
			'dmyip_test_side_effect',
			static function (): string {
				return 'executed';
			}
		);

		$output = Registry::render( '[year] [dmyip_test_side_effect]' );

		$this->assertStringContainsString( (string) current_time( 'Y' ), (string) $output );
		$this->assertStringContainsString( '[dmyip_test_side_effect]', (string) $output );
		$this->assertStringNotContainsString( 'executed', (string) $output );
	}

	/**
	 * Verify REST and WP-CLI hooks register in a real installation.
	 */
	public function test_public_api_and_shortcodes_are_registered(): void {
		$this->assertTrue( shortcode_exists( 'year' ) );
		$this->assertTrue( shortcode_exists( 'daysuntil' ) );
		$this->assertFalse( shortcode_exists( 'dynamic_date' ) );
		$this->assertFalse( shortcode_exists( 'nextoccurrence' ) );
		$this->assertFalse( shortcode_exists( 'daysuntilnext' ) );
		$this->assertFalse( shortcode_exists( 'occurrenceyear' ) );

		do_action( 'rest_api_init' );
		$routes = rest_get_server()->get_routes();
		$this->assertArrayHasKey( '/dmyip/v1/date/(?P<type>[a-z_]+)', $routes );
	}

	/**
	 * Verify the Countdown block retains a readable server fallback.
	 */
	public function test_countdown_has_server_rendered_fallback_text(): void {
		$blocks = parse_blocks(
			'<!-- wp:dmyip/countdown {"targetDate":"2000-01-01","recurring":true} /-->'
		);
		$output = render_block( $blocks[0] );

		$this->assertStringContainsString( 'data-wp-text="context.displayText"', $output );
		$this->assertMatchesRegularExpression( '/>\d+ days<\/span>/', $output );
	}

	/**
	 * Invalid countdown dates do not claim that zero days remain.
	 */
	public function test_invalid_countdown_date_renders_nothing(): void {
		$blocks = parse_blocks(
			'<!-- wp:dmyip/countdown {"targetDate":"not-a-date"} /-->'
		);

		$this->assertSame( '', render_block( $blocks[0] ) );
	}

	/**
	 * The default countdown label uses singular grammar when one day remains.
	 */
	public function test_countdown_uses_singular_default_label(): void {
		$tomorrow = current_datetime()->modify( '+1 day' )->format( 'Y-m-d' );
		$blocks   = parse_blocks(
			sprintf( '<!-- wp:dmyip/countdown {"targetDate":"%s"} /-->', $tomorrow )
		);

		$this->assertStringContainsString( '>1 day</span>', render_block( $blocks[0] ) );
	}

	/**
	 * WP-CLI accepts both documented shortcode input and renderer types.
	 */
	public function test_cli_accepts_shortcode_and_type_input(): void {
		$commands = new Commands();

		$this->assertSame( (string) current_time( 'Y' ), $commands->resolve_value( [ '[year]' ], [] ) );
		$this->assertSame( (string) current_time( 'Y' ), $commands->resolve_value( [ 'year' ], [] ) );
		$this->assertSame( '', $commands->resolve_value( [ '[gallery]' ], [] ) );
	}
}
