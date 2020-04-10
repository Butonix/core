<?php

use Core\Phtml\RenderPage as Render;
use Core\Phtml\RenderBlock as RenderBlock;
use Core\ErrorHandler\ErrorHandler as Error;
use Core\PermissionHandler\CurrentUserCant as CurrentUserCant;

/**
 * Dashboard Administration Screen
 *
 * @package WordPress
 * @subpackage Administration
 */

/** Load WordPress Bootstrap */
require_once __DIR__ . '/admin.php';
require_once ABSPATH . ADMIN_DIR . '/classes/wrapper/class-admin-bootstrap-wrapper.php';

/** Load WordPress dashboard API */
require_once ABSPATH . ADMIN_DIR . '/includes/dashboard.php';

$page_settings = [
	'title' => __( 'Dashboard' ),
	'parent_file' => 'index.php'
];

class Dashboard extends Render {
	
	public $enqueue_scripts = array();

	public function __construct($page_settings) {
		
		wp_dashboard_setup();

		$this->prepare_scripts();

		add_thickbox();

    $this->enqueue_scripts($this->enqueue_scripts);

    $this->get_header();

    // get the template
    $this->get_template_view('dashboard/index', $page_settings);
    
    // load the global admin footer template
    $this->get_footer();
	}

	public function prepare_scripts() {
		$this->enqueue_scripts[] = 'dashboard';
    
    if ( current_user_can( 'install_plugins' ) ) {

    	$this->enqueue_scripts[] = ['plugin-install', 'updates'];

    }

		if ( current_user_can( 'upload_files' ) ) {
			
			$this->enqueue_scripts[] = 'media-upload';

		}

		if ( wp_is_mobile() ) {
			$this->enqueue_scripts[] = 'jquery-touch-punch';
		}
	}
}

new Dashboard($page_settings);

?>
