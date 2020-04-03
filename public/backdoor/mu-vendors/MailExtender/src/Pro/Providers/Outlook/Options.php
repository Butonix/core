<?php

namespace WPMailSMTP\Pro\Providers\Outlook;

use WPMailSMTP\Providers\OptionsAbstract;

/**
 * Class Options
 */
class Options extends OptionsAbstract {

	/**
	 * Mailer slug.
	 *
	 * @since 1.5.0
	 */
	const SLUG = 'outlook';

	/**
	 * Outlook Options constructor.
	 *
	 * @since 1.5.0
	 */
	public function __construct() {

		parent::__construct(
			array(
				'logo_url'    => wp_mail_smtp()->assets_url . '/images/providers/microsoft.svg',
				'slug'        => self::SLUG,
				'title'       => esc_html__( 'Outlook', 'wp-mail-smtp-pro' ),
				'description' => sprintf(
					wp_kses( /* translators: %s - URL to Outlook doc. */
						__( 'Send emails using your personal or business Outlook account, all while keeping your login credentials safe.<br><br>Read our <a href="%s" target="_blank" rel="noopener noreferrer">Outlook documentation</a> to learn how to configure Outlook and improve your email deliverability.', 'wp-mail-smtp-pro' ),
						array(
							'br' => array(),
							'a'  => array(
								'href'   => array(),
								'rel'    => array(),
								'target' => array(),
							),
						)
					),
					'https://wpmailsmtp.com/docs/how-to-set-up-the-outlook-mailer-in-wp-mail-smtp/'
				),
				'notices'     => array(
					'educational' => esc_html__( 'The Outlook mailer is a great choice if you\'re already using paid email services with Microsoft, as you\'ll have the benefit of high email sending limits without signing up for a separate service. Due to the fairly complex setup, however, this option is recommended for more technical users. If you\'d prefer a more straightforward setup, then we\'d recommend considering one of the other mailer options.', 'wp-mail-smtp-pro' ),
				),
				'php'         => '5.6',
			)
		);
	}

	/**
	 * Output the mailer provider options.
	 *
	 * @since 1.5.0
	 */
	public function display_options() {

		// Do not display options if PHP version is not correct.
		if ( ! $this->is_php_correct() ) {
			$this->display_php_warning();

			return;
		}

		// Do not display options if there is no SSL certificate on a site.
		if ( ! is_ssl() ) {
			$this->display_ssl_warning();

			return;
		}
		?>

		<!-- Application ID -->
		<div id="wp-mail-smtp-setting-row-<?php echo esc_attr( $this->get_slug() ); ?>-client_id"
			class="wp-mail-smtp-setting-row wp-mail-smtp-setting-row-text wp-mail-smtp-clear">
			<div class="wp-mail-smtp-setting-label">
				<label for="wp-mail-smtp-setting-<?php echo esc_attr( $this->get_slug() ); ?>-client_id"><?php esc_html_e( 'Application ID', 'wp-mail-smtp-pro' ); ?></label>
			</div>
			<div class="wp-mail-smtp-setting-field">
				<input name="wp-mail-smtp[<?php echo esc_attr( $this->get_slug() ); ?>][client_id]" type="text"
					value="<?php echo esc_attr( $this->options->get( $this->get_slug(), 'client_id' ) ); ?>"
					<?php echo $this->options->is_const_defined( $this->get_slug(), 'client_id' ) ? 'disabled' : ''; ?>
					id="wp-mail-smtp-setting-<?php echo esc_attr( $this->get_slug() ); ?>-client_id" spellcheck="false"
				/>
			</div>
		</div>

		<!-- Application Password -->
		<div id="wp-mail-smtp-setting-row-<?php echo esc_attr( $this->get_slug() ); ?>-client_secret"
			class="wp-mail-smtp-setting-row wp-mail-smtp-setting-row-text wp-mail-smtp-clear">
			<div class="wp-mail-smtp-setting-label">
				<label for="wp-mail-smtp-setting-<?php echo esc_attr( $this->get_slug() ); ?>-client_secret"><?php esc_html_e( 'Application Password', 'wp-mail-smtp-pro' ); ?></label>
			</div>
			<div class="wp-mail-smtp-setting-field">
				<?php if ( $this->options->is_const_defined( $this->get_slug(), 'client_secret' ) ) : ?>
					<input type="text" disabled value="****************************************"
						id="wp-mail-smtp-setting-<?php echo esc_attr( $this->get_slug() ); ?>-client_secret"
					/>
					<?php $this->display_const_set_message( 'WPMS_OUTLOOK_CLIENT_SECRET' ); ?>
				<?php else : ?>
					<input type="password" spellcheck="false"
						name="wp-mail-smtp[<?php echo esc_attr( $this->get_slug() ); ?>][client_secret]"
						value="<?php echo esc_attr( $this->options->get( $this->get_slug(), 'client_secret' ) ); ?>"
						id="wp-mail-smtp-setting-<?php echo esc_attr( $this->get_slug() ); ?>-client_secret"
					/>
				<?php endif; ?>
			</div>
		</div>

		<!-- Redirect URIs -->
		<div id="wp-mail-smtp-setting-row-<?php echo esc_attr( $this->get_slug() ); ?>-client_redirect"
			class="wp-mail-smtp-setting-row wp-mail-smtp-setting-row-text wp-mail-smtp-clear">
			<div class="wp-mail-smtp-setting-label">
				<label for="wp-mail-smtp-setting-<?php echo esc_attr( $this->get_slug() ); ?>-client_redirect"><?php esc_html_e( 'Redirect URI', 'wp-mail-smtp-pro' ); ?></label>
			</div>
			<div class="wp-mail-smtp-setting-field">
				<input type="text" readonly="readonly"
					value="<?php echo esc_attr( Auth::get_plugin_auth_url() ); ?>"
					id="wp-mail-smtp-setting-<?php echo esc_attr( $this->get_slug() ); ?>-client_redirect"
				/>
				<button type="button" class="wp-mail-smtp-btn wp-mail-smtp-btn-md wp-mail-smtp-btn-light-grey wp-mail-smtp-setting-copy"
					title="<?php esc_attr_e( 'Copy URL to clipboard', 'wp-mail-smtp-pro' ); ?>"
					data-source_id="wp-mail-smtp-setting-<?php echo esc_attr( $this->get_slug() ); ?>-client_redirect">
					<span class="dashicons dashicons-admin-page"></span>
				</button>
				<p class="desc">
					<?php esc_html_e( 'This is the page on your site that you will be redirected to after you have authenticated with Microsoft.', 'wp-mail-smtp-pro' ); ?>
					<br>
					<?php esc_html_e( 'You need to copy this URL into "Authentication > Redirect URIs" web field for your application on Microsoft Azure site for your project there.', 'wp-mail-smtp-pro' ); ?>
				</p>
			</div>
		</div>

		<!-- Auth users button -->
		<div id="wp-mail-smtp-setting-row-<?php echo esc_attr( $this->get_slug() ); ?>-authorize"
			class="wp-mail-smtp-setting-row wp-mail-smtp-setting-row-text wp-mail-smtp-clear">
			<div class="wp-mail-smtp-setting-label">
				<label><?php esc_html_e( 'Authorization', 'wp-mail-smtp-pro' ); ?></label>
			</div>
			<div class="wp-mail-smtp-setting-field">
				<?php $this->display_auth_setting_action(); ?>
			</div>
		</div>

		<?php
	}

	/**
	 * Display either an "Allow..." or "Remove..." button.
	 *
	 * @since 1.5.0
	 */
	protected function display_auth_setting_action() {

		// Do the processing on the fly, as having ajax here is too complicated.
		$this->process_provider_remove();

		$auth = new Auth();
		?>

		<?php if ( $auth->is_clients_saved() ) : ?>

			<?php if ( $auth->is_auth_required() ) : ?>

				<a href="<?php echo esc_url( $auth->get_auth_url() ); ?>" class="wp-mail-smtp-btn wp-mail-smtp-btn-md wp-mail-smtp-btn-orange">
					<?php esc_html_e( 'Allow plugin to send emails using your Microsoft account', 'wp-mail-smtp-pro' ); ?>
				</a>
				<p class="desc">
					<?php esc_html_e( 'Click the button above to confirm authorization.', 'wp-mail-smtp-pro' ); ?>
				</p>

			<?php else : ?>

				<a href="<?php echo esc_url( wp_nonce_url( wp_mail_smtp()->get_admin()->get_admin_page_url(), 'outlook_remove', 'outlook_remove_nonce' ) ); ?>#wp-mail-smtp-setting-row-<?php echo esc_attr( $this->get_slug() ); ?>-authorize" class="wp-mail-smtp-btn wp-mail-smtp-btn-md wp-mail-smtp-btn-red js-wp-mail-smtp-provider-remove">
					<?php esc_html_e( 'Remove Connection', 'wp-mail-smtp-pro' ); ?>
				</a>
				<span class="connected-as">
					<?php
					$user = $this->options->get( $this->get_slug(), 'user_details' );

					if ( ! empty( $user['email'] ) && ! empty( $user['display_name'] ) ) {
						printf(
							/* translators: %s - Display name and email, as received from Microsoft API. */
							esc_html__( 'Connected as %s', 'wp-mail-smtp-pro' ),
							'<code>' . esc_html( $user['display_name'] . ' <' . $user['email'] . '>' ) . '</code>'
						);
					}
					?>
				</span>
				<p class="desc">
					<?php esc_html_e( 'Removing the connection will give you an ability to redo the connection or link to another Microsoft account.', 'wp-mail-smtp-pro' ); ?>
				</p>

			<?php endif; ?>

		<?php else : ?>

			<p class="inline-notice inline-error">
				<?php esc_html_e( 'To access this section, please add an Application ID and Application Password, then click the Save Settings button.', 'wp-mail-smtp-pro' ); ?>
			</p>

		<?php
		endif;
	}

	/**
	 * Remove Provider connection.
	 *
	 * @since 1.5.0
	 */
	public function process_provider_remove() {

		if ( ! is_super_admin() ) {
			return;
		}

		if (
			! isset( $_GET['outlook_remove_nonce'] ) ||
			! wp_verify_nonce( $_GET['outlook_remove_nonce'], 'outlook_remove' ) // phpcs:ignore
		) {
			return;
		}

		$options = new \WPMailSMTP\Options();

		if ( $options->get( 'mail', 'mailer' ) !== $this->get_slug() ) {
			return;
		}

		$old_opt = $options->get_all();

		foreach ( $old_opt[ $this->get_slug() ] as $key => $value ) {
			// Unset everything except App ID and Password.
			if ( ! in_array( $key, array( 'client_id', 'client_secret' ), true ) ) {
				unset( $old_opt[ $this->get_slug() ][ $key ] );
			}
		}

		$options->set( $old_opt );
	}
}
