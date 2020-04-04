<?php

declare( strict_types = 1 );

namespace Core\Ethos;

// Prevents direct access
defined( 'ABSPATH' ) || exit;

if ( ! defined( 'Core_Ethos_PLUGIN_FILE' ) ) {
	define( 'Core_Ethos_PLUGIN_FILE', __FILE__ );
}

// Autoload
if ( version_compare( PHP_VERSION, '5.6.0', '>=' ) ) {
	require __DIR__ . '/src/admin/EnqueueScripts.php';
	require __DIR__ . '/src/admin/OptionsHelper.php';
	require __DIR__ . '/src/admin/ThemeOptions.php';
	// Metabox
	require __DIR__ . '/src/meta-boxes/Metabox.php';
	//Forms
	require __DIR__ . '/src/forms/FrontEndForm.php';
	require __DIR__ . '/src/forms/ContactForm.php';

	// Post types
	require __DIR__ . '/src/post-types/PostType.php';
	require __DIR__ . '/src/post-types/PostForm.php';
	require __DIR__ . '/src/post-types/PostStatus.php';

	require __DIR__ . '/src/taxonomies/Taxonomy.php';
	require __DIR__ . '/src/taxonomies/TermMeta.php';
	
	require __DIR__ . '/src/templates/TemplateLoader.php';
	require __DIR__ . '/src/users/UserMeta.php';
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
			load_plugin_textdomain( 'Core', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
		}
	}
}
add_action( 'plugins_loaded', array( 'Core\Ethos\CoreEthos', 'init' ) );
