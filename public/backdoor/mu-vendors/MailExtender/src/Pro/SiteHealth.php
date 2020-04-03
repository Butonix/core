<?php

namespace WPMailSMTP\Pro;

use WPMailSMTP\Pro\Emails\Logs\EmailsCollection;

/**
 * Class SiteHealth adds the plugin status and information to the WP Site Health admin page.
 * Pro version specific tests and info.
 *
 * @since {VERSION}
 */
class SiteHealth {

	/**
	 * This URL is used to check whether the site can communicate with our servers
	 * to receive updated and check the validity of a license.
	 *
	 * @since {VERSION}
	 */
	const CONNECTION_PING_URL = 'https://wpmailsmtp.com/connection-test.php';

	/**
	 * Initialize the site heath functionality for pro plugin version specific tasks.
	 *
	 * @since {VERSION}
	 */
	public function init() {

		add_filter( 'site_status_tests', array( $this, 'register_pro_site_status_tests' ) );

		// The priority is set a bit lower, so the core plugin can register the core debug info fields.
		add_filter( 'debug_information', array( $this, 'register_pro_debug_information' ), 20 );

		// Register async test hooks.
		add_action( 'wp_ajax_health-check-pro-license_test', array( $this, 'pro_license_test' ) );
		add_action( 'wp_ajax_health-check-wpmailsmtpdotcom-communication_test', array( $this, 'wpmailsmtpdotcom_communication_test' ) );
	}

	/**
	 * Register tests.
	 * This will be displayed in the "Status" tab of the WP Site Health page.
	 *
	 * @since {VERSION}
	 *
	 * @param array $tests The array with all tests.
	 *
	 * @return array
	 */
	public function register_pro_site_status_tests( $tests ) {

		$tests['async']['wp_mail_smtp_pro_license_check'] = array(
			'label' => esc_html__( 'Is WP Mail SMTP Pro license active and valid?', 'wp-mail-smtp-pro' ),
			'test'  => 'pro_license_test',
		);

		$tests['async']['wp_mail_smtp_wpmailsmtpdotcom_communication'] = array(
			'label' => esc_html__( 'Is wpmailsmtp.com reachable?', 'wp-mail-smtp' ),
			'test'  => 'wpmailsmtpdotcom_communication_test',
		);

		if ( wp_mail_smtp()->pro->get_logs()->is_enabled() ) {
			$tests['direct']['wp_mail_smtp_db_tables_exist'] = array(
				'label' => esc_html__( 'Do WP Mail SMTP Pro DB tables exist?', 'wp-mail-smtp-pro' ),
				'test'  => array( $this, 'db_tables_test' ),
			);
		}

		return $tests;
	}

	/**
	 * Register debug information.
	 * This will be displayed in the "Info" tab of the WP Site Health page.
	 *
	 * @since {VERSION}
	 *
	 * @param array $debug_info Array of existing debug information.
	 *
	 * @return array
	 */
	public function register_pro_debug_information( $debug_info ) {

		$pro_fields = array();

		$db_tables = wp_mail_smtp()->pro->get_custom_db_tables();

		$pro_fields['db_tables'] = array(
			'label' => esc_html__( 'DB tables', 'wp-mail-smtp-pro' ),
			'value' => ! empty( $db_tables ) ?
				implode( ', ', $db_tables ) : esc_html__( 'No DB tables found.', 'wp-mail-smtp-pro' ),
		);

		if ( wp_mail_smtp()->pro->get_logs()->is_enabled() && wp_mail_smtp()->pro->get_logs()->is_valid_db() ) {
			/** @noinspection PhpLanguageLevelInspection */
			$pro_fields['email_log_entries'] = array(
				'label' => esc_html__( 'Email log entries', 'wp-mail-smtp-pro' ),
				'value' => ( new EmailsCollection() )->get_count(),
			);
		}

		$debug_info[ \WPMailSMTP\SiteHealth::DEBUG_INFO_SLUG ]['fields'] = array_merge(
			$debug_info[ \WPMailSMTP\SiteHealth::DEBUG_INFO_SLUG ]['fields'],
			$pro_fields
		);

		return $debug_info;
	}

	/**
	 * Perform the test (async) for checking and verifying the pro license.
	 *
	 * @since {VERSION}
	 */
	public function pro_license_test() {

		check_ajax_referer( 'health-check-site-status' );

		if ( ! current_user_can( 'view_site_health_checks' ) ) {
			wp_send_json_error();
		}

		$result = array(
			'label'       => esc_html__( 'WP Mail SMTP Pro license is active and valid', 'wp-mail-smtp-pro' ),
			'status'      => 'good',
			'badge'       => array(
				'label' => wp_mail_smtp()->get_site_health()->get_label(),
				'color' => \WPMailSMTP\SiteHealth::BADGE_COLOR,
			),
			'description' => '',
			'actions'     => sprintf(
				'<p><a href="%s">%s</a></p>',
				esc_url( wp_mail_smtp()->get_admin()->get_admin_page_url() ),
				esc_html__( 'View license setting', 'wp-mail-smtp-pro' )
			),
			'test'        => 'wp_mail_smtp_pro_license_check',
		);

		$license_status = wp_mail_smtp()->pro->get_license()->get_status();

		$result['description'] = $license_status['message'];

		if ( $license_status['valid'] === false ) {
			$result['label']          = esc_html__( 'WP Mail SMTP Pro license is invalid', 'wp-mail-smtp-pro' );
			$result['badge']['color'] = 'orange';
			$result['status']         = 'recommended';
		}

		wp_send_json_success( $result );
	}

	/**
	 * Perform the test (async) for checking if this WP site can communicate with wpmailsmtp.com.
	 *
	 * @since {VERSION}
	 */
	public function wpmailsmtpdotcom_communication_test() {

		check_ajax_referer( 'health-check-site-status' );

		if ( ! current_user_can( 'view_site_health_checks' ) ) {
			wp_send_json_error();
		}

		$result = array(
			'label'       => esc_html__( 'Your site can communicate with wpmailsmtp.com', 'wp-mail-smtp-pro' ),
			'status'      => 'good',
			'badge'       => array(
				'label' => wp_mail_smtp()->get_site_health()->get_label(),
				'color' => \WPMailSMTP\SiteHealth::BADGE_COLOR,
			),
			'description' => sprintf(
				'<p>%s</p>',
				esc_html__( 'Communicating with the wpmailsmtp.com servers is used to check for new plugin versions, and to verify license keys.', 'wp-mail-smtp-pro' )
			),
			'actions'     => '',
			'test'        => 'wp_mail_smtp_wpmailsmtpdotcom_communication',
		);

		$response = wp_remote_post( self::CONNECTION_PING_URL );

		if ( wp_remote_retrieve_response_code( $response ) !== 200 ) {
			$result['status']         = 'critical';
			$result['badge']['color'] = 'red';
			$result['label']          = esc_html__( 'Could not reach wpmailsmtp.com', 'wp-mail-smtp-pro' );

			$result['description'] .= sprintf(
				'<p>%s</p>',
				sprintf(
					'<span class="error"><span class="screen-reader-text">%s</span></span> %s',
					esc_html__( 'Error', 'wp-mail-smtp-pro' ),
					sprintf( /* translators: %s - The error returned by the lookup. */
						esc_html__( 'Your site is unable to reach wpmailsmtp.com, and returned the error: %s', 'wp-mail-smtp-pro' ),
						is_wp_error( $response ) ? $response->get_error_message() : wp_remote_retrieve_response_message( $response )
					)
				)
			);
		}

		wp_send_json_success( $result );
	}

	/**
	 * Perform the test for checking if all Pro plugin DB tables exist.
	 *
	 * @since {VERSION}
	 */
	public function db_tables_test() {

		global $wpdb;

		$tables = wp_mail_smtp()->pro->get_custom_db_tables();

		$missing_tables = array();

		foreach ( $tables as $table ) {
			if ( $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $table ) ) !== $table ) {
				$missing_tables[] = $table;
			}
		}

		$result = array(
			'label'       => esc_html__( 'WP Mail SMTP Pro DB tables are created', 'wp-mail-smtp-pro' ),
			'status'      => 'good',
			'badge'       => array(
				'label' => wp_mail_smtp()->get_site_health()->get_label(),
				'color' => \WPMailSMTP\SiteHealth::BADGE_COLOR,
			),
			'description' => esc_html__( 'WP Mail SMTP Pro has Email Log functionality, that allows storing all sent emails in the custom database table. In order to work properly, the custom tables should be created, and it seems the option is enabled and custom tables are properly created.', 'wp-mail-smtp-pro' ),
			'actions'     => '',
			'test'        => 'wp_mail_smtp_db_tables_exist',
		);

		if ( ! empty( $missing_tables ) ) {
			$result['label']          = esc_html__( 'WP Mail SMTP Pro DB tables check has failed', 'wp-mail-smtp-pro' );
			$result['status']         = 'critical';
			$result['badge']['color'] = 'red';
			$result['description']    = sprintf(
				'<p>%s</p><p>%s</p>',
				sprintf( /* translators: %s - the list of missing tables separated by a comma. */
					esc_html( _n( 'Missing table: %s', 'Missing tables: %s', count( $missing_tables ), 'wp-mail-smtp-pro' ) ),
					esc_html( implode( ', ', $missing_tables ) )
				),
				esc_html__( 'WP Mail SMTP Pro has Email Log functionality, that allows storing all sent emails in the custom database table. In order to work properly, the custom tables should be created, and it seems the option is enabled but tables are missing. Please try to re-install the WP Mail SMTP Pro plugin. If this issue persists, please contact our support.', 'wp-mail-smtp-pro' )
			);
		}

		return $result;
	}
}
