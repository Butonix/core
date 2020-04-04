<?php
/**
 * Plugin Name: Core Ethos
 * Version: 0.0.1
 * Author URI: Haste Design
 * License: GPLv2
 * Text Domain: Core-Ethos
 */

declare( strict_types = 1 );

namespace Core\Ethos;

// Prevents direct access
defined( 'ABSPATH' ) || exit;

if ( ! defined( 'Core_Ethos_PLUGIN_FILE' ) ) {
	define( 'Core_Ethos_PLUGIN_FILE', __FILE__ );
}

// Autoload
if ( version_compare( PHP_VERSION, '5.6.0', '>=' ) ) {
	require __DIR__ . '/vendor/autoload.php';
}

if ( ! class_exists( 'CoreEthos' ) ) {
	class CoreEthos {
		/**
		 * Current version number
		 *
		 * @var   string
		 * @since 1.0.0
		 */
		const VERSION = '1.0.0';

		/**
		 * Instance of this class.
		 *
		 * @var object
		 */
		protected static $instance = null;

		/**
		 * Plugin directory path
		 *
		 * @var string
		 */
		private $plugin_dir = null;

		/**
		 * Return the plugin instance.
		 */
		public static function init() {
			$self             = new self();
			$self->plugin_dir = plugin_dir_path( __FILE__ );

			add_action( 'init', array( $self, 'load_textdomain' ) );
			add_action( 'init', array( $self, 'includes' ), 0 );
		}

		/**
		 * A final check if Core Ethos exists before kicking off our Core Ethos loading.
		 * Core_Ethos_VERSION is defined at this point.
		 *
		 * @since  1.0.0
		 */
		public function includes() {
			require_once $this->plugin_dir . '/functions.php';
		}

		/**
		 * Get the plugin url.
		 *
		 * @return string
		 */
		public static function plugin_url() {
			return untrailingslashit( plugins_url( '/', Core_Ethos_PLUGIN_FILE ) );
		}

		/**
		 * Load plugin translation
		 */
		public function load_textdomain() {
			load_plugin_textdomain( 'Core-Ethos', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
		}
	}
}
add_action( 'plugins_loaded', array( 'Core\Ethos\CoreEthos', 'init' ) );
