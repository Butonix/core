<?php

namespace WPMailSMTP\Pro\Providers;

use WPMailSMTP\Debug;
use WPMailSMTP\Pro\Providers\AmazonSES\Auth as SESAuth;
use WPMailSMTP\Options;
use WPMailSMTP\Pro\Providers\Outlook\Auth as MSAuth;
use WPMailSMTP\WP;

/**
 * Class Providers to add Pro providers.
 *
 * @since 1.5.0
 */
class Providers {

	/**
	 * Providers constructor.
	 *
	 * @since 1.5.0
	 */
	public function __construct() {

		$this->init();
	}

	/**
	 * WordPress related hooks.
	 *
	 * @since 1.5.0
	 */
	public function init() {

		add_filter( 'wp_mail_smtp_providers_loader_get_providers', array( $this, 'inject_providers' ) );

		add_action( 'load-index.php', array( $this, 'process_auth_code' ) );

		add_action( 'wp_mail_smtp_admin_area_enqueue_assets', array( $this, 'enqueue_assets' ) );

		add_action( 'wp_ajax_wp_mail_smtp_pro_providers_ajax', array( $this, 'process_ajax' ) );
	}

	/**
	 * Inject own Pro providers.
	 *
	 * @since 1.5.0
	 *
	 * @param array $providers
	 *
	 * @return array
	 */
	public function inject_providers( $providers ) {
		/*
		 * PHP 5.3 compatible approach that is used in core plugin.
		 * Ideally it should be WPMailSMTP\Pro\Providers\Outlook::class.
		 */
		$providers['amazonses'] = 'WPMailSMTP\Pro\Providers\AmazonSES\\';
		$providers['outlook']   = 'WPMailSMTP\Pro\Providers\Outlook\\';

		return $providers;
	}

	/**
	 * Complete the auth process for the Provider.
	 * Currently used for Microsoft Outlook only.
	 *
	 * @since 1.5.0
	 */
	public function process_auth_code() {

		// Only super admins can do that.
		if ( ! is_super_admin() ) {
			return;
		}

		// Ajax is not supported.
		if ( WP::is_doing_ajax() ) {
			return;
		}

		// We should be coming from somewhere.
		if ( empty( $_SERVER['HTTP_REFERER'] ) ) {
			return;
		}

		// We should have a required GET data.
		if (
			! isset( $_GET['code'] ) ||
			! isset( $_GET['state'] )
		) {
			return;
		}

		$state = sanitize_key( $_GET['state'] );
		$code  = preg_replace( '/[^a-zA-Z0-9_\-.]/', '', $_GET['code'] ); // phpcs:ignore

		$auth = new MSAuth();

		if ( ! wp_verify_nonce( $state, $auth->state_key ) ) {
			$url = add_query_arg(
				'error',
				'microsoft_no_code',
				wp_mail_smtp()->get_admin()->get_admin_page_url()
			);
		} else {
			// Save the code.
			$auth->process_auth( $code );

			$url = add_query_arg(
				'success',
				'microsoft_site_linked',
				wp_mail_smtp()->get_admin()->get_admin_page_url()
			);
		}

		wp_safe_redirect( $url );
		exit;
	}

	/**
	 * Inject Pro features specific assets: CSS & JS.
	 *
	 * @since 1.5.0
	 */
	public function enqueue_assets() {

		// CSS.
		\wp_enqueue_style(
			'wp-mail-smtp-admin-pro-settings',
			\wp_mail_smtp()->pro->assets_url . '/css/smtp-pro-settings.min.css',
			array( 'wp-mail-smtp-admin' ),
			WPMS_PLUGIN_VER,
			false
		);

		/*
		 * JavaScript.
		 */
		\wp_enqueue_script(
			'wp-mail-smtp-admin-pro-settings',
			\wp_mail_smtp()->pro->assets_url . '/js/smtp-pro-settings' . WP::asset_min() . '.js',
			array( 'jquery' ),
			WPMS_PLUGIN_VER,
			false
		);

		\wp_localize_script(
			'wp-mail-smtp-admin-pro-settings',
			'wp_mail_smtp_pro',
			array(
				'ses_text_sending'       => esc_html__( 'Sending...', 'wp-mail-smtp-pro' ),
				'ses_text_sent'          => esc_html__( 'Sent', 'wp-mail-smtp-pro' ),
				'ses_text_resend'        => esc_html__( 'Resend', 'wp-mail-smtp-pro' ),
				'ses_text_email_delete'  => esc_html__( 'Are you sure you want to delete this email address? You will need to add and verify it again if you want to use it in the future.', 'wp-mail-smtp-pro' ),
				'ses_text_smth_wrong'    => esc_html__( 'Something went wrong, please reload the page and try again.', 'wp-mail-smtp-pro' ),
				'ses_text_email_invalid' => esc_html__( 'Please make sure that the email address is valid.', 'wp-mail-smtp-pro' ),
			)
		);
	}

	/**
	 * Process AJAX requests fired by a pro version of a plugin and related to providers.
	 * Currently, only AmazonSES has some AJAX.
	 * So we will hard-code this behavior for now.
	 *
	 * @since 1.5.0
	 */
	public function process_ajax() {

		$generic_error = esc_html__( 'Something went wrong. Please try again later.', 'wp-mail-smtp-pro' );

		// Verify nonce existence.
		if ( ! isset( $_POST['nonce'] ) ) {
			wp_send_json_error( $generic_error );
		}

		$mailer = isset( $_POST['mailer'] ) ? sanitize_key( $_POST['mailer'] ) : '';

		if ( $mailer !== 'amazonses' ) {
			wp_send_json_error( $generic_error );
		}

		$task = isset( $_POST['task'] ) ? sanitize_key( $_POST['task'] ) : '';

		switch ( $task ) {
			case 'email_add':
				if ( ! wp_verify_nonce( $_POST['nonce'], 'wp_mail_smtp_pro_amazonses_email_add' ) ) { // phpcs:ignore
					wp_send_json_error( $generic_error );
				}

				$email = isset( $_POST['email'] ) ? sanitize_text_field( wp_unslash( $_POST['email'] ) ) : '';

				// We better have a valid email address.
				if ( ! is_email( $email ) ) {
					wp_send_json_error( esc_html__( 'Please provide a valid email address.', 'wp-mail-smtp-pro' ) );
				}

				$ses = new SESAuth();

				if ( $ses->do_verify_email( $email ) === true ) {
					$options = new Options();
					$all_opt = $options->get_all();

					$all_opt[ $mailer ]['emails_pending'][] = $email;
					$all_opt[ $mailer ]['emails_pending']   = array_unique( $all_opt[ $mailer ]['emails_pending'] );

					$options->set( $all_opt );

					wp_send_json_success(
						sprintf(
							wp_kses(
								/* translators: %s - email address. */
								__( 'Please check inbox of <code>%s</code> address for a verification email.', 'wp-mail-smtp-pro' ),
								array(
									'code' => array(),
								)
							),
							esc_html( $email )
						)
					);
				} else {
					$error = Debug::get_last();
					Debug::clear();

					wp_send_json_error(
						esc_html( $error )
					);
				}

				break;

			case 'email_delete':
				if ( ! wp_verify_nonce( $_POST['nonce'], 'wp_mail_smtp_pro_amazonses_email_delete' ) ) { // phpcs:ignore
					wp_send_json_error( $generic_error );
				}

				$email = isset( $_POST['email'] ) ? sanitize_text_field( wp_unslash( $_POST['email'] ) ) : '';

				if ( ! is_email( $email ) ) {
					wp_send_json_error( esc_html__( 'Please provide a valid email address.', 'wp-mail-smtp-pro' ) );
				}

				$ses = new SESAuth();

				if ( $ses->do_delete_verified_email( $email ) === true ) {
					wp_send_json_success(
						sprintf(
							wp_kses(
								/* translators: %s - email address. */
								__( 'Email address <code>%s</code> was successfully deleted.', 'wp-mail-smtp-pro' ),
								array(
									'code' => array(),
								)
							),
							esc_html( $email )
						)
					);
				} else {
					$error = Debug::get_last();
					Debug::clear();

					wp_send_json_error(
						esc_html( $error )
					);
				}

				break;

			case 'email_resend_delete':
				if ( ! wp_verify_nonce( $_POST['nonce'], 'wp_mail_smtp_pro_amazonses_email_resend_delete' ) ) { // phpcs:ignore
					wp_send_json_error( $generic_error );
				}

				$email = isset( $_POST['email'] ) ? sanitize_text_field( wp_unslash( $_POST['email'] ) ) : '';

				// We better have a valid email address.
				if ( ! is_email( $email ) ) {
					wp_send_json_error( esc_html__( 'Please provide a valid email address.', 'wp-mail-smtp-pro' ) );
				}

				$options = new Options();
				$all_opt = $options->get_all();

				if ( in_array( $email, $all_opt[ $mailer ]['emails_pending'], true ) ) {
					// Remove this email address.
					$all_opt[ $mailer ]['emails_pending'] = array_diff( $all_opt[ $mailer ]['emails_pending'], array( $email ) );

					$options->set( $all_opt );

					wp_send_json_success(
						esc_html__( 'Email address was successfully deleted.', 'wp-mail-smtp-pro' )
					);
				} else {
					$error = Debug::get_last();
					Debug::clear();

					wp_send_json_error(
						esc_html( $error )
					);
				}

				break;
		}

	}
}
