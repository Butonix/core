<?php

namespace WPMailSMTP\Pro\Providers\AmazonSES;

use WPMailSMTP\Debug;
use WPMailSMTP\Geo;
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
	const SLUG = 'amazonses';

	/**
	 * Outlook Options constructor.
	 *
	 * @since 1.5.0
	 */
	public function __construct() {

		parent::__construct(
			array(
				'logo_url'    => wp_mail_smtp()->assets_url . '/images/providers/aws.svg',
				'slug'        => self::SLUG,
				'title'       => esc_html__( 'Amazon SES', 'wp-mail-smtp-pro' ),
				'description' => esc_html__( 'Send emails using your Amazon AWS account and its SES service, all while keeping your login credentials safe.', 'wp-mail-smtp-pro' ) . '<br><br>' .
				                 // phpcs:disable
				                 sprintf(
					                 wp_kses( /* translators: %s - WPMailSMTP.com URL. */
						                 __( 'Read our <a href="%s" target="_blank" rel="noopener noreferrer">Amazon SES documentation</a> to learn how to configure Amazon SES and improve your email deliverability.', 'wp-mail-smtp-pro' ),
						                 array(
							                 'a' => array(
								                 'href'   => array(),
								                 'rel'    => array(),
								                 'target' => array(),
							                 ),
						                 )
					                 ),
					                 'https://wpmailsmtp.com/docs/how-to-set-up-the-amazon-ses-mailer-in-wp-mail-smtp/'
				                 ),
								// phpcs:enable
				'notices'     => array(
					'educational' => esc_html__( 'The Amazon SES mailer will be a good choice for technically advanced users who already have experience working with Amazon\'s web services. If you aren\'t sure whether this mailer sounds like the right fit for your site, then we recommend considering one of our other mailer options.', 'wp-mail-smtp-pro' ),
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

		<!-- Access Key ID -->
		<div id="wp-mail-smtp-setting-row-<?php echo esc_attr( $this->get_slug() ); ?>-client_id"
			class="wp-mail-smtp-setting-row wp-mail-smtp-setting-row-text wp-mail-smtp-clear">
			<div class="wp-mail-smtp-setting-label">
				<label for="wp-mail-smtp-setting-<?php echo esc_attr( $this->get_slug() ); ?>-client_id">
					<?php esc_html_e( 'Access Key ID', 'wp-mail-smtp-pro' ); ?>
				</label>
			</div>
			<div class="wp-mail-smtp-setting-field">
				<input name="wp-mail-smtp[<?php echo esc_attr( $this->get_slug() ); ?>][client_id]" type="text"
					value="<?php echo esc_attr( $this->options->get( $this->get_slug(), 'client_id' ) ); ?>"
					<?php echo $this->options->is_const_defined( $this->get_slug(), 'client_id' ) ? 'disabled' : ''; ?>
					id="wp-mail-smtp-setting-<?php echo esc_attr( $this->get_slug() ); ?>-client_id" spellcheck="false"
				/>
			</div>
		</div>

		<!-- Secret Access Key -->
		<div id="wp-mail-smtp-setting-row-<?php echo esc_attr( $this->get_slug() ); ?>-client_secret"
			class="wp-mail-smtp-setting-row wp-mail-smtp-setting-row-text wp-mail-smtp-clear">
			<div class="wp-mail-smtp-setting-label">
				<label for="wp-mail-smtp-setting-<?php echo esc_attr( $this->get_slug() ); ?>-client_secret">
					<?php esc_html_e( 'Secret Access Key', 'wp-mail-smtp-pro' ); ?>
				</label>
			</div>
			<div class="wp-mail-smtp-setting-field">
				<?php if ( $this->options->is_const_defined( $this->get_slug(), 'client_secret' ) ) : ?>
					<input type="text" disabled value="****************************************"
						id="wp-mail-smtp-setting-<?php echo esc_attr( $this->get_slug() ); ?>-client_secret"
					/>
					<?php $this->display_const_set_message( 'WPMS_AMAZONSES_CLIENT_SECRET' ); ?>
				<?php else : ?>
					<input type="password" spellcheck="false"
						name="wp-mail-smtp[<?php echo esc_attr( $this->get_slug() ); ?>][client_secret]"
						value="<?php echo esc_attr( $this->options->get( $this->get_slug(), 'client_secret' ) ); ?>"
						id="wp-mail-smtp-setting-<?php echo esc_attr( $this->get_slug() ); ?>-client_secret"
					/>
				<?php endif; ?>
			</div>
		</div>

		<!-- Closest Region -->
		<div id="wp-mail-smtp-setting-row-<?php echo esc_attr( $this->get_slug() ); ?>-region"
			class="wp-mail-smtp-setting-row wp-mail-smtp-setting-row-text wp-mail-smtp-clear">
			<div class="wp-mail-smtp-setting-label">
				<label for="wp-mail-smtp-setting-<?php echo esc_attr( $this->get_slug() ); ?>-region">
					<?php esc_html_e( 'Closest Region', 'wp-mail-smtp-pro' ); ?>
				</label>
			</div>
			<div class="wp-mail-smtp-setting-field">
				<?php
				if ( $this->get_slug() === $this->options->get( 'mail', 'mailer' ) ) {
					$is_region_guessed = false;
					$current_region    = $this->options->get( $this->get_slug(), 'region' );

					if ( empty( $current_region ) ) {
						$current_region = $this->get_closest_region();

						$is_region_guessed = ! empty( $current_region );
					}
					?>
					<select
						<?php echo $this->options->is_const_defined( $this->get_slug(), 'region' ) ? 'disabled' : ''; ?>
						name="wp-mail-smtp[<?php echo esc_attr( $this->get_slug() ); ?>][region]"
						id="wp-mail-smtp-setting-<?php echo esc_attr( $this->get_slug() ); ?>-region">
						<option value=""><?php esc_html_e( '--- Select region ---', 'wp-mail-smtp-pro' ); ?></option>
						<?php foreach ( Auth::get_regions_names() as $region => $label ) : ?>
							<option value="<?php echo esc_attr( $region ); ?>" <?php selected( $current_region, $region ); ?>><?php echo esc_html( $label ); ?></option>
						<?php endforeach; ?>
					</select>
					<p class="desc">
						<?php if ( $is_region_guessed ) { ?>
							<?php esc_html_e( 'The closest Amazon SES region to your website was preselected.', 'wp-mail-smtp-pro' ); ?><br/>
						<?php } else { ?>
							<?php esc_html_e( 'Please select the Amazon SES API region which is the closest to where your website is hosted.', 'wp-mail-smtp-pro' ); ?><br/>
						<?php } ?>
						<?php esc_html_e( 'This can help to decrease network latency between your site and Amazon SES, which will speed up email sending.', 'wp-mail-smtp-pro' ); ?>
					</p>
				<?php } else { ?>
					<p class="inline-notice inline-error"><?php esc_html_e( 'To access this section, please click the Save Settings button.', 'wp-mail-smtp-pro' ); ?></p>
				<?php } ?>
			</div>
		</div>

		<!-- Verified Senders -->
		<div id="wp-mail-smtp-setting-row-<?php echo esc_attr( $this->get_slug() ); ?>-senders"
			class="wp-mail-smtp-setting-row wp-mail-smtp-setting-row-text wp-mail-smtp-clear">
			<div class="wp-mail-smtp-setting-label">
				<label><?php esc_html_e( 'Verified Emails', 'wp-mail-smtp-pro' ); ?></label>
			</div>
			<div class="wp-mail-smtp-setting-field">
				<?php
				if ( $this->get_slug() === $this->options->get( 'mail', 'mailer' ) ) {
					$auth = new Auth();

					if ( $auth->is_connection_ready() ) {
						// Get the addresses that have been verified in an AWS SES account > Identity Management > Email Addresses.
						$emails_verified = $auth->get_verified_emails();
						$emails_pending  = $this->options->get( $this->get_slug(), 'emails_pending' );
						$error           = Debug::get_last();

						// Check that pending is already verified. If it is - remove it from the pending list.
						$emails_pending_new = array_diff( $emails_pending, $emails_verified );

						// Compare arrays non-strictly, we need to make sure values itself of arrays are equal, not the values order.
						if ( $emails_pending_new != $emails_pending ) { // phpcs:ignore WordPress.PHP.StrictComparisons.LooseComparison
							$all_opt = $this->options->get_all();

							$all_opt[ $this->get_slug() ]['emails_pending'] = $emails_pending_new;

							$this->options->set( $all_opt );

							$emails_pending = $emails_pending_new;
						}

						if ( empty( $emails_verified ) && ! empty( $error ) && strpos( $error, 'SimpleEmailService' ) !== false ) {
							// Display an error message to a user.
							echo '<p class="inline-notice inline-error">' . esc_html( $error ) . '</p>';
							Debug::clear();
						} else {
							?>
							<?php if ( ! empty( $emails_pending ) || ! empty( $emails_verified ) ) : ?>
								<table class="actions-list">
									<thead>
									<tr>
										<th><?php esc_html_e( 'Email Address', 'wp-mail-smtp-pro' ); ?></th>
										<th><?php esc_html_e( 'Status', 'wp-mail-smtp-pro' ); ?></th>
										<th><?php esc_html_e( 'Action', 'wp-mails-smtp' ); ?></th>
									</tr>
									</thead>
									<tbody>
									<?php
									// Pending.
									if ( ! empty( $emails_pending ) ) :
										foreach ( $emails_pending as $email ) :
											?>

											<tr>
												<td class="email"><?php echo esc_html( $email ); ?></td>
												<td class="status"><?php esc_html_e( 'Pending', 'wp-mail-smtp-pro' ); ?></td>
												<td class="actions">
													<a href="#" title="<?php esc_attr_e( 'Resend a verification email to this address', 'wp-mail-smtp-pro' ); ?>"
													   data-email="<?php echo esc_attr( $email ); ?>"
													   data-nonce="<?php echo esc_attr( wp_create_nonce( 'wp_mail_smtp_pro_' . $this->get_slug() . '_email_add' ) ); ?>"
													   class="js-wp-mail-smtp-providers-<?php echo esc_attr( $this->get_slug() ); ?>-email-resend">
														<?php esc_html_e( 'Resend', 'wp-mail-smtp-pro' ); ?>
													</a>
													<a href="#" title="<?php esc_attr_e( 'Delete this email address', 'wp-mail-smtp-pro' ); ?>"
													   data-email="<?php echo esc_attr( $email ); ?>"
													   data-nonce="<?php echo esc_attr( wp_create_nonce( 'wp_mail_smtp_pro_' . $this->get_slug() . '_email_resend_delete' ) ); ?>"
													   class="js-wp-mail-smtp-providers-<?php echo esc_attr( $this->get_slug() ); ?>-email-resend-delete">
														<?php esc_html_e( 'Delete', 'wp-mail-smtp-pro' ); ?>
													</a>
												</td>
											</tr>

										<?php endforeach; ?>
									<?php endif; ?>
									<?php
									// Verified.
									if ( ! empty( $emails_verified ) ) :
										foreach ( $emails_verified as $email ) :
											?>
											<tr>
												<td class="email"><?php echo esc_html( $email ); ?></td>
												<td class="status"><?php esc_html_e( 'Verified', 'wp-mail-smtp-pro' ); ?></td>
												<td class="actions">
													<a href="#" title="<?php esc_attr_e( 'Delete this email address', 'wp-mail-smtp-pro' ); ?>"
													   data-email="<?php echo esc_attr( $email ); ?>"
													   data-nonce="<?php echo esc_attr( wp_create_nonce( 'wp_mail_smtp_pro_' . $this->get_slug() . '_email_delete' ) ); ?>"
													   class="js-wp-mail-smtp-providers-<?php echo esc_attr( $this->get_slug() ); ?>-email-delete">
														<?php esc_html_e( 'Delete', 'wp-mail-smtp-pro' ); ?>
													</a>
												</td>
											</tr>
										<?php endforeach; ?>
									<?php endif; ?>
									</tbody>
								</table>

							<?php else : ?>

								<p style="margin-bottom: 10px">
									<strong><?php esc_html_e( 'No verified email addresses.', 'wp-mail-smtp-pro' ); ?></strong>
									<?php esc_html_e( 'You will not be able to send emails until you add one (or more).', 'wp-mail-smtp-pro' ); ?>
								</p>

							<?php endif; ?>

							<?php add_thickbox(); ?>

							<p style="margin-bottom: 10px">
								<a href="#TB_inline?&width=300&height=&inlineId=wp-mail-smtp-providers-<?php echo esc_attr( $this->get_slug() ); ?>-email-enter-holder"
								   title="<?php esc_attr_e( 'Email to Verify', 'wp-mail-smtp-pro' ); ?>" class="thickbox wp-mail-smtp-btn wp-mail-smtp-btn-md wp-mail-smtp-btn-orange">
									<?php esc_html_e( 'Add an Email Address', 'wp-mail-smtp-pro' ); ?>
								</a>
							</p>

							<div id="wp-mail-smtp-providers-<?php echo esc_attr( $this->get_slug() ); ?>-email-enter-holder">
								<div id="wp-mail-smtp-providers-<?php echo esc_attr( $this->get_slug() ); ?>-email-enter">
									<?php wp_nonce_field( 'wp_mail_smtp_pro_amazonses_email_add', 'wp_mail_smtp_pro_amazonses_email_add' ); ?>
									<input type="email" placeholder="<?php esc_html_e( 'Please enter a valid email address', 'wp-mail-smtp-pro' ); ?>">
									<button class="wp-mail-smtp-btn wp-mail-smtp-btn-md wp-mail-smtp-btn-orange js-wp-mail-smtp-providers-<?php echo esc_attr( $this->get_slug() ); ?>-email-add">
										<?php esc_html_e( 'Send Verification Email', 'wp-mail-smtp-pro' ); ?>
									</button>
								</div>
							</div>

							<p class="desc">
								<?php esc_html_e( 'Here are the email addresses that have been verified and can be used as the From Email address.', 'wp-mail-smtp-pro' ); ?>
							</p>
							<?php
						}
					} else {
						echo '<p class="inline-notice inline-error">' . esc_html( $this->get_connection_not_ready_error_text() ) . '</p>';
					}
				} else {
					echo '<p class="inline-notice inline-error">' . esc_html( $this->get_connection_not_ready_error_text() ) . '</p>';
				}
				?>

			</div>
		</div>

		<?php
	}

	/**
	 * Get an error text when the connection is not yet ready,
	 * basically, when we can't make requests to AmazonSES API.
	 *
	 * @since 1.5.0
	 *
	 * @return string
	 */
	protected function get_connection_not_ready_error_text() {
		return esc_html__( 'To access this section, please add an Access Key ID and Secret Access Key, select the Closest Region, then click the Save Settings button.', 'wp-mail-smtp-pro' );
	}

	/**
	 * Get the closest region based on Amazon SES region coordinates.
	 *
	 * @since 1.5.0
	 *
	 * @return string Region identifier.
	 */
	private function get_closest_region() {

		$site = Geo::get_location_by_ip( Geo::get_ip_by_domain( Geo::get_site_domain() ) );

		if ( empty( $site ) ) {
			return '';
		}

		$distance_to = array();

		foreach ( Auth::get_regions_coordinates() as $region => $coords ) {
			$distance_to[ $region ] = Geo::get_distance_between(
				$site['latitude'],
				$site['longitude'],
				$coords['lat'],
				$coords['lon']
			);
		}

		return array_search( min( $distance_to ), $distance_to, true );
	}
}
