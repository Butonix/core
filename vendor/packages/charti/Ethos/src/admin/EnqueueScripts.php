<?php

namespace Core\Ethos\Admin;
use Core\Ethos\CoreEthos as CoreEthos;

// Prevents direct access
defined( 'ABSPATH' ) || exit;

class EnqueueScripts {
	/**
	 * Instantiate the object and register the hooks.
	 * @return [type] [description]
	 */
	public static function init() {
		$self = new self();
		add_action( 'admin_enqueue_scripts', array( $self, 'load_admin_styles' ) );
	}

	/**
	 * Load your admin styles.
	 * @param  [type] $hook [description]
	 * @return
	 */
	public function load_admin_styles() {
		wp_enqueue_style( 'Core-admin', CoreEthos::plugin_url() . '/assets/dist/css/admin.css' );
	}
}
