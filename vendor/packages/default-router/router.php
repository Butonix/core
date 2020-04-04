<?php
/*
  Plugin Name: Charti Dynamic Router
  Version: 1.0.0
  Description: Create dynamic routers directly from code without any dashboard or database actions. This can be used for all kind of routes. <a href="#">Check Documentation</a>
  Author: Charti CMS
  Website: https://charti.dev/
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
  die;
}
/**
 * Currently plugin version.
 */
define( 'CHARTI_ROUTER_VERSION', '1.0.0' );
define( 'CHARTI_ROUTER__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

require plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';

Rareloop\WordPress\Router\Router::init();
