<?php

namespace WPMailSMTP\Pro\Providers\AmazonSES;

use WPMailSMTP\Debug;
use WPMailSMTP\Options as PluginOptions;
use WPMailSMTP\Pro\Providers\AmazonSES\SES\SimpleEmailService;
use WPMailSMTP\Providers\AuthAbstract;

/**
 * Class Auth
 *
 * @since 1.5.0
 */
class Auth extends AuthAbstract {

	/**
	 * Auth constructor.
	 *
	 * @since 1.5.0
	 */
	public function __construct() {

		$options           = new PluginOptions();
		$this->mailer_slug = $options->get( 'mail', 'mailer' );

		if ( $this->mailer_slug !== Options::SLUG ) {
			return;
		}

		$this->options = $options->get_group( $this->mailer_slug );

		$this->get_client();
	}

	/**
	 * Get the list of supported AWS regions.
	 *
	 * @since 1.5.0
	 *
	 * @return array
	 */
	public static function get_regions_names() {

		return array(
			SimpleEmailService::AWS_US_EAST_1 => esc_html__( 'US East (N. Virginia)', 'wp-mail-smtp-pro' ),
			SimpleEmailService::AWS_US_WEST_2 => esc_html__( 'US West (Oregon)', 'wp-mail-smtp-pro' ),
			SimpleEmailService::AWS_EU_WEST1  => esc_html__( 'EU (Ireland)', 'wp-mail-smtp-pro' ),
			// SimpleEmailService::AWS_EU_CENTRAL_1   => esc_html__( 'EU (Frankfurt)', 'wp-mail-smtp-pro' ),
			// SimpleEmailService::AWS_AP_SOUTH_1     => esc_html__( 'Asia Pacific (Mumbai)', 'wp-mail-smtp-pro' ),
			// SimpleEmailService::AWS_AP_SOUTHEAST_1 => esc_html__( 'Asia Pacific (Sydney)', 'wp-mail-smtp-pro' ),
		);
	}

	/**
	 * Get the list of supported AWS regions coordinates.
	 *
	 * @since 1.5.0
	 *
	 * @return array
	 */
	public static function get_regions_coordinates() {

		return array(
			SimpleEmailService::AWS_US_EAST_1      => array(
				'lat' => 38.837392,
				'lon' => - 77.447313,
			),
			SimpleEmailService::AWS_US_WEST_2      => array(
				'lat' => 45.3573,
				'lon' => - 122.6068,
			),
			SimpleEmailService::AWS_EU_WEST1       => array(
				'lat' => 53.305494,
				'lon' => - 7.737649,
			),
			SimpleEmailService::AWS_EU_CENTRAL_1   => array(
				'lat' => 50.1109,
				'lon' => 8.6821,
			),
			SimpleEmailService::AWS_AP_SOUTH_1     => array(
				'lat' => 19.0760,
				'lon' => 72.8777,
			),
			SimpleEmailService::AWS_AP_SOUTHEAST_1 => array(
				'lat' => 33.8688,
				'lon' => 151.2093,
			),
		);
	}

	/**
	 * Init and get SES object to work with.
	 *
	 * @since 1.5.0
	 *
	 * @return SimpleEmailService
	 */
	public function get_client() {

		// Doesn't load client twice + gives ability to overwrite.
		if ( ! empty( $this->client ) ) {
			return $this->client;
		}

		$this->client = new SimpleEmailService( $this->options['client_id'], $this->options['client_secret'], $this->options['region'] );

		return $this->client;
	}

	/**
	 * Send a request to get the list of verified emails.
	 *
	 * @since 1.5.0
	 *
	 * @return array
	 */
	public function get_verified_emails() {

		set_error_handler( array( $this, 'ses_error_handler' ) ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_set_error_handler

		$data   = $this->get_client()->listVerifiedEmailAddresses();
		$emails = array();

		if ( isset( $data['Addresses'] ) ) {
			$emails = $data['Addresses'];
			Debug::clear();
		}

		return $emails;
	}

	/**
	 * Send a request to verify an email address.
	 *
	 * @since 1.5.0
	 *
	 * @param string $email Email address to verify.
	 *
	 * @return bool
	 */
	public function do_verify_email( $email ) {

		set_error_handler( array( $this, 'ses_error_handler' ) ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_set_error_handler

		$response = $this->get_client()->verifyEmailAddress( $email );

		if (
			is_array( $response ) &&
			isset( $response['RequestId'] )
		) {
			Debug::clear();

			return true;
		}

		return false;
	}

	/**
	 * Send a request to delete a verified email address.
	 *
	 * @since 1.5.0
	 *
	 * @param string $email Email address to remove.
	 *
	 * @return bool
	 */
	public function do_delete_verified_email( $email ) {

		set_error_handler( array( $this, 'ses_error_handler' ) ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_set_error_handler

		$response = $this->get_client()->deleteVerifiedEmailAddress( $email );

		if (
			is_array( $response ) &&
			isset( $response['RequestId'] )
		) {
			Debug::clear();

			return true;
		}

		return false;
	}

	/**
	 * Custom handling of errors generated in SES library.
	 *
	 * @since 1.5.0
	 *
	 * @param string $errno
	 * @param string $errstr
	 * @param string $errfile
	 * @param string $errline
	 * @param string $errcontext
	 *
	 * @return bool
	 */
	public function ses_error_handler( $errno, $errstr, $errfile, $errline, $errcontext ) {

		if ( ! ( error_reporting() & $errno ) ) { // phpcs:ignore
			// This error code is not included in error_reporting,
			// so let it fall through to the standard PHP error handler.
			return false;
		}

		$error = explode( "\n", $errstr );

		// Record the error essential text.
		Debug::set( $error[0] );

		// Don't execute PHP internal error handler.
		return true;
	}

	/**
	 * AmazonSES requires a selected region AND both keys.
	 *
	 * @since 1.5.0
	 *
	 * @return bool
	 */
	public function is_connection_ready() {

		return $this->is_clients_saved() && ! empty( $this->options['region'] );
	}

	/**
	 * Whether we should to perform an extra auth step.
	 *
	 * @since 1.5.0
	 *
	 * @return bool
	 */
	public function is_auth_required() {

		return false;
	}
}
