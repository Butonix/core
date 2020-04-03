<?php

namespace WPMailSMTP\Pro\Emails\Logs\Admin;

use WPMailSMTP\Admin\Area;

/**
 * Class PageAbstract to handle Logs pages specific needs.
 *
 * @since 1.5.0
 */
abstract class PageAbstract extends \WPMailSMTP\Admin\PageAbstract {

	/**
	 * @since 1.5.0
	 *
	 * @var string Slug of a page.
	 */
	protected $slug = 'logs';

	/**
	 * Title of a tab.
	 *
	 * @since 1.5.0
	 *
	 * @return string
	 */
	public function get_title() {
		return $this->get_label();
	}

	/**
	 * Get the page/tab link.
	 *
	 * @since 1.5.0
	 *
	 * @return string
	 */
	public function get_link() {

		return add_query_arg(
			'page',
			Area::SLUG . '-' . $this->slug,
			admin_url( 'admin.php' )
		);
	}

	/**
	 * Notify user that email logging is disabled.
	 *
	 * @since 1.5.0
	 */
	public function display_logging_disabled() {
		?>

		<div class="wp-mail-smtp-logs-note">
			<h2><?php esc_html_e( 'Email Logging is Not Enabled', 'wp-mail-smtp-pro' ); ?></h2>
			<p>
				<?php
				printf(
					wp_kses( /* translators: %s - settings page URL to configure Email Log. */
						__( 'Email Logging can be turned on in the WP Mail SMTP plugin settings, under the <a href="%s">Email Log tab</a>. ', 'wp-mail-smtp-pro' ),
						array(
							'a' => array(
								'href' => true,
							),
						)
					),
					esc_url( wp_mail_smtp()->get_admin()->get_admin_page_url( Area::SLUG . '&tab=logs' ) )
				);
				?>
				<br>
				<?php esc_html_e( 'Emails sent when logging is disabled are not stored in the database and will not display when enabled.', 'wp-mail-smtp-pro' ); ?>
			</p>
		</div>

		<?php
	}

	/**
	 * Notify user that email logging is not installed correctly.
	 *
	 * @since 1.7.0
	 */
	public function display_logging_not_installed() {
		?>

		<div class="wp-mail-smtp-logs-note errored">
			<h2><?php esc_html_e( 'Email Logging is Not Installed Correctly', 'wp-mail-smtp-pro' ); ?></h2>

			<p>
				<?php esc_html_e( 'For some reason the database table was not installed correctly. Please contact plugin support team to diagnose and fix the issue.', 'wp-mail-smtp-pro' ); ?>
				<br>
				<?php esc_html_e( 'Right now all sent emails are not logged.', 'wp-mail-smtp-pro' ); ?>
			</p>
		</div>

		<?php
	}
}
