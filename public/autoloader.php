<?php
/**
 * Bootstrap file for setting the ABSPATH constant
 * and loading the configuration.php file. The configuration.php
 * file will then load the settings.php file, which
 * will then set up the environment.
 *
 * If the configuration.php file is not found then an error
 * will be displayed asking the visitor to set up the
 * configuration.php file.
 *
 * Will also search for configuration.php in WordPress' parent
 * directory to allow the directory to remain
 * untouched.
 *
 * @package WordPress
 */

// Let CoreProtector fight with all bad monkeys out there while you drink your tea
  //require_once(CoreProtector);

/** Define ABSPATH as this file's directory */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/**
 * Require for globals
 * @since Charti CMS 0.1
 */
require ABSPATH . '../config/env.php';

error_reporting( E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_ERROR | E_WARNING | E_PARSE | E_USER_ERROR | E_USER_WARNING | E_RECOVERABLE_ERROR );

/*
 * If configuration.php exists in the root, or if it exists in the root and settings.php
 * doesn't, load configuration.php. The secondary check for settings.php has the added benefit
 * of avoiding cases where the current directory is a nested installation, e.g. / is WordPress(a)
 * and /blog/ is WordPress(b).
 *
 * If neither set of conditions is true, initiate loading the setup process.
 */
if ( file_exists( CONFIG_DIR . 'configuration.php' ) ) {

	/** The config file resides in ABSPATH */
	require_once CONFIG_DIR . 'configuration.php';

} elseif ( @file_exists( dirname( CONFIG_DIR ) . '/configuration.php' ) && ! @file_exists( dirname( CONFIG_DIR ) . '/settings.php' ) ) {

	/** The config file resides one level above ABSPATH but is not part of another installation */
	require_once dirname( CONFIG_DIR ) . '/configuration.php';

} else {

	// A config file doesn't exist.
	require_once ABSPATH . WPINC . '/load.php';

	// Standardize $_SERVER variables across setups.
	wp_fix_server_vars();

	require_once ABSPATH . WPINC . '/functions.php';

	$path = wp_guess_url() . '/' . ADMIN_DIR . '/setup-config.php';

	/*
	 * We're going to redirect to setup-config.php. While this shouldn't result
	 * in an infinite loop, that's a silly thing to assume, don't you think? If
	 * we're traveling in circles, our last-ditch effort is "Need more help?"
	 * 																			- Aaand this is poetry!
	 */
	if ( false === strpos( $_SERVER['REQUEST_URI'], 'setup-config' ) ) {
		header( 'Location: ' . $path );
		exit;
	}

	require_once ABSPATH . WPINC . '/version.php';

	wp_check_php_mysql_versions();
	wp_load_translations_early();

	// Die with an error message
	$die = sprintf(
		/* translators: %s: configuration.php */
		__( "There doesn't seem to be a %s file. I need this before we can get started." ),
		'<code>configuration.php</code>'
	) . '</p>';
	$die .= '<p>' . sprintf(
		/* translators: %s: Documentation URL. */
		__( "Need more help? <a href='%s'>We got it</a>." ),
		__( 'https://wordpress.org/support/article/editing-configuration-php/' )
	) . '</p>';
	$die .= '<p>' . sprintf(
		/* translators: %s: configuration.php */
		__( "You can create a %s file through a web interface, but this doesn't work for all server setups. The safest way is to manually create the file." ),
		'<code>configuration.php</code>'
	) . '</p>';
	$die .= '<p><a href="' . $path . '" class="button button-large">' . __( 'Create a Configuration File' ) . '</a>';

	wp_die( $die, __( '&rsaquo; Error' ) );
}
