<?php
/**
 * 
 */
class Core_Setup_Config {
  /* used for defining setup steps */
  public $step, $step_1, $step_2, $step_3;

  public $sample;

  public $message_output;

  public $missing_config_sample = "Sorry, the <code>%s</code> file is required to make it work. <strong>Please re-upload this file to your installation.</strong>";

  public $config_exists = 'The file <code>%1$s</code> already exists. If you need to reset any of the configuration items in this file, please delete it first. You may try <a href="%2$s">installing now</a>.';

  /* used for defining the required config(sample) file */
  public $required_file;

  // returns the configuration path
  public $config_file;

  // returns the whole configuration code (when auto is not possible)
  public $config_code_output;
  
  // used for db connection
  public $database, $username, $password, $host, $prefix;


  public function __construct($configuration_sample, $configuration) {
    
    $this->config_sample = $configuration_sample;
    $this->config = $configuration;

    /** Load Core */
    require CONFIG_DIR . 'settings.php';

    /** Load WordPress Administration Upgrade API */
    require_once ABSPATH . ADMIN_DIR . '/includes/upgrade.php';
    
    nocache_headers(); // always returns null. should be fixed! now move on.
    
    // Check config files. Both, sample or in use
    $this->check_configs();
    
    // Initialize the header viewer template
    $this->header_view();

    // Let's start with the first step
    $this->step = isset( $_GET['step'] ) ? (int) $_GET['step'] : 0;

    $this->setup_wizard();

  }

  /*
    Whether config or config sample. do the do
  */
  function check_configs() {

    // Look for configuration.php and exit if exists
    $this->check_configuration_file();

    // Look for configuration-sample.php and let the wizard begin
    $this->require_configuration_sample();

  }

  function require_configuration_sample() {

    $this->file_exists( $this->config_sample, $this->missing_config_sample, true );

    // grab the configuration-sample code 
    $this->config_file = file( CONFIG_DIR . $this->config_sample );
    
  }

  function check_configuration_file() {

    $this->file_exists( $this->config, $this->config_exists, true );
  }

  /*
    Support configuration(-sample) file one level up,
    for the develop repo.
  */
  private function file_exists($file, $error_message, $missing_file = false) {

    if( file_exists( CONFIG_DIR . $file ) ) {
      
      // Check if configuration.php has been already created and skip the setup
      if( $file == $this->config ) {
        
        if ( $missing_file ) {
          
          $args = [$file, 'install.php'];

          new ErrorHandler($error_message, $args);
        }

      }

    } elseif (file_exists( dirname(CONFIG_DIR) . $file) ) {
      
      $config_file = file(dirname(CONFIG_DIR) . $file);

    } else {

      if ( $missing_file ) {
        $this->required_file = $file;
      }

      // if the configuration.php dosen't exists then let's start the setup
      if ( $this->required_file == $this->config ) {
        return true;
      }

      new ErrorHandler($error_message, $this->required_file);

    }

  }

  /**
   * In Chartí CMS we will try to minimize the installation procces
   * We will get rid of languages and other useless steps
   * P.S. The language can be changed after the setup.
   */
  function setup_wizard() {
    switch ( $this->step ) {
    /**
     *  Jump directly to database setup
     */
      case 0:
        $autofocus = wp_is_mobile() ? '' : ' autofocus';
        
        $this->setup_wizard_step_db();

      break;
    /**
     *  Grab database credentials and check if fits our needs
     */
      case 1:
        if ( $_POST['dbname'] && $_POST['pwd'] && $_POST['dbhost']) {

          // Get database credentials from post
          $this->database = trim( wp_unslash( $_POST['dbname'] ) );
          $this->username = trim( wp_unslash( $_POST['uname'] ) );
          $this->password = trim( wp_unslash( $_POST['pwd'] ) );
          $this->host = trim( wp_unslash( $_POST['dbhost'] ) );
          $this->prefix = trim( wp_unslash( $_POST['prefix'] ) );

          $step_1  = 'setup-config.php?step=1';
          $install = 'install.php';

          if ( isset( $_REQUEST['noapi'] ) ) {
            $step_1 .= '&amp;noapi';
          }

          // Fire when table prefix is not specified
          if ( empty( $this->prefix ) ) {
            new ErrorHandler('<strong>Error</strong>: "Table Prefix" must not be empty.');
          }

          //Define database credentials
          define( 'DB_NAME', $this->database );
          define( 'DB_USER', $this->username );
          define( 'DB_PASSWORD', $this->password );
          define( 'DB_HOST', $this->host ); 

          // Re-construct $wpdb with these new values.
          unset( $this->database_object );

          $this->require_wp_db();

          /*
          * The wpdb constructor bails when WP_SETUP_CONFIG is set, so we must
          * fire this manually. We'll fail here if the values are no good.
          */
          //$database_object->db_connect();

          // Init the db setup
          $this->setup_wizard_step_db_init();

        } else {
          new ErrorHandler('All the monkeys around will just want to jump! Jump!');
        }

      break;

      default:

        $args = [$_GET['step'], 'setup-config.php'];
        
        new ErrorHandler('Hey, You go too far! There is <strong>no step %s</strong> for database setup!<br><a href="%s">Let\'s try again</a>.', $args);

      break;
    }
  }

  /**
   * Load the database class file and instantiate the `$wpdb` global.
   *
   * @since 2.5.0
   *
   * @global wpdb $wpdb WordPress database abstraction object.
   */
  private function require_wp_db() {
    global $wpdb;

    // Load DB Class
    require_once ABSPATH . WPINC . '/wp-db.php';

    // Check if there is any custom db configuration
    if ( file_exists( ABSPATH . WP_CONTENT_DIR . '/db.php' ) ) {
      require_once ABSPATH . WP_CONTENT_DIR . '/db.php';
    }

    // Check if we have all credentials we need
    $dbuser     = defined( 'DB_USER' ) ? DB_USER : '';
    $dbpassword = defined( 'DB_PASSWORD' ) ? DB_PASSWORD : '';
    $dbname     = defined( 'DB_NAME' ) ? DB_NAME : '';
    $dbhost     = defined( 'DB_HOST' ) ? DB_HOST : '';

    // Init wpdb class with given credentials
    $wpdb = new wpdb( $dbuser, $dbpassword, $dbname, $dbhost );
  
    /*
    * The wpdb constructor bails when WP_SETUP_CONFIG is set, so we must
    * fire this manually. Will fail here if the values are no good.
    */
    $wpdb->db_connect();

    // Check if any errors and drop the message
    if ( $wpdb->error ) {
      new ErrorHandler( $wpdb->error->get_error_message() );
    }

    $errors = $wpdb->hide_errors();

    $wpdb->query( "SELECT $this->prefix" );
    
    $wpdb->show_errors( $errors );

    if ( ! $wpdb->last_error ) {
      // MySQL was able to parse the prefix as a value, which we don't want. Bail.
      //wp_die( __( '<strong>Error</strong>: "Table Prefix" is invalid.' ) );
      new ErrorHandler( $wpdb->last_error );
    }

    // Generate keys and salts
    $this->configuration_handler($wpdb);

  }


  // Generate keys and salts using secure CSPRNG; fallback to API if enabled; further fallback to original wp_generate_password().
  function configuration_handler($wpdb) {
    // Try generate salts locally
    try {
      $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()-_ []{}<>~`+=,.;:/?|';

      $max   = strlen( $chars ) - 1;
      
      for ( $i = 0; $i < 8; $i++ ) {
        $key = '';
        
        for ( $j = 0; $j < 64; $j++ ) {
          $key .= substr( $chars, random_int( 0, $max ), 1 );
        }
        
        $secret_keys[] = $key;
        
      }
    // If can't generate salts locally let's try with Wordpress API
    } catch ( Exception $ex ) {
      $no_api = isset( $_POST['noapi'] );

      if ( ! $no_api ) {
        $secret_keys = wp_remote_get( 'https://api.wordpress.org/secret-key/1.1/salt/' );
      }

      if ( $no_api || is_wp_error( $secret_keys ) ) {
        $secret_keys = array();
        for ( $i = 0; $i < 8; $i++ ) {
          $secret_keys[] = wp_generate_password( 64, true, true );
        }
      } else {
        $secret_keys = explode( "\n", wp_remote_retrieve_body( $secret_keys ) );
        foreach ( $secret_keys as $k => $v ) {
          $secret_keys[ $k ] = substr( $v, 28, 64 );
        }
      }
    }

    $key = 0;

    //exit;

    foreach ( $this->config_file as $line_num => $line ) {

      if ( '$table_prefix =' == substr( $line, 0, 15 ) ) {

        $this->config_file[ $line_num ] = '$table_prefix = \'' . addcslashes( $this->prefix, "\\'" ) . "';\r\n";
        continue;
      }

      if ( ! preg_match( '/^define\(\s*\'([A-Z_]+)\',([ ]+)/', $line, $match ) ) {
        continue;
      }

      $constant = $match[1];
      $padding  = $match[2];

      switch ( $constant ) {
        case 'DB_NAME':
        case 'DB_USER':
        case 'DB_PASSWORD':
        case 'DB_HOST':
          $this->config_file[ $line_num ] = "define( '" . $constant . "'," . $padding . "'" . addcslashes( constant( $constant ), "\\'" ) . "' );\r\n";
          break;
        case 'DB_CHARSET':
          if ( 'utf8mb4' === $wpdb->charset || ( ! $wpdb->charset && $wpdb->has_cap( 'utf8mb4' ) ) ) {
            $this->config_file[ $line_num ] = "define( '" . $constant . "'," . $padding . "'utf8mb4' );\r\n";
          }
          break;
        case 'AUTH_KEY':
        case 'SECURE_AUTH_KEY':
        case 'LOGGED_IN_KEY':
        case 'NONCE_KEY':
        case 'AUTH_SALT':
        case 'SECURE_AUTH_SALT':
        case 'LOGGED_IN_SALT':
        case 'NONCE_SALT':
          $this->config_file[ $line_num ] = "define( '" . $constant . "'," . $padding . "'" . $secret_keys[ $key++ ] . "' );\r\n";
          break;
      }
    }

    unset( $line );

    /*
      if the configuration directory is not writable
      let's prompt the code that should be added manually
    */
    if ( is_writable( CONFIG_DIR ) ):
      
      $this->manual_configuration_setup();

    else:

      $this->automatic_installation();

    endif;

  }

  /*
     Will fire when the setup is ready and the
     configuration directory is writable
  */
  function automatic_installation() {
      if ( file_exists( CONFIG_DIR . $this->config_sample ) ) {
        // copy the sample file structure and fill with current credentials
        $path_to_config = CONFIG_DIR . $this->config;
      
      } else {
        // if fails to find lets try with dirname
        $path_to_config = dirname( CONFIG_DIR ) . '/' . $this->config;
      
      }

      $handle = fopen( $path_to_config, 'w' );

      foreach ( $this->config_file as $line ) {

        fwrite( $handle, $line );
      
      }
      
      fclose( $handle );

      // set the file permissions to 0666
      chmod( $path_to_config, 0666 );
      // prompt the success message + button, go to install
      $this->setup_successful();
  }

  /*
     Will fire when the setup is ready but the
     configuration file can't be added automatically
  */
  function manual_configuration_setup() {
      
      $this->config_code_output = '';

      foreach ( $this->config_file as $line ) {
        $this->config_code_output .= htmlentities( $line, ENT_COMPAT, 'UTF-8' );
      }
      
      new Render_Install_Template('code', $this->config_code_output);

  }

  function setup_successful() {
    new Render_Install_Template('success');
  }

  /**
   * Header Template
   */
  public function header_view() {
    new Render_Install_Template('header');  
  }

  // Setup Wizard: Datanbase 
  function setup_wizard_step_db() {
    new Render_Install_Template('step-1');
  }

  function setup_wizard_step_db_init() {
    new Render_Install_Template('step-2');
  }

}