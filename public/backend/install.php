<?php
/**
 *	We are installing.
 *
 *	Moved to OOP
 *	@since 1.0	by Charti CMS
 *	
 */

define( 'WP_INSTALLING', true );

class Core_Installer {

	public $install_data_helper = array();
	/**
	 * @global string $wp_version             The CMS version string.
	 * @global string $required_php_version   The required PHP version string.
	 * @global string $required_mysql_version The required MySQL version string.
	 */
	public $wp_version, $required_php_version, $required_mysql_version;
	/**
	 * @local string $php_version     The PHP Version
	 * @local string $mysql_version   The required PHP version string.
	 * @local string $php_compat 			Compare the PHP compatbility
	 * @local string $mysql_compat 		Compare the MySQL version string.
	 */
	public $php_version, $mysql_version, $php_compat, $mysql_compat;
	
	// Handles the ultimate status before installing data
	public $valid_status = false;

	// Returns $wpdb database connection
	protected $wpdb;
	
	// Returns sql query
	protected $sql;
	
	// Returns @array with admin user if exist
	protected $user_table; 
	
	// Default publicity option which can be changed during installation
	public $is_public, $weblog_title, $user_name, $admin_email, $admin_password, $admin_password_check;
	
	// Default language
	public $loaded_language = 'en_US';
	
	// Deafult scripts to print
	public $scripts_to_print = array( 'jquery' );


	public function __construct() {
		
		// Load required files 
		$this->load__required_files();
		
		// Check if the CMS has been already installed. Prompt the message and exit the setup wizard
		if ( is_blog_installed() ) {
			$this->instance_already_created();
		}

		// Make a wpdb connection
		$this->wpdb();
		
		// Get the required versions (cms version, php, mysql)
		$this->load__required_versions();
		
		// force clear cache during installation
		nocache_headers();

		// the installation default step. starts with zero
		$step = isset( $_GET['step'] ) ? (int) $_GET['step'] : 0;
    
    // check if user has been already created
    $this->user_admin_already_created__handler();
    
    // POST form handler
    $this->installation_post_form__handler();
		
		// Initialize the header viewer template
		$this->header_view();

    switch ( $step ) {
    	case 0:
    		$next_step = 1;
    		// add necessary scripts
    		$this->scripts_to_print[] = 'user-profile';

    		$this->install_data_helper = [
    			'user_table_exists' => $this->user_table,
    			'loaded_language' => $this->loaded_language
    		];

    		echo '<form id="setup" method="post" action="install.php?step='.$next_step.'" novalidate="novalidate" style="margin:auto;">';
    		
    		$this->install_view($this->install_data_helper);
    		
    		echo '</form>';

    	break;

    	case 1:

				if ( ! empty( $this->wpdb->error ) ) {
					//wp_die( $this->wpdb->error->get_error_message() );
				}

				// Let's handle the POST form errors, if any
				$this->post__error_handler();

				// Install database core 
				if( $this->valid_status ) {
					
					$this->database_installation();
				}

    	break;
    }

    // Render footer
    new Render_Install_Template('footer', $this->scripts_to_print);

	}

	// Let's check to make sure the CMS isn't already installed.
	protected function instance_already_created() {
		
		new Render_Install_Template('header');

		new Render_Install_Template('error-already-installed');

		die;

	}

	protected function load__required_files() {
		/** Load WordPress Bootstrap */
		require_once dirname( __DIR__ ) . '/autoloader.php';
		// Require the Error Handler
		require_once ABSPATH . WPINC . '/setup/class-error-handler.php';
		/** Load WordPress Administration Upgrade API */
		require_once ABSPATH . ADMIN_DIR . '/includes/upgrade.php';
		/** Require Render Template */
		require_once ABSPATH . WPINC . '/setup/class-render-template.php';
		/** Load wpdb */
		require_once ABSPATH . WPINC . '/wp-db.php';
	}

	protected function load__required_versions() {
		/**
		 * @global string $wp_version             The WordPress version string.
		 * @global string $required_php_version   The required PHP version string.
		 * @global string $required_mysql_version The required MySQL version string.
		 */
		global $wp_version, $required_php_version, $required_mysql_version;

		$this->wp_version = $wp_version;

		$this->required_php_version = $required_php_version;

		$this->required_mysql_version = $required_mysql_version;

		$this->load__system_versions();
	}


	protected function load__system_versions() {
		$this->php_version   = phpversion();

		$this->mysql_version = $this->wpdb->db_version();

		$this->php_compat    = version_compare( $this->php_version, $this->required_php_version, '>=' );

		$this->mysql_compat  = version_compare( $this->mysql_version, $this->required_mysql_version, '>=' ) || file_exists( WP_CONTENT_DIR . '/db.php' );

		$this->system_compatibility_handler();

	}


	protected function post__error_handler() {

		// Should find a better method to handle $this->error_status;

		// Admin Username Validator
		if ( empty( $this->user_name ) ) {
			
			new ErrorHandler(__( 'Please provide a <strong>valid username.</strong>' ));

		} elseif ( sanitize_user( $this->user_name, true ) != $this->user_name ) {
			
			new ErrorHandler(__( 'The <strong>username</strong> you provided has invalid characters.' ));

		}

		// Admin Password Validator
		if($this->admin_password != $this->admin_password_check) {

			new ErrorHandler(__( 'Your <strong>passwords do not match</strong>. Please try again.' ));

		}

		// Website name Validator
		if ( empty( $this->weblog_title ) ) {

			new ErrorHandler(__( 'A <strong>website name</strong> for your application is required.' ));
		
		}

		// Email address Validator
		if ( empty( $this->admin_email ) ) {

			new ErrorHandler(__( 'Provide a <strong>valid email address</strong>.' ) );

		} elseif( ! is_email( $this->admin_email ) ) {

			new ErroHandler(__('Sorry, that isn\'t a valid <strong>email address</strong>. Email addresses look like <code>username@example.com</code>.'));
		}

		/* 
			The last and the most important.
			It depends by this status to install the database core
		*/
		$this->valid_status = true;

	}


	/*
		Database Installation

		Run the database installer when everything goes well
		and we have enough avocados, cheese and water!
			- Stay Hydrated!
	*/
	private function database_installation() {
		
		$this->wpdb->show_errors();

		$results = wp_install( $this->weblog_title, $this->user_name, $this->admin_email, $this->is_public, '',  wp_slash( $this->admin_password ), $this->loaded_language);		

		$this->success_installation_message();

	}
	// Output the final message
	public function success_installation_message() {
		new Render_Install_Template('success-installation');
	}

	/* 	
			A method that fires when there is
			any issues with system compatibility
			
			P.S. Chartí requires >= 7.2

			@since 1.0

	*/
	protected function system_compatibility_handler() {

		// Check if the PHP version is compatible with our requirements
		if ( ! $this->php_compat ) {
			
			$args = [
				$this->required_php_version,
				$this->php_version,
				$this->required_mysql_version
			];

			new ErrorHandler('You cannot install because <a href="https://charti.dev">Chartí CMS</a> requires PHP version <strong>%1$s</strong> or higher. You are running version <strong>%2$s</strong>. Please upgrade to a newer version!', $args );

		}
		// Check both MySQL and PHP versions
		if ( ! $this->mysql_compat && ! $this->php_compat ) {
			
			$args = [
				$this->required_php_version,
				$this->required_mysql_version,
				$this->php_version,
				$this->mysql_version
			];

			new ErrorHandler('You cannot install because <a href="https://charti.dev">Chartí CMS</a> requires PHP version <strong>%1$s</strong> or higher and <strong>MySQL version %2$s</strong> or higher. You are running <strong>PHP %3s</strong> version and <strong>MySQL version %4s</strong>. Please, upgrade your sistem configuration.', $args);
		}

		// Check the MySQL version
		if ( ! $this->mysql_compat ) {
			
			$args = [
				$this->required_mysql_version,
				$this->mysql_version
			];

			new ErrorHandler('You cannot install because <a href="https://charti.dev">Chartí CMS</a> requires <strong>MySQL version %1$s</strong> or higher. You are running on <strong>MySQL version %2s</strong>. Please, upgrade your sistem configuration.', $args);
		}

		// Check if the database prefix has been defined
		if ( ! is_string( $this->wpdb->base_prefix ) || '' === $this->wpdb->base_prefix ) {

			new ErrorHandler(__( 'Your %s file has an empty database table prefix, which is not supported. Please update your file.' ),
			'<code>configuration.php</code>');

		}

		// Set error message if DO_NOT_UPGRADE_GLOBAL_TABLES isn't set as it will break install.
		if ( defined( 'DO_NOT_UPGRADE_GLOBAL_TABLES' ) ) {
			new ErrorHandler(__( 'The constant %s cannot be defined during installation.' ), '<code>DO_NOT_UPGRADE_GLOBAL_TABLES</code>');
		}

	}

  /**
   * Header Template
   */
  public function header_view() {
    new Render_Install_Template('header');  
  }

  public function install_view($data) {  	
  	
  	new Render_install_Template('install', $data);

  }

 	/* Wrap global $wpdb so we can use it later */
  protected function wpdb() {
  	global $wpdb;
  	
  	$this->wpdb = $wpdb;
  	
  	//return $this->wpdb;
  }

  /* Check if there any admin user */
  protected function user_admin_already_created__handler() {
	$this->sql = $this->wpdb->prepare( 'SHOW TABLES LIKE %s', $this->wpdb->esc_like( $this->wpdb->users ) );
		$this->user_table = ( $this->wpdb->get_var( $this->sql ) != null );
  }

  protected function installation_post_form__handler() {
  	global $wpdb;

		// Ensure that sites appear in search engines by default.
		$this->is_public = 1;
		
		// Check if the public was disabled
		if ( isset( $_POST['weblog_title'] ) ) {
			$this->is_public = isset( $_POST['blog_public'] );
		}

		// Website name
		$this->weblog_title = isset( $_POST['weblog_title'] ) ? trim( wp_unslash( $_POST['weblog_title'] ) ) : '';
		// Website user name (admin)
		$this->user_name    = isset( $_POST['user_name'] ) ? trim( wp_unslash( $_POST['user_name'] ) ) : '';
		// Admin credentials
		$this->admin_email  = isset( $_POST['admin_email'] ) ? trim( wp_unslash( $_POST['admin_email'] ) ) : '';
		$this->admin_password = isset( $_POST['admin_password'] ) ? wp_unslash( $_POST['admin_password'] ) : '';
		$this->admin_password_check = isset( $_POST['admin_password2'] ) ? wp_unslash( $_POST['admin_password2'] ) : '';


  }

}

// Run the Core Installation
new Core_Installer();
