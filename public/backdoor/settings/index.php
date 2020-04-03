<?php
/**
 * General settings administration panel.
 *
 * @package WordPress
 * @subpackage Administration
 */

/** WordPress Administration Bootstrap */
require_once __DIR__ . '/../admin.php';

/** WordPress Translation Installation API */
require_once ABSPATH . ADMIN_DIR . '/includes/translation-install.php';

if ( ! current_user_can( 'manage_options' ) ) {
	wp_die( __( 'Sorry, you are not allowed to manage options for this site.' ) );
}

$title       = __( 'General Settings' );
$parent_file = 'settings/index.php';
/* translators: Date and time format for exact current time, mainly about timezones, see https://www.php.net/date */
$timezone_format = _x( 'Y-m-d H:i:s', 'timezone date format' );

add_action( 'admin_head', 'options_general_add_js' );

require_once ABSPATH . ADMIN_DIR . '/admin-header.php';
?>

<div class="wrap">
<h1 class="mb-4"><?php echo esc_html( $title ); ?></h1>

<form method="post" action="options.php" novalidate="novalidate">
<?php settings_fields( 'general' ); ?>

	<div class="row">
		<div class="col-lg-8">
			<div class="row">
				<div class="col-lg-6">
					<div class="form-group">
						<label for="blogname"><?php _e( 'Site Title' ); ?></label>
					  <input id="blogname" value="<?php form_option( 'blogname' ); ?>" name="blogname" type="text" class="form-control">
					</div>
				</div>

				<div class="col-lg-6">
					<div class="form-group">
						<label for="blogdescription"><?php _e( 'Tagline' ); ?></label>
					  <input name="blogdescription" type="text" id="blogdescription" aria-describedby="tagline-description" value="<?php form_option( 'blogdescription' ); ?>" class="form-control">
					</div>
				</div>
			</div>
			<!-- /.row -->

		<?php
		if ( ! is_multisite() ) {
			$wp_site_url_class = '';
			$wp_home_class     = '';
			if ( defined( 'WP_SITEURL' ) ) {
				$wp_site_url_class = ' disabled';
			}
			if ( defined( 'WP_HOME' ) ) {
				$wp_home_class = ' disabled';
			}
		?>
			<div class="row">
				<div class="col-lg-6">
					<div class="form-group">
						<label for="siteurl"><?php _e( 'Admin URL' ); ?></label>
					  <input name="siteurl" type="url" id="siteurl" value="<?php form_option( 'siteurl' ); ?>"<?php disabled( defined( 'WP_SITEURL' ) ); ?> type="text" class="form-control">
					</div>
				</div>

				<div class="col-lg-6">
					<label for="home"><?php _e( 'Website URL' ); ?></label>
					<div class="form-group">
					    <input name="home" type="url" id="home" aria-describedby="home-description" value="<?php form_option( 'home' ); ?>" class="form-control">
					    <?php if ( ! defined( 'WP_HOME' ) ) : ?>
							<small class="form-text text-muted">
								<?php
									echo
										__( 'Enter the address here if you want your site home page to be different from your installation directory.');
								?>
							</small>
					    <?php endif; ?>
					</div>
				</div>
			</div>
			<!-- /.row -->
	<?php } ?>

	<?php 
		// Retrieve the new email address (if exists)
		$new_admin_email = get_option( 'new_admin_email' );
	?>

			<div class="row">
				<div class="col-lg-12">
					
					<?php if ( $new_admin_email && get_option( 'admin_email' ) !== $new_admin_email ) : ?>
					<div class="card border-0 p-3 bg-light">
					<?php endif; ?>

					<div class="form-group">
						<label for="new_admin_email"><?php _e( 'Administration Email Address' ); ?></label>
						<input name="new_admin_email" type="email" id="new_admin_email" aria-describedby="new-admin-email-description" value="<?php form_option( 'admin_email' ); ?>" class="form-control" />
						<small class="form-text text-muted" id="new-admin-email-description"><?php _e( 'This address is used for admin purposes. If you change this, we will send you an email at your new address to confirm it. <strong>The new address will not become active until confirmed.</strong>' ); ?></small>
					</div>
					<!-- Check if there is a new administration address e-mail. If so, show a pending confirmation -->
					<?php if ( $new_admin_email && get_option( 'admin_email' ) !== $new_admin_email ) : ?>
						<div class="updated inline">
						<p>
						<?php
							printf(
								/* translators: %s: New admin email. */
								__( 'There is a pending change of the admin email to %s.' ),
								'<code>' . esc_html( $new_admin_email ) . '</code>'
							);
							printf(
								' <a href="%1$s">%2$s</a>',
								esc_url( wp_nonce_url( admin_url( 'options.php?dismiss=new_admin_email' ), 'dismiss-' . get_current_blog_id() . '-new_admin_email' ) ),
								__( 'Cancel' )
							);
						?>
						</p>
						</div>
					</div> <!-- /if new_email -->
					<?php endif; ?>

				</div>
			</div> <!-- /.row -->
		</div> <!-- column-left -->

		<!-- column-right-->
		<div class="column-right col-4">
			<?php if ( ! is_multisite() ) { ?>
			<div class="row">
				<div class="col-lg-12">

						<div class="card border-0 bg-light p-3">
							<h3 class="mt-0 mb-2"><?php _e( 'Membership' ); ?></h3>

						  <div class="form-group form-check">
						    <input name="users_can_register" type="checkbox" id="users_can_register" value="1" class="form-check-input" <?php checked( '1', get_option( 'users_can_register' ) ); ?> />
						    <label class="form-check-label" for="users_can_register"><?php _e( 'Anyone can register' ); ?></label>
						  </div>
							
							<div class="form-group">
								<label for="default_role"><?php _e( 'New User Default Role' ); ?></label>
								<select name="default_role" id="default_role" class="form-control">
									<?php wp_dropdown_roles( get_option( 'default_role' ) ); ?>
								</select>
							</div>
						</div>

				</div>
			</div>
			<?php } ?>
		<?php
			$languages = get_available_languages();
			$translations = wp_get_available_translations();
			
			if ( ! is_multisite() && defined( 'WPLANG' ) && '' !== WPLANG && 'en_US' !== WPLANG && ! in_array( WPLANG, $languages ) ) {
				$languages[] = WPLANG;
			}

			if ( ! empty( $languages ) || ! empty( $translations ) ) {
				?>
			<div class="row mt-4">
				<div class="col-lg-12">
						<div class="card border-0 bg-light p-3">
							<h3 class="mt-0 mb-2">
								<span class="dashicons mt-1 dashicons-translation" aria-hidden="true"></span>
								<?php _e( 'Site Language' ); ?>
							</h3>
							<div class="form-group">
						<?php
							$locale = get_locale();
							if ( ! in_array( $locale, $languages ) ) {
								$locale = '';
							}

							wp_dropdown_languages(
								array(
									'name'                        => 'WPLANG',
									'id'                          => 'WPLANG',
									'class'	=> 'form-control',
									'selected'                    => $locale,
									'languages'                   => $languages,
									'translations'                => $translations,
									'show_available_translations' => current_user_can( 'install_languages' ) && wp_can_install_language_pack(),
								)
							);
						?>
							</div>
					</div>
				</div>
			</div>
				<?php
			}
	?>
		</div> <!-- /.column-right-->
	</div> <!-- /the big .row -->

	<div class="row">
		<div class="col-lg-12">
			<h3 class="mb-0">Time & Dates</h3>
			<p class="lead mb-2 mt-0">Adjusting time and dates will be applied global.</p>
			<div class="timezone-info mb-2">
				<h4 id="utc-time" class="mb-0 d-inline-block">
				<?php
					printf(
						/* translators: %s: UTC time. */
						__( 'Universal time is %s' ),
						'<code>' . date_i18n( $timezone_format, false, true ) . '</code>'
					);
					?>
				</h4>
			<?php if ( !get_option( 'timezone_string' ) || ! empty( $current_offset ) ) : ?>
				<h4 id="local-time" class="ml-3 mb-0 d-inline-block">
				<?php
					printf(
						/* translators: %s: Local time. */
						__( 'Local time is %s' ),
						'<code>' . date_i18n( $timezone_format ) . '</code>'
					);
				?>
				</h4>
			<?php endif; ?>
			</div>
		</div>
	</div>

	<div class="row mt-2">
		<div class="col-lg-4">
		<?php
			$current_offset = get_option( 'gmt_offset' );
			$tzstring       = get_option( 'timezone_string' );

			$check_zone_info = true;

			// Remove old Etc mappings. Fallback to gmt_offset.
			if ( false !== strpos( $tzstring, 'Etc/GMT' ) ) {
				$tzstring = '';
			}

			if ( empty( $tzstring ) ) { // Create a UTC+- zone if no timezone string exists.
				$check_zone_info = false;
				if ( 0 == $current_offset ) {
					$tzstring = 'UTC+0';
				} elseif ( $current_offset < 0 ) {
					$tzstring = 'UTC' . $current_offset;
				} else {
					$tzstring = 'UTC+' . $current_offset;
				}
			}
		?>
			<div class="form-group">
				<label for="timezone_string"><?php _e( 'Timezone' ); ?></label>
				<select class="form-control" id="timezone_string" name="timezone_string" aria-describedby="timezone-description">
					<?php echo wp_timezone_choice( $tzstring, get_user_locale() ); ?>
				</select>

				<small class="form-text text-muted description" id="timezone-description">
				<?php
					printf(
						/* translators: %s: UTC abbreviation */
						__( 'Choose either a city in the same timezone as you or a %s (Coordinated Universal Time) time offset.' ),
						'<abbr>UTC</abbr>'
					);
					?>
				</small>
			</div>
		</div>
		<div class="col-lg-4">
			<div class="form-group">
				<label for="start_of_week"><?php _e( 'Week Starts On' ); ?></label>
				<select class="form-control" name="start_of_week" id="start_of_week">
				<?php
				/**
				 * @global WP_Locale $wp_locale WordPress date and time locale object.
				 */
				global $wp_locale;

				for ( $day_index = 0; $day_index <= 6; $day_index++ ) :
					$selected = ( get_option( 'start_of_week' ) == $day_index ) ? 'selected="selected"' : '';
					echo "\n\t<option value='" . esc_attr( $day_index ) . "' $selected>" . $wp_locale->get_weekday( $day_index ) . '</option>';
				endfor;
				?>
				</select>
			</div>
		</div>
	</div>

		<div class="row">
			<div class="col-lg-4">
				<label class="d-block h5"><?php _e( 'Date Format' ); ?></label>
		<?php
			/**
			 * Filters the default date formats.
			 *
			 * @since 2.7.0
			 * @since 4.0.0 Added ISO date standard YYYY-MM-DD format.
			 *
			 * @param string[] $default_date_formats Array of default date formats.
			 */
			$date_formats = array_unique( apply_filters( 'date_formats', array( __( 'F j, Y' ), 'Y-m-d', 'm/d/Y', 'd/m/Y' ) ) );

			$custom = true;

		foreach ( $date_formats as $format ) {
			echo "<label class='d-block'><input type='radio' name='date_format' value='" . esc_attr( $format ) . "'";
			if ( get_option( 'date_format' ) === $format ) { // checked() uses "==" rather than "===".
				echo " checked='checked'";
				$custom = false;
			}
			echo ' /> <span class="date-time-text format-i18n">' . date_i18n( $format ) . '</span><code>' . esc_html( $format ) . "</code></label>";
		}

			echo '<label><input type="radio" name="date_format" id="date_format_custom_radio" value="\c\u\s\t\o\m"';
			checked( $custom );
			echo '/> <span class="date-time-text date-time-custom-text">' . __( 'Custom:' ) . '<span class="screen-reader-text"> ' . __( 'enter a custom date format in the following field' ) . '</span></span></label>' .
				'<label for="date_format_custom" class="screen-reader-text">' . __( 'Custom date format:' ) . '</label>' .
				'<input type="text" name="date_format_custom" id="date_format_custom" value="' . esc_attr( get_option( 'date_format' ) ) . '" class="form-control" />' .
				'<p><strong>' . __( 'Preview:' ) . '</strong> <span class="example">' . date_i18n( get_option( 'date_format' ) ) . '</span>' .
				"<span class='spinner'></span>\n" . '</p>';
		?>
			</div>
			<div class="col-lg-4">
				<label class="d-block h5"><?php _e( 'Time Format' ); ?></label>
			<?php
				/**
				 * Filters the default time formats.
				 *
				 * @since 2.7.0
				 *
				 * @param string[] $default_time_formats Array of default time formats.
				 */
				$time_formats = array_unique( apply_filters( 'time_formats', array( __( 'g:i a' ), 'g:i A', 'H:i' ) ) );

				$custom = true;
			?>
				<label>
						<input type="radio" name="time_format" id="time_format_custom_radio" value="\c\u\s\t\o\m" <?php echo checked( $custom ); ?> '/>
						<span class="date-time-text date-time-custom-text">
						<?php echo __( 'Custom:' ); ?>
						<span class="screen-reader-text">
							<?php echo __( 'enter a custom time format in the following field' ); ?>
						</span>
					</span>
				</label>
				<label for="time_format_custom" class="screen-reader-text">
					<?php echo __( 'Custom time format:' ); ?>
				</label>

				<input type="text" name="time_format_custom" id="time_format_custom" value="<?php echo esc_attr( get_option( 'time_format' ) ); ?>" class="form-control form-control-sm" />
				<div class="mb-3">
					<strong><?php echo __('Preview:' );?></strong>
					<span class="example"><?php echo date_i18n( get_option( 'time_format' ) ); ?></span>
					<!-- <span class='spinner'></span> -->
				</div>

			<?php
				foreach ( $time_formats as $format ) {
					echo "<label><input type='radio' name='time_format' value='" . esc_attr( $format ) . "'";
					if ( get_option( 'time_format' ) === $format ) { // checked() uses "==" rather than "===".
						echo " checked='checked'";
						$custom = false;
					}
					echo ' /> <span class="date-time-text format-i18n">' . date_i18n( $format ) . '</span><code>' . esc_html( $format ) . "</code></label><br />\n";
				}
			?>
			</div>
		</div>

		<?php do_settings_fields( 'general', 'default' ); ?>
		<?php do_settings_sections( 'general' ); ?>
		
		<div style="margin: 0 -45px -45px; padding: 25px 45px; background: #d5f9f1; border-top: 1px #ecf0e8 solid; text-align: center;">
			<?php submit_button('Update Changes', 'btn-dark px-4 py-2'); ?>
		</div>
</form>

</div>

<?php require_once ABSPATH . ADMIN_DIR . '/admin-footer.php'; ?>
