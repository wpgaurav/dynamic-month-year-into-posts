<?php
/**
 * License Manager for Dynamic Month & Year into Posts.
 *
 * Handles license activation, deactivation, verification, and auto-updates
 * via FluentCart Pro licensing API on gauravtiwari.org.
 *
 * @package DMYIP
 */

declare(strict_types=1);

namespace DMYIP;

/**
 * License Manager class.
 */
class LicenseManager {

	/**
	 * License server URL.
	 */
	private const LICENSE_SERVER = 'https://gauravtiwari.org/';

	/**
	 * FluentCart product ID (update after creating the product).
	 */
	private const ITEM_ID = 0;

	/**
	 * wp_options key for storing license data.
	 */
	private const OPTION_KEY = 'dmyip_license';

	/**
	 * wp_options key for last remote verification timestamp.
	 */
	private const LAST_CHECK_KEY = 'dmyip_license_last_check';

	/**
	 * Transient key for update check cache.
	 */
	private const UPDATE_TRANSIENT = 'dmyip_update_info';

	/**
	 * Plugin file path.
	 *
	 * @var string
	 */
	private string $plugin_file;

	/**
	 * Plugin basename.
	 *
	 * @var string
	 */
	private string $plugin_basename;

	/**
	 * Constructor.
	 *
	 * @param string $plugin_file Main plugin file path.
	 */
	public function __construct( string $plugin_file ) {
		$this->plugin_file     = $plugin_file;
		$this->plugin_basename = plugin_basename( $plugin_file );
	}

	/**
	 * Register all hooks.
	 *
	 * @return void
	 */
	public function register(): void {
		// Admin page.
		add_action( 'admin_menu', array( $this, 'add_submenu_page' ), 99 );
		add_action( 'admin_init', array( $this, 'handle_license_actions' ) );
		add_action( 'admin_notices', array( $this, 'admin_notices' ) );

		// Auto-updater.
		add_filter( 'pre_set_site_transient_update_plugins', array( $this, 'check_for_update' ) );
		add_filter( 'plugins_api', array( $this, 'plugin_info' ), 10, 3 );
		add_action( 'delete_site_transient_update_plugins', array( $this, 'clear_update_transient' ) );

		// Plugin row meta.
		add_filter( 'plugin_action_links_' . $this->plugin_basename, array( $this, 'plugin_action_links' ) );

		// Weekly remote license verification.
		if ( ! wp_next_scheduled( 'dmyip_verify_license' ) ) {
			wp_schedule_event( time(), 'weekly', 'dmyip_verify_license' );
		}
		add_action( 'dmyip_verify_license', array( $this, 'verify_remote_license' ) );
	}

	/**
	 * Add License submenu page.
	 *
	 * @return void
	 */
	public function add_submenu_page(): void {
		add_options_page(
			__( 'Dynamic Month & Year License', 'dynamic-month-year-into-posts' ),
			__( 'DMYIP License', 'dynamic-month-year-into-posts' ),
			'manage_options',
			'dmyip-license',
			array( $this, 'render_license_page' )
		);
	}

	/**
	 * Handle license activation/deactivation form submissions.
	 *
	 * @return void
	 */
	public function handle_license_actions(): void {
		if ( ! isset( $_POST['dmyip_license_action'] ) || ! current_user_can( 'manage_options' ) ) {
			return;
		}

		check_admin_referer( 'dmyip_license_nonce', 'dmyip_license_nonce' );

		$action = sanitize_text_field( $_POST['dmyip_license_action'] );

		if ( 'activate' === $action ) {
			$key = sanitize_text_field( trim( $_POST['license_key'] ?? '' ) );
			if ( empty( $key ) ) {
				add_settings_error( 'dmyip_license', 'empty_key', __( 'Please enter a license key.', 'dynamic-month-year-into-posts' ), 'error' );
				return;
			}
			$result = $this->activate_license( $key );
			if ( is_wp_error( $result ) ) {
				add_settings_error( 'dmyip_license', 'activation_error', $result->get_error_message(), 'error' );
			} else {
				add_settings_error( 'dmyip_license', 'activated', __( 'License activated successfully.', 'dynamic-month-year-into-posts' ), 'success' );
			}
		} elseif ( 'deactivate' === $action ) {
			$result = $this->deactivate_license();
			if ( is_wp_error( $result ) ) {
				add_settings_error( 'dmyip_license', 'deactivation_error', $result->get_error_message(), 'error' );
			} else {
				add_settings_error( 'dmyip_license', 'deactivated', __( 'License deactivated successfully.', 'dynamic-month-year-into-posts' ), 'success' );
			}
		}
	}

	/**
	 * Activate a license key.
	 *
	 * @param string $key License key.
	 * @return array<string, string>|\WP_Error
	 */
	public function activate_license( string $key ) {
		$response = $this->api_request( 'activate_license', array(
			'license_key' => $key,
			'item_id'     => self::ITEM_ID,
			'site_url'    => home_url(),
		) );

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		if ( empty( $response['success'] ) || 'valid' !== ( $response['status'] ?? '' ) ) {
			$message = $response['message'] ?? __( 'License activation failed.', 'dynamic-month-year-into-posts' );
			return new \WP_Error( 'activation_failed', $message );
		}

		$license_data = array(
			'license_key'     => $key,
			'status'          => 'valid',
			'activation_hash' => $response['activation_hash'] ?? '',
			'expiration_date' => $response['expiration_date'] ?? 'lifetime',
			'product_title'   => $response['product_title'] ?? 'Dynamic Month & Year',
			'activated_at'    => current_time( 'mysql' ),
		);

		update_option( self::OPTION_KEY, $license_data );
		update_option( self::LAST_CHECK_KEY, time() );
		delete_transient( self::UPDATE_TRANSIENT );

		return $license_data;
	}

	/**
	 * Deactivate the current license.
	 *
	 * @return array<string, string>|\WP_Error
	 */
	public function deactivate_license() {
		$license = $this->get_license_data();

		if ( empty( $license['license_key'] ) ) {
			return new \WP_Error( 'no_license', __( 'No license key found.', 'dynamic-month-year-into-posts' ) );
		}

		$this->api_request( 'deactivate_license', array(
			'license_key' => $license['license_key'],
			'item_id'     => self::ITEM_ID,
			'site_url'    => home_url(),
		) );

		$default_data = array(
			'license_key'     => '',
			'status'          => 'inactive',
			'activation_hash' => '',
			'expiration_date' => '',
			'product_title'   => '',
			'activated_at'    => '',
		);

		update_option( self::OPTION_KEY, $default_data );
		delete_option( self::LAST_CHECK_KEY );
		delete_transient( self::UPDATE_TRANSIENT );

		return $default_data;
	}

	/**
	 * Verify license with remote server.
	 *
	 * @return void
	 */
	public function verify_remote_license(): void {
		$license = $this->get_license_data();

		if ( empty( $license['license_key'] ) || 'valid' !== ( $license['status'] ?? '' ) ) {
			return;
		}

		$params = array(
			'item_id'  => self::ITEM_ID,
			'site_url' => home_url(),
		);

		if ( ! empty( $license['activation_hash'] ) ) {
			$params['activation_hash'] = $license['activation_hash'];
		} else {
			$params['license_key'] = $license['license_key'];
		}

		$response = $this->api_request( 'check_license', $params );

		if ( is_wp_error( $response ) ) {
			return;
		}

		$remote_status = $response['status'] ?? 'invalid';

		if ( 'valid' !== $remote_status ) {
			$license['status'] = $remote_status;
			update_option( self::OPTION_KEY, $license );
		}

		update_option( self::LAST_CHECK_KEY, time() );
	}

	/**
	 * Check for plugin updates.
	 *
	 * @param object $transient_data Update transient data.
	 * @return object
	 */
	public function check_for_update( $transient_data ) {
		if ( empty( $transient_data->checked ) ) {
			return $transient_data;
		}

		$license = $this->get_license_data();
		if ( empty( $license['license_key'] ) || 'valid' !== ( $license['status'] ?? '' ) ) {
			return $transient_data;
		}

		$update_info = get_transient( self::UPDATE_TRANSIENT );

		if ( false === $update_info ) {
			$params = array(
				'item_id'  => self::ITEM_ID,
				'site_url' => home_url(),
			);

			if ( ! empty( $license['activation_hash'] ) ) {
				$params['activation_hash'] = $license['activation_hash'];
			} else {
				$params['license_key'] = $license['license_key'];
			}

			$update_info = $this->api_request( 'get_license_version', $params );

			if ( ! is_wp_error( $update_info ) ) {
				set_transient( self::UPDATE_TRANSIENT, $update_info, 12 * HOUR_IN_SECONDS );
			}
		}

		if ( is_wp_error( $update_info ) || empty( $update_info['new_version'] ) ) {
			return $transient_data;
		}

		if ( version_compare( $update_info['new_version'], Plugin::VERSION, '>' ) ) {
			$transient_data->response[ $this->plugin_basename ] = (object) array(
				'id'            => $this->plugin_basename,
				'slug'          => 'dynamic-month-year-into-posts',
				'plugin'        => $this->plugin_basename,
				'new_version'   => $update_info['new_version'],
				'url'           => $update_info['url'] ?? 'https://gauravtiwari.org/snippet/dynamic-month-year/',
				'package'       => $update_info['package'] ?? '',
				'icons'         => $update_info['icons'] ?? array(),
				'banners'       => $update_info['banners'] ?? array(),
				'tested'        => $update_info['tested'] ?? '',
				'requires_php'  => $update_info['requires_php'] ?? '7.4',
				'compatibility' => new \stdClass(),
			);
		}

		return $transient_data;
	}

	/**
	 * Plugin info for the WP updates modal.
	 *
	 * @param false|object|array $result Current result.
	 * @param string             $action API action.
	 * @param object             $args   Request args.
	 * @return false|object
	 */
	public function plugin_info( $result, $action, $args ) {
		if ( 'plugin_information' !== $action || 'dynamic-month-year-into-posts' !== ( $args->slug ?? '' ) ) {
			return $result;
		}

		$update_info = get_transient( self::UPDATE_TRANSIENT );
		if ( empty( $update_info ) || is_wp_error( $update_info ) ) {
			return $result;
		}

		return (object) array(
			'name'          => $update_info['name'] ?? 'Dynamic Month & Year into Posts',
			'slug'          => 'dynamic-month-year-into-posts',
			'version'       => $update_info['new_version'] ?? '',
			'author'        => '<a href="https://gauravtiwari.org">Gaurav Tiwari</a>',
			'homepage'      => $update_info['homepage'] ?? 'https://gauravtiwari.org/snippet/dynamic-month-year/',
			'download_link' => $update_info['package'] ?? '',
			'trunk'         => $update_info['trunk'] ?? '',
			'last_updated'  => $update_info['last_updated'] ?? '',
			'sections'      => $update_info['sections'] ?? array(),
			'banners'       => $update_info['banners'] ?? array(),
			'icons'         => $update_info['icons'] ?? array(),
			'requires'      => $update_info['requires'] ?? '6.0',
			'requires_php'  => $update_info['requires_php'] ?? '7.4',
			'tested'        => $update_info['tested'] ?? '',
		);
	}

	/**
	 * Clear update transient.
	 *
	 * @return void
	 */
	public function clear_update_transient(): void {
		delete_transient( self::UPDATE_TRANSIENT );
	}

	/**
	 * Add License link to plugin action links.
	 *
	 * @param array<string> $links Plugin action links.
	 * @return array<string>
	 */
	public function plugin_action_links( array $links ): array {
		$license_link = sprintf(
			'<a href="%s">%s</a>',
			admin_url( 'options-general.php?page=dmyip-license' ),
			__( 'License', 'dynamic-month-year-into-posts' )
		);
		array_unshift( $links, $license_link );
		return $links;
	}

	/**
	 * Show admin notice if license is not active.
	 *
	 * @return void
	 */
	public function admin_notices(): void {
		$screen = get_current_screen();
		if ( ! $screen ) {
			return;
		}

		// Only show on plugins page and DMYIP settings.
		if ( 'plugins' !== $screen->id && 'settings_page_dmyip-license' !== $screen->id ) {
			return;
		}

		if ( 'settings_page_dmyip-license' === $screen->id ) {
			return;
		}

		$license = $this->get_license_data();
		$status  = $license['status'] ?? 'inactive';

		if ( 'valid' === $status ) {
			return;
		}

		$license_url = admin_url( 'options-general.php?page=dmyip-license' );

		if ( 'expired' === $status ) {
			printf(
				'<div class="notice notice-warning"><p>%s <a href="%s">%s</a></p></div>',
				esc_html__( 'Your Dynamic Month & Year license has expired. Renew to continue receiving updates.', 'dynamic-month-year-into-posts' ),
				esc_url( $license_url ),
				esc_html__( 'Manage License', 'dynamic-month-year-into-posts' )
			);
		}
	}

	/**
	 * Render the license admin page.
	 *
	 * @return void
	 */
	public function render_license_page(): void {
		$license = $this->get_license_data();
		$status  = $license['status'] ?? 'inactive';
		$key     = $license['license_key'] ?? '';
		$expires = $license['expiration_date'] ?? '';

		settings_errors( 'dmyip_license' );
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Dynamic Month & Year License', 'dynamic-month-year-into-posts' ); ?></h1>

			<div class="card" style="max-width: 600px; margin-top: 20px;">
				<h2 style="margin-top: 0;"><?php esc_html_e( 'License Status', 'dynamic-month-year-into-posts' ); ?></h2>

				<?php if ( 'valid' === $status ) : ?>
					<div style="background: #d4edda; border: 1px solid #c3e6cb; padding: 12px 16px; border-radius: 4px; margin-bottom: 16px;">
						<strong style="color: #155724;">&#10003; <?php esc_html_e( 'License Active', 'dynamic-month-year-into-posts' ); ?></strong>
						<?php if ( $expires && 'lifetime' !== $expires ) : ?>
							<br><small><?php printf( esc_html__( 'Expires: %s', 'dynamic-month-year-into-posts' ), esc_html( $expires ) ); ?></small>
						<?php elseif ( 'lifetime' === $expires ) : ?>
							<br><small><?php esc_html_e( 'Lifetime license', 'dynamic-month-year-into-posts' ); ?></small>
						<?php endif; ?>
					</div>

					<form method="post">
						<?php wp_nonce_field( 'dmyip_license_nonce', 'dmyip_license_nonce' ); ?>
						<input type="hidden" name="dmyip_license_action" value="deactivate">
						<p><code style="font-size: 14px; padding: 4px 8px;"><?php echo esc_html( $this->mask_key( $key ) ); ?></code></p>
						<p><input type="submit" class="button" value="<?php esc_attr_e( 'Deactivate License', 'dynamic-month-year-into-posts' ); ?>"></p>
					</form>

				<?php elseif ( 'expired' === $status ) : ?>
					<div style="background: #fff3cd; border: 1px solid #ffc107; padding: 12px 16px; border-radius: 4px; margin-bottom: 16px;">
						<strong style="color: #856404;">&#9888; <?php esc_html_e( 'License Expired', 'dynamic-month-year-into-posts' ); ?></strong>
					</div>
					<p><a href="https://gauravtiwari.org/product/dynamic-month-year-into-posts/" class="button button-primary" target="_blank"><?php esc_html_e( 'Renew License', 'dynamic-month-year-into-posts' ); ?></a></p>
					<hr>
					<form method="post">
						<?php wp_nonce_field( 'dmyip_license_nonce', 'dmyip_license_nonce' ); ?>
						<input type="hidden" name="dmyip_license_action" value="activate">
						<p>
							<label for="license_key"><strong><?php esc_html_e( 'Or enter a new license key:', 'dynamic-month-year-into-posts' ); ?></strong></label><br>
							<input type="text" id="license_key" name="license_key" class="regular-text" style="margin-top: 4px;">
						</p>
						<p><input type="submit" class="button button-primary" value="<?php esc_attr_e( 'Activate License', 'dynamic-month-year-into-posts' ); ?>"></p>
					</form>

				<?php else : ?>
					<p><?php esc_html_e( 'Enter your license key to enable automatic updates and support.', 'dynamic-month-year-into-posts' ); ?></p>
					<form method="post">
						<?php wp_nonce_field( 'dmyip_license_nonce', 'dmyip_license_nonce' ); ?>
						<input type="hidden" name="dmyip_license_action" value="activate">
						<p>
							<label for="license_key"><strong><?php esc_html_e( 'License Key', 'dynamic-month-year-into-posts' ); ?></strong></label><br>
							<input type="text" id="license_key" name="license_key" class="regular-text" style="margin-top: 4px;">
						</p>
						<p><input type="submit" class="button button-primary" value="<?php esc_attr_e( 'Activate License', 'dynamic-month-year-into-posts' ); ?>"></p>
					</form>
					<hr>
					<p><small>
						<?php printf(
							esc_html__( 'Don\'t have a license? %sGet one here%s.', 'dynamic-month-year-into-posts' ),
							'<a href="https://gauravtiwari.org/product/dynamic-month-year-into-posts/" target="_blank">', '</a>'
						); ?>
					</small></p>
				<?php endif; ?>
			</div>
		</div>
		<?php
	}

	/**
	 * Get saved license data.
	 *
	 * @return array<string, string>
	 */
	public function get_license_data(): array {
		$defaults = array(
			'license_key'     => '',
			'status'          => 'inactive',
			'activation_hash' => '',
			'expiration_date' => '',
			'product_title'   => '',
			'activated_at'    => '',
		);

		$data = get_option( self::OPTION_KEY, array() );
		return is_array( $data ) ? wp_parse_args( $data, $defaults ) : $defaults;
	}

	/**
	 * Check if the license is valid.
	 *
	 * @return bool
	 */
	public function is_valid(): bool {
		$license = $this->get_license_data();
		return 'valid' === ( $license['status'] ?? '' );
	}

	/**
	 * Make API request to FluentCart license server.
	 *
	 * @param string               $action API action.
	 * @param array<string, mixed> $params Request parameters.
	 * @return array<string, mixed>|\WP_Error
	 */
	private function api_request( string $action, array $params = array() ) {
		$url = add_query_arg(
			array_merge( array( 'fluent-cart' => $action ), $params ),
			self::LICENSE_SERVER
		);

		$response = wp_remote_get( $url, array(
			'timeout'   => 15,
			'sslverify' => true,
		) );

		if ( is_wp_error( $response ) ) {
			return new \WP_Error( 'api_error', __( 'Could not connect to the license server.', 'dynamic-month-year-into-posts' ) );
		}

		$code = wp_remote_retrieve_response_code( $response );
		$body = json_decode( wp_remote_retrieve_body( $response ), true );

		if ( $code >= 400 || empty( $body ) ) {
			return new \WP_Error( 'api_error', $body['message'] ?? __( 'License server error.', 'dynamic-month-year-into-posts' ) );
		}

		return $body;
	}

	/**
	 * Mask a license key for display.
	 *
	 * @param string $key License key.
	 * @return string
	 */
	private function mask_key( string $key ): string {
		if ( strlen( $key ) <= 8 ) {
			return $key;
		}
		return substr( $key, 0, 4 ) . str_repeat( '*', strlen( $key ) - 8 ) . substr( $key, -4 );
	}
}
