<?php

namespace WPMailSMTP\Pro\Providers\Outlook;

use WPMailSMTP\Providers\MailerAbstract;
use WPMailSMTP\Options as PluginOptions;
use WPMailSMTP\WP;

/**
 * Class Mailer implements Mailer functionality.
 *
 * @since 1.5.0
 */
class Mailer extends MailerAbstract {

	/**
	 * @since 1.5.0
	 *
	 * @var array
	 */
	protected $body = array(
		'message'         => array(),
		'saveToSentItems' => true,
	);

	/**
	 * Which response code from HTTP provider is considered to be successful?
	 *
	 * @since 1.5.0
	 *
	 * @var int
	 */
	protected $email_sent_code = 202;

	/**
	 * URL to make an API request to.
	 *
	 * @since 1.5.0
	 *
	 * @var string
	 */
	protected $url = 'https://graph.microsoft.com/v1.0/me/sendMail';

	/**
	 * Mailer constructor.
	 *
	 * @since 1.5.0
	 *
	 * @param \WPMailSMTP\MailCatcher $phpmailer
	 */
	public function __construct( $phpmailer ) {

		// Init the client that checks tokens and re-saves them if needed.
		new Auth();

		// We want to prefill everything from \WPMailSMTP\MailCatcher class, which extends \PHPMailer.
		parent::__construct( $phpmailer );

		$token = $this->options->get( $this->mailer, 'access_token' );

		if ( ! empty( $token['access_token'] ) ) {
			$this->set_header( 'Authorization', 'Bearer ' . $token['access_token'] );
		}
		$this->set_header( 'content-type', 'application/json' );
	}

	/**
	 * PhpMailer generates certain headers, including our custom.
	 * We want to preserve them when sending an email.
	 * Thus we need to custom process them and add to message body headers.
	 *
	 * @since 1.5.0
	 *
	 * @param array $headers
	 */
	public function set_headers( $headers ) {

		foreach ( $headers as $header ) {
			$name  = isset( $header[0] ) ? $header[0] : false;
			$value = isset( $header[1] ) ? $header[1] : false;

			$this->set_body_header( $name, $value );
		}

		// Add custom PHPMailer-specific header.
		$this->set_body_header( 'X-Mailer', 'WPMailSMTP/Mailer/' . $this->mailer . ' ' . WPMS_PLUGIN_VER );
	}

	/**
	 * MS Graph object is nested inside 'message'.
	 *
	 * @since 1.5.0
	 *
	 * @param array $param
	 */
	public function set_body_param( $param ) {

		$this->body['message'] = PluginOptions::array_merge_recursive( $this->body['message'], $param );
	}

	/**
	 * We are allowed to provide custom header for emails.
	 *
	 * @see   https://docs.microsoft.com/en-us/graph/api/resources/internetmessageheader?view=graph-rest-1.0
	 *
	 * @since 1.5.0
	 *
	 * @param string $name
	 * @param string $value
	 */
	public function set_body_header( $name, $value ) {

		$is_duplicated = false;

		$name = sanitize_text_field( $name );
		if ( empty( $name ) ) {
			return;
		}

		$headers = isset( $this->body['message']['internetMessageHeaders'] ) ? (array) $this->body['message']['internetMessageHeaders'] : array();

		// Do not allow duplicate names.
		foreach ( $headers as $header ) {

			if ( $header['name'] === $name ) {
				$is_duplicated = true;
			}
		}

		if ( $is_duplicated ) {
			return;
		}

		$headers[] = array(
			'name'  => $name,
			'value' => WP::sanitize_value( $value ),
		);

		$this->set_body_param(
			array(
				'internetMessageHeaders' => $headers,
			)
		);
	}

	/**
	 * Define the FROM (name and email) and SENDER.
	 * It doesn't support random email, should be the same as used for connection.
	 *
	 * @see   https://docs.microsoft.com/en-us/graph/api/resources/recipient?view=graph-rest-1.0
	 * @see   https://docs.microsoft.com/en-us/graph/outlook-send-mail-from-other-user
	 *
	 * @since 1.5.0
	 *
	 * @param string $email Not used.
	 * @param string $name  Not used.
	 */
	public function set_from( $email, $name = '' ) {

		$sender = $this->options->get( $this->mailer, 'user_details' );

		$this->set_body_param(
			array(
				'from' => array(
					'emailAddress' => array(
						'name'    => sanitize_text_field( $sender['display_name'] ),
						'address' => $sender['email'],
					),
				),
			)
		);

		$this->set_body_param(
			array(
				'sender' => array(
					'emailAddress' => array(
						'name'    => sanitize_text_field( $sender['display_name'] ),
						'address' => $sender['email'],
					),
				),
			)
		);
	}

	/**
	 * Define the CC/BCC/TO (with names and emails).
	 *
	 * @see   https://docs.microsoft.com/en-us/graph/api/resources/recipient?view=graph-rest-1.0
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

			$type_id = $type . 'Recipients';

			$data[ $type_id ] = array();

			// Iterate over all emails for each type.
			// There might be multiple to/cc/bcc emails.
			foreach ( $emails as $email ) {
				$holder = array();
				$addr   = isset( $email[0] ) ? $email[0] : false;
				$name   = isset( $email[1] ) ? $email[1] : false;

				if ( ! filter_var( $addr, FILTER_VALIDATE_EMAIL ) ) {
					continue;
				}

				$holder['address'] = $addr;
				if ( ! empty( $name ) ) {
					$holder['name'] = $name;
				}

				array_push( $data[ $type_id ], array( 'emailAddress' => $holder ) );
			}
		}

		if ( ! empty( $data ) ) {
			foreach ( $data as $type_id => $type_data ) {
				$this->set_body_param(
					array(
						$type_id => $type_data,
					)
				);
			}
		}
	}

	/**
	 * Set the email content.
	 * MS Graph supports only 1 type at a time (no multipart emails),
	 * so in case of multipart we ignore the text/plain part.
	 *
	 * @see   https://docs.microsoft.com/en-us/graph/api/resources/itembody?view=graph-rest-1.0
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

			if ( ! empty( $content['html'] ) ) {
				$body = array(
					'contentType' => 'html',
					'content'     => $content['html'],
				);
			} else {
				$body = array(
					'contentType' => 'text',
					'content'     => $content['text'],
				);
			}

			$this->set_body_param(
				array(
					'body' => $body,
				)
			);
		} else {
			$body = array(
				'contentType' => 'html',
				'content'     => $content,
			);

			if ( $this->phpmailer->ContentType === 'text/plain' ) {
				$body['contentType'] = 'text';
			}

			$this->set_body_param(
				array(
					'body' => $body,
				)
			);
		}
	}

	/**
	 * Set Reply-To part of the message.
	 * This is not in email header, but in body>message.
	 *
	 * @see   https://docs.microsoft.com/en-us/graph/api/resources/recipient?view=graph-rest-1.0
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

			if ( ! filter_var( $addr, FILTER_VALIDATE_EMAIL ) ) {
				continue;
			}

			$holder = array();

			$holder['address'] = $addr;
			if ( ! empty( $name ) ) {
				$holder['name'] = $name;
			}

			$data[] = array( 'emailAddress' => $holder );
		}

		if ( ! empty( $data ) ) {
			$this->set_body_param(
				array(
					'replyTo' => $data,
				)
			);
		}
	}

	/**
	 * MS Graph doesn't support sender or return_path params.
	 * So we do nothing.
	 *
	 * @since 1.5.0
	 *
	 * @param string $from_email
	 */
	public function set_return_path( $from_email ) {
	}

	/**
	 * Add attachments to the body.
	 * MS Graph accepts an array of files content in body, so we will include all files and send.
	 * Doesn't handle exceeding the limits etc, as this is done and reported by MS Graph API via errors.
	 *
	 * @see   https://docs.microsoft.com/en-us/graph/api/resources/attachment?view=graph-rest-1.0
	 *
	 * @since 1.5.0
	 *
	 * @param array $attachments
	 */
	public function set_attachments( $attachments ) {

		if ( empty( $attachments ) ) {
			return;
		}

		$data = array();

		foreach ( $attachments as $attachment ) {
			$file = false;

			/*
			 * We are not using WP_Filesystem API as we can't reliably work with it.
			 * It is not always available, same as credentials for FTP.
			 */
			try {
				if ( is_file( $attachment[0] ) && is_readable( $attachment[0] ) ) {
					$file = file_get_contents( $attachment[0] ); // phpcs:ignore
				}
			}
			catch ( \Exception $e ) {
				$file = false;
			}

			if ( $file === false ) {
				continue;
			}

			$data[] = array(
				'@odata.type'  => '#microsoft.graph.fileAttachment',
				'name'         => $attachment[2],
				'contentBytes' => base64_encode( $file ),
				'contentType'  => $attachment[4],
			);
		}

		if ( ! empty( $data ) ) {
			$this->set_body_param(
				array(
					'hasAttachments' => true,
				)
			);
			$this->set_body_param(
				array(
					'attachments' => $data,
				)
			);
		}
	}

	/**
	 * Redefine the way email body is returned.
	 * Microsoft Graph needs JSON object.
	 *
	 * @see   https://docs.microsoft.com/en-us/graph/api/resources/message?view=graph-rest-1.0
	 *
	 * @since 1.5.0
	 *
	 * @return string
	 */
	public function get_body() {

		$body = $this->process_body_headers_unique( parent::get_body() );

		return wp_json_encode( $body );
	}

	/**
	 * Outlook doesn't allow duplicated headers in emails.
	 * This will make sure that no duplicates are available.
	 * The last duplicate header will be preserved, the 1st one will be removed.
	 *
	 * @since 1.5.0
	 *
	 * @param array $body Email body will all email-related headers and attachments.
	 *
	 * @return array
	 */
	protected function process_body_headers_unique( $body ) {

		$headers    = isset( $body['message']['internetMessageHeaders'] ) ? $body['message']['internetMessageHeaders'] : array();
		$to_process = array();

		// Get keys and header name for all headers, with duplicates.
		foreach ( $headers as $key => $header_outer ) {
			$to_process[ $key ] = $header_outer['name'];
		}

		if ( ! empty( $to_process ) ) {
			// With this double flipping we remove duplicates,
			// preserving the last define value with the biggest key.
			$to_process = array_flip( array_flip( $to_process ) );

			// Now get headers without duplicates.
			$headers = array_filter( $headers, function( $key ) use ( $to_process ) {
				return isset( $to_process[ $key ] );
			}, ARRAY_FILTER_USE_KEY );
		}

		// Reset keys in headers array and assign back to body.
		$body['message']['internetMessageHeaders'] = array_values( $headers );

		return $body;
	}

	/**
	 * Send the email using Microsoft Graph API.
	 *
	 * @see   https://docs.microsoft.com/en-us/graph/api/user-sendmail?view=graph-rest-1.0
	 *
	 * @since 1.5.0
	 */
	public function send() {

		$params = PluginOptions::array_merge_recursive(
			$this->get_default_params(),
			array(
				'headers' => $this->get_headers(),
				'body'    => $this->get_body(),
			)
		);

		$response = wp_safe_remote_post( $this->url, $params );

		$this->process_response( $response );
	}

	/**
	 * Process Outlook-specific response with a helpful error.
	 *
	 * @see   https://docs.microsoft.com/en-us/graph/errors
	 * @see   https://docs.microsoft.com/en-us/azure/active-directory/develop/reference-aadsts-error-codes
	 * @see   https://docs.microsoft.com/en-us/exchange/client-developer/web-service-reference/responsecode
	 *
	 * @since 1.5.0
	 *
	 * @return string
	 */
	protected function get_response_error() {

		$body = (array) wp_remote_retrieve_body( $this->response );

		$error_text = '';

		if (
			! empty( $body['error']->code ) &&
			! empty( $body['error']->message )
		) {
			$error_text = esc_html( $body['error']->code . ': ' . $body['error']->message );
			if ( ! empty( $body['error']->innerError->date ) ) {
				$error_text .= "\r\n" . 'Date: ' . esc_html( $body['error']->innerError->date );
			}
		}

		return ! empty( $error_text ) ? $error_text : wp_json_encode( $body );
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

		$mg_text[] = '<strong>App ID/Pass:</strong> ' . ( $auth->is_clients_saved() ? 'Yes' : 'No' );
		$mg_text[] = '<strong>Tokens:</strong> ' . ( ! $auth->is_auth_required() ? 'Yes' : 'No' );

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
