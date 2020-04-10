<?php

namespace WPMailSMTP\Pro\Emails\Logs\Providers;

use WPMailSMTP\MailCatcher;
use WPMailSMTP\Options;
use WPMailSMTP\Pro\Emails\Logs\Email;

/**
 * Class SMTP to handle saving to log emails sent by "Other SMTP" mailer.
 *
 * @since 1.5.0
 */
class SMTP {

	/**
	 * @since 1.5.0
	 *
	 * @var \WPMailSMTP\MailCatcher
	 */
	private $mailcatcher;

	/**
	 * Preserve the cloned instance of the MailCatcher class.
	 *
	 * @since 1.5.0
	 *
	 * @param \WPMailSMTP\MailCatcher $mailcatcher
	 *
	 * @return \WPMailSMTP\Pro\Emails\Logs\Providers\SMTP
	 */
	public function set_source( MailCatcher $mailcatcher ) {

		$this->mailcatcher = clone $mailcatcher;

		return $this;
	}

	/**
	 * Save the actual email data before we got response from SMTP server about its status.
	 *
	 * @since 1.5.0
	 *
	 * @return int
	 */
	public function save_before() {

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
				->set_status( Email::STATUS_UNSENT );

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

		// Return the state.
		return $email_id;
	}

	/**
	 * Update the status of the currently sent email.
	 *
	 * @since 1.5.0
	 *
	 * @param int  $email_id
	 * @param bool $is_sent
	 */
	public function save_after( $email_id, $is_sent ) {

		if ( empty( $email_id ) ) {
			return;
		}

		try {
			$email = new Email( $email_id );
			$email
				->set_status( (bool) $is_sent ? Email::STATUS_SENT : Email::STATUS_UNSENT )
				->save();
		}
		catch ( \Exception $e ) {
			// Do nothing for now.
		}
	}
}
