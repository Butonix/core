<?php

namespace WPMailSMTP\Pro\Emails\Logs\Providers;

use WPMailSMTP\MailCatcher;
use WPMailSMTP\Options;
use WPMailSMTP\Pro\Emails\Logs\Email;
use WPMailSMTP\Providers\MailerAbstract;

/**
 * Class Common to handle saving to log all emails sent by mailers.
 *
 * @since 1.5.0
 */
class Common {

	/**
	 * @since 1.5.0
	 */
	const MAILER = 'gmail';

	/**
	 * @since 1.5.0
	 *
	 * @var \WPMailSMTP\MailCatcher
	 */
	private $mailcatcher;

	/**
	 * @since 1.5.0
	 *
	 * @var \WPMailSMTP\Providers\MailerAbstract
	 */
	private $mailer;

	/**
	 * Common constructor.
	 *
	 * @since 1.5.0
	 *
	 * @param \WPMailSMTP\Providers\MailerAbstract $mailer
	 */
	public function __construct( MailerAbstract $mailer ) {

		$this->mailer = $mailer;
	}

	/**
	 * Preserve the cloned instance of the MailCatcher class.
	 *
	 * @since 1.5.0
	 *
	 * @param \WPMailSMTP\MailCatcher $mailcatcher
	 *
	 * @return \WPMailSMTP\Pro\Emails\Logs\Providers\Common
	 */
	public function set_source( MailCatcher $mailcatcher ) {

		$this->mailcatcher = clone $mailcatcher;

		return $this;
	}

	/**
	 * Save locally the email that was sent to Gmail.
	 *
	 * @since 1.5.0
	 *
	 * @return int
	 */
	public function save() {

		$headers     = explode( $this->mailcatcher->LE, $this->mailcatcher->createHeader() );
		$attachments = count( $this->mailcatcher->getAttachments() );
		$people      = array();
		$email_id    = 0;

		foreach ( $this->mailcatcher->getToAddresses() as $to ) {
			$people['to'][] = $to[0];
		}
		foreach ( $this->mailcatcher->getCcAddresses() as $cc ) {
			$people['cc'][] = $cc[0];
		}
		foreach ( $this->mailcatcher->getBccAddresses() as $bcc ) {
			$people['bcc'][] = $bcc[0];
		}
		$people['from'] = $this->mailcatcher->From;

		try {
			$email = new Email();
			$email
				->set_subject( $this->mailcatcher->Subject )
				->set_people( $people )
				->set_headers( array_filter( $headers ) )
				->set_attachments( $attachments )
				->set_mailer( Options::init()->get( 'mail', 'mailer' ) )
				->set_status( $this->mailer->is_email_sent() ? Email::STATUS_SENT : Email::STATUS_UNSENT );

			if ( wp_mail_smtp()->pro->get_logs()->is_enabled_content() ) {
				$email
					->set_content_plain( $this->mailcatcher->ContentType === 'text/plain' ? $this->mailcatcher->Body : $this->mailcatcher->AltBody )
					->set_content_html( $this->mailcatcher->ContentType !== 'text/plain' ? $this->mailcatcher->Body : '' );
			}

			$email_id = $email->save()->get_id();
		}
		catch ( \Exception $e ) {
			// Do nothing for now.
		}

		return $email_id;
	}
}
