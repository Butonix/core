<?php

namespace WPMailSMTP\Pro\Providers\AmazonSES;

use WPMailSMTP\Debug;
use WPMailSMTP\Providers\MailerAbstract;
use WPMailSMTP\Pro\Providers\AmazonSES\SES\SimpleEmailServiceMessage;

/**
 * Class Mailer implements Mailer functionality.
 *
 * @since 1.5.0
 */
class Mailer extends MailerAbstract {

	/**
	 * @since 1.5.0
	 *
	 * @var SimpleEmailServiceMessage
	 */
	protected $ses_message;

	/**
	 * URL to make an API request to.
	 * Not really used for Amazon SES.
	 * Default: AWS_US_EAST_1.
	 *
	 * @since 1.5.0
	 *
	 * @var string
	 */
	protected $url = 'https://email.us-east-1.amazonaws.com';

	/**
	 * Mailer constructor.
	 *
	 * @since 1.5.0
	 *
	 * @param \WPMailSMTP\MailCatcher $phpmailer
	 */
	public function __construct( $phpmailer ) {

		$this->ses_message = new SimpleEmailServiceMessage();

		// We want to prefill everything from \WPMailSMTP\MailCatcher class, which extends \PHPMailer.
		parent::__construct( $phpmailer );
	}

	/**
	 * PHPMailer generates certain headers, including our custom.
	 * We want to preserve them when sending an email.
	 * Thus we need to custom process them and add properly.
	 *
	 * @see https://docs.aws.amazon.com/ses/latest/DeveloperGuide/header-fields.html
	 *
	 * @since 1.5.0
	 *
	 * @param array $headers
	 */
	public function set_headers( $headers ) {

		foreach ( $headers as $header ) {
			$name  = isset( $header[0] ) ? $header[0] : false;
			$value = isset( $header[1] ) ? $header[1] : false;

			if ( empty( $name ) || empty( $value ) ) {
				continue;
			}

			$this->ses_message->addCustomHeader( $name . ': ' . $value );
		}

		$this->ses_message->addCustomHeader( 'X-Mailer: WPMailSMTP/Mailer/' . $this->mailer . ' ' . WPMS_PLUGIN_VER );
	}

	/**
	 * Define the FROM (name and email).
	 *
	 * @since 1.5.0
	 *
	 * @param string $email From Email address.
	 * @param string $name  From Name.
	 */
	public function set_from( $email, $name = '' ) {

		if ( ! empty( $name ) ) {
			$this->ses_message->setFrom( $name . ' <' . $email . '>' );
		} else {
			$this->ses_message->setFrom( $email );
		}
	}

	/**
	 * Define the CC/BCC/TO (with names and emails).
	 *
	 * @see https://github.com/daniel-zahariev/php-aws-ses#recipients
	 *
	 * @since 1.5.0
	 *
	 * @param array $recipients
	 */
	public function set_recipients( $recipients ) {

		if ( empty( $recipients ) ) {
			return;
		}

		// Allow for now only these recipient types.
		$default = array( 'to', 'cc', 'bcc' );
		$data    = array();

		foreach ( $recipients as $type => $emails ) {
			if (
				! in_array( $type, $default, true ) ||
				empty( $emails ) ||
				! is_array( $emails )
			) {
				continue;
			}

			// Iterate over all emails for each type.
			// There might be multiple to/cc/bcc emails.
			foreach ( $emails as $email ) {
				$addr = isset( $email[0] ) ? $email[0] : false;
				$name = isset( $email[1] ) ? $email[1] : false;

				if ( ! is_email( $addr ) ) {
					continue;
				}

				$data[ $type ][] = ! empty( $name ) ? $name . ' <' . $addr . '>' : $addr;
			}
		}

		foreach ( $data as $type => $recipient ) {
			$this->ses_message->{'add' . $type }( $recipient );
		}
	}

	/**
	 * Set email subject.
	 *
	 * @since 1.5.0
	 *
	 * @param string $subject
	 */
	public function set_subject( $subject ) {

		$this->ses_message->setSubject( $subject );
	}

	/**
	 * Set the email content.
	 *
	 * @see https://github.com/daniel-zahariev/php-aws-ses#message-body
	 *
	 * @since 1.5.0
	 *
	 * @param array|string $content String when text/plain, array otherwise.
	 */
	public function set_content( $content ) {

		if ( empty( $content ) ) {
			return;
		}

		if ( is_array( $content ) ) {

			if (
				! isset( $content['text'] ) ||
				! isset( $content['html'] )
			) {
				return;
			}

			$this->ses_message->setMessageFromString( $content['text'], $content['html'] );
		} else {
			if ( $this->phpmailer->ContentType === 'text/plain' ) {
				$this->ses_message->setMessageFromString( $content );
			} else {
				$this->ses_message->setMessageFromString( '', $content );
			}
		}
	}

	/**
	 * Set Reply-To part of the message.
	 *
	 * @see https://github.com/daniel-zahariev/php-aws-ses#recipients
	 *
	 * @since 1.5.0
	 *
	 * @param array $reply_to
	 */
	public function set_reply_to( $reply_to ) {

		if ( empty( $reply_to ) ) {
			return;
		}

		$data = array();

		foreach ( $reply_to as $key => $emails ) {
			if (
				empty( $emails ) ||
				! is_array( $emails )
			) {
				continue;
			}

			$addr = isset( $emails[0] ) ? $emails[0] : false;
			$name = isset( $emails[1] ) ? $emails[1] : false;

			if ( ! is_email( $addr ) ) {
				continue;
			}

			$data[] = ! empty( $name ) ? $name . ' <' . $addr . '>' : $addr;
		}

		if ( ! empty( $data ) ) {
			$this->ses_message->addReplyTo( $data );
		}
	}

	/**
	 * Used for receiving bounce emails etc.
	 *
	 * @since 1.5.0
	 *
	 * @param string $email
	 */
	public function set_return_path( $email ) {

		$this->ses_message->setReturnPath( $email );
	}

	/**
	 * Add attachments to the message.
	 *
	 * @see https://github.com/daniel-zahariev/php-aws-ses#attachments
	 *
	 * @since 1.5.0
	 *
	 * @param array $attachments
	 */
	public function set_attachments( $attachments ) {

		if ( empty( $attachments ) ) {
			return;
		}

		foreach ( $attachments as $attachment ) {
			/*
			 * We are not using WP_Filesystem API as we can't reliably work with it.
			 * It is not always available, same as credentials for FTP.
			 */
			try {
				$this->ses_message->addAttachmentFromFile( $attachment[2], $attachment[0], $attachment[4] );
			} catch ( \Exception $e ) {
				// Do nothing in case of an error.
			}
		}
	}

	/**
	 * Not used.
	 *
	 * @since 1.5.0
	 */
	public function get_body() {
		$this->ses_message->getRawMessage( false );
	}
	/**
	 * Not used.
	 *
	 * @since 1.5.0
	 */
	public function get_headers() {
		$this->ses_message->getRawMessage( false );
	}

	/**
	 * Send the email.
	 *
	 * @since 1.5.0
	 */
	public function send() {

		$auth = new Auth();

		if ( $this->ses_message->validate() ) {
			$response = $auth->get_client()->sendEmail( $this->ses_message, true, false );

			$this->process_response( $response );
		} else {
			$this->process_response( array( 'error' => 'Message failed validation.' ) );
		}
	}

	/**
	 * Check the correct output of the response.
	 *
	 * @since 1.5.0
	 *
	 * @param array|false $response An array containing the unique identifier for this message and a separate request id (or errors codes and message). False if the provided message is missing any required fields.
	 */
	protected function process_response( $response ) {

		$this->response = $response;

		$error = '';

		if ( $this->response === false ) {
			$error = esc_html__( 'Just sent email is missing some required fields.', 'wp-mail-smtp-pro' );
		}

		if ( is_object( $this->response ) && isset( $response->error ) ) {
			$error = wp_json_encode( isset( $this->response->error['Error'] ) ? $this->response->error['Error'] : $this->response->error );
		}

		if ( is_array( $this->response ) && ! isset( $response['MessageId'] ) ) {
			$error = esc_html__( 'Something went wrong. Please try again.', 'wp-mail-smtp-pro' );
		}

		// Save the error text.
		if ( ! empty( $error ) ) {
			Debug::set(
				'Mailer: ' . esc_html( wp_mail_smtp()->get_providers()->get_options( $this->mailer )->get_title() ) . "\r\n" .
				$error
			);
		}
	}

	/**
	 * Whether the email was successfully sent.
	 *
	 * @since 1.5.0
	 *
	 * @return bool
	 */
	public function is_email_sent() {

		$is_sent = false;

		if ( is_array( $this->response ) && ! empty( $this->response['MessageId'] ) ) {
			$is_sent = true;

			Debug::clear();
		}

		return apply_filters( 'wp_mail_smtp_providers_mailer_is_email_sent', $is_sent, $this->mailer );
	}

	/**
	 * Get mailer debug information, that is helpful during support.
	 *
	 * @since 1.5.0
	 *
	 * @return string
	 */
	public function get_debug_info() {

		$mg_text = array();

		$auth = new Auth();

		$mg_text[] = '<strong>Access Key ID/Secret:</strong> ' . ( $auth->is_clients_saved() ? 'Yes' : 'No' );

		return implode( '<br>', $mg_text );
	}

	/**
	 * Whether the mailer has all its settings correctly set up and saved.
	 *
	 * @since 1.5.0
	 *
	 * @return bool
	 */
	public function is_mailer_complete() {

		if ( ! $this->is_php_compatible() ) {
			return false;
		}

		$auth = new Auth();

		if (
			$auth->is_clients_saved() &&
			! $auth->is_auth_required()
		) {
			return true;
		}

		return false;
	}
}
