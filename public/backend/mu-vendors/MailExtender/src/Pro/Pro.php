<?php

namespace WPMailSMTP\Pro;

use WPMailSMTP\Pro\Emails\Logs\Logs;

/**
 * Class Pro handles all Pro plugin code and functionality registration.
 * Initialized inside 'init' WordPress hook.
 *
 * @since 1.5.0
 */
class Pro {

	/**
	 * Plugin slug.
	 *
	 * @since 1.5.0
	 */
	const SLUG = 'wp-mail-smtp-pro';

	/**
	 * List of files to be included early.
	 * Path from the root of the plugin directory.
	 *
	 * @since 1.5.0
	 */
	const PLUGGABLE_FILES = array(
		'src/Pro/Emails/Control/functions.php',
	);

	/**
	 * URL to Pro plugin assets directory.
	 *
	 * @since 1.5.0
	 *
	 * @var string Without trailing slash.
	 */
	public $assets_url = '';

	/**
	 * Pro class constructor.
	 *
	 * @since 1.5.0
	 */
	public function __construct() {

		$this->assets_url = wp_mail_smtp()->assets_url . '/pro';

		$this->init();
	}

	/**
	 * Initialize the main Pro logic.
	 *
	 * @since 1.5.0
	 */
	public function init() {

		// Load translations just in case.
		load_plugin_textdomain( 'wp-mail-smtp-pro', false, plugin_basename( wp_mail_smtp()->plugin_path ) . '/assets/pro/languages' );

		add_filter( 'http_request_args', array( $this, 'request_lite_translations' ), 10, 2 );

		$this->get_control();
		$this->get_logs();
		$this->get_providers();
		$this->get_license();
		$this->get_site_health()->init();
	}

	/**
	 * Load the Control functionality.
	 *
	 * @since 1.5.0
	 *
	 * @return Emails\Control\Control
	 */
	public function get_control() {

		static $control;

		if ( ! isset( $control ) ) {
			$control = apply_filters( 'wp_mail_smtp_pro_get_control', new Emails\Control\Control() );
		}

		return $control;
	}

	/**
	 * Load the Logs functionality.
	 *
	 * @since 1.5.0
	 *
	 * @return Emails\Logs\Logs
	 */
	public function get_logs() {

		static $logs;

		if ( ! isset( $logs ) ) {
			$logs = apply_filters( 'wp_mail_smtp_pro_get_logs', new Emails\Logs\Logs() );
		}

		return $logs;
	}

	/**
	 * Load the new Providers functionality.
	 *
	 * @since 1.5.0
	 *
	 * @return \WPMailSMTP\Pro\Providers\Providers
	 */
	public function get_providers() {

		static $providers;

		if ( ! isset( $providers ) ) {
			$providers = apply_filters( 'wp_mail_smtp_pro_get_providers', new Providers\Providers() );
		}

		return $providers;
	}

	/**
	 * Load the new License functionality.
	 *
	 * @since 1.5.0
	 *
	 * @return \WPMailSMTP\Pro\License\License
	 */
	public function get_license() {

		static $license;

		if ( ! isset( $license ) ) {
			$license = apply_filters( 'wp_mail_smtp_pro_get_license', new License\License() );
		}

		return $license;
	}

	/**
	 * Load the Site Health functionality.
	 *
	 * @since {VERSION}
	 *
	 * @return \WPMailSMTP\Pro\SiteHealth
	 */
	public function get_site_health() {

		static $site_health;

		if ( ! isset( $site_health ) ) {
			$site_health = apply_filters( 'wp_mail_smtp_pro_get_site_health', new SiteHealth() );
		}

		return $site_health;
	}

	/**
	 * Adds WP Mail SMTP (Lite) to the update checklist of installed plugins, to check for new translations.
	 *
	 * @since 1.6.0
	 *
	 * @param array  $args HTTP Request arguments to modify.
	 * @param string $url  The HTTP request URI that is executed.
	 *
	 * @return array The modified Request arguments to use in the update request.
	 */
	public function request_lite_translations( $args, $url ) {

		// Only do something on upgrade requests.
		if ( strpos( $url, 'api.wordpress.org/plugins/update-check' ) === false ) {
			return $args;
		}

		/*
		 * If WP Mail SMTP is already in the list, don't add it again.
		 *
		 * Checking this by name because the install path is not guaranteed.
		 * The capitalized json data defines the array keys, therefore we need to check and define these as such.
		 */
		$plugins = json_decode( $args['body']['plugins'], true );
		foreach ( $plugins['plugins'] as $slug => $data ) {
			if ( isset( $data['Name'] ) && $data['Name'] === 'WP Mail SMTP' ) {
				return $args;
			}
		}

		/*
		 * Add an entry to the list that matches the WordPress.org slug for WP Mail SMTP Lite.
		 *
		 * This entry is based on the currently present data from this plugin, to make sure the version and textdomain
		 * settings are as expected. Take care of the capitalized array key as before.
		 */
		$plugins['plugins']['wp-mail-smtp/wp_mail_smtp.php'] = $plugins['plugins'][ plugin_basename( wp_mail_smtp()->plugin_path ) . '/wp_mail_smtp.php' ];
		// Override the name of the plugin.
		$plugins['plugins']['wp-mail-smtp/wp_mail_smtp.php']['Name'] = 'WP Mail SMTP';
		// Override the version of the plugin to prevent increasing the update count.
		$plugins['plugins']['wp-mail-smtp/wp_mail_smtp.php']['Version'] = '9999.0';

		// Overwrite the plugins argument in the body to be sent in the upgrade request.
		$args['body']['plugins'] = wp_json_encode( $plugins );

		return $args;
	}

	/**
	 * Get the list of all custom DB tables that should be present in the DB.
	 *
	 * @since {VERSION}
	 *
	 * @return array List of table names.
	 */
	public function get_custom_db_tables() {

		return array(
			Logs::get_table_name(),
		);
	}
}
