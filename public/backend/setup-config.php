<?php
/**
 * Retrieves and creates the configuration.php file.
 *
 * The permissions for the base directory must allow for writing files in order
 * for the configuration.php to be created using this page.
 *
 * @package WordPress
 * @subpackage Administration
 */

if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __DIR__ ) . '/' );
}

/**
 * Require for globals
 * @since Thirsty Core 0.1
 */
require ABSPATH . '../config/env.php';
require_once ABSPATH . WPINC . '/setup/class-error-handler.php';
require_once ABSPATH . WPINC . '/setup/class-render-template.php';
require_once ABSPATH . WPINC . '/setup/class-setup-handler.php';

// DO not change this!
$config_sample = 'configuration-sample.php';
$config = 'configuration.php';

/**
 * We are installing.
 */
define( 'WP_INSTALLING', true );

/**
 * We are blissfully unaware of anything.
 */
define( 'WP_SETUP_CONFIG', true );

/**
 * Disable error reporting
 *
 * Set this to error_reporting( -1 ) for debugging
 */
error_reporting( -1 );


/**
 * 	Core Setup Config 
 *	@since Charti CMS 1.0
 */

new Core_Setup_Config($config_sample, $config);

