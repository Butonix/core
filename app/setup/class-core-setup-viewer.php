<?php
/**
  *   Charti CMS
  *
  *   The simplified setup wizard. Without language proccess.
  *   English comes as default language.
  *   Changing this can be done from admin dashboard after install.
  *
  *   @since 1.0
  */
class Core_Setup_Viewer {
  
  public $body_classes  = array();

  public $step,
        $step_1,
        $step_2,
        $step_3;

  public $database_object,
        $database,
        $username,
        $password,
        $host,
        $prefix;
  
  public function __construct() {
    // grab the $wpdb structure

    var_dump($file_in_use);
    //$this->database_object = $wpdb;
    
    $this->body_classes[] = 'wp-core-ui';
    
    // Initialize the header viewer template
    $this->header_view();
    
    // Let's start with the first step
    $this->step = isset( $_GET['step'] ) ? (int) $_GET['step'] : 0;

    $this->setup_wizard();

  }  

  public function header_view() {
    $this->load_template('header');  
  }

  function setup_wizard() {
    switch ( $this->step ) {

      // let's skip this one
      // case 0:
      //   $this->setup_wizard_step_db_pre();
      // break;

      case 0:
        $autofocus = wp_is_mobile() ? '' : ' autofocus';
        
        $this->setup_wizard_step_db();

      break;

      case 1:
        if ( $_POST['dbname'] && $_POST['pwd'] && $_POST['dbhost'] && $_POST['prefix']) {

          // Get database credentials from post
          $this->database = trim( wp_unslash( $_POST['dbname'] ) );
          $this->username = trim( wp_unslash( $_POST['uname'] ) );
          $this->password = trim( wp_unslash( $_POST['pwd'] ) );
          $this->host = trim( wp_unslash( $_POST['dbhost'] ) );
          $this->prefix = trim( wp_unslash( $_POST['prefix'] ) );

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
          new ErrorHandler('test');
        }
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

    $this->configuration_handler();

  }

  // Setup Wizard: Datanbase 
  function setup_wizard_step_db() {
    
    $this->load_template('step-1');

  }

  function setup_wizard_step_db_init() {
    $this->load_template('step-2');
  }
  
  // Generate keys and salts using secure CSPRNG; fallback to API if enabled; further fallback to original wp_generate_password().
  function configuration_handler() {
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

    foreach ( $config_file as $line_num => $line ) {
      if ( '$table_prefix =' == substr( $line, 0, 15 ) ) {
        $config_file[ $line_num ] = '$table_prefix = \'' . addcslashes( $this->prefix, "\\'" ) . "';\r\n";
        continue;
      }

      if ( ! preg_match( '/^define\(\s*\'([A-Z_]+)\',([ ]+)/', $line, $match ) ) {
        continue;
      }

      $constant = $match[1];
      $padding  = $match[2];

      var_dump($this->config_file_in_use);

      switch ( $constant ) {
        case 'DB_NAME':
        case 'DB_USER':
        case 'DB_PASSWORD':
        case 'DB_HOST':
          $config_file[ $line_num ] = "define( '" . $constant . "'," . $padding . "'" . addcslashes( constant( $constant ), "\\'" ) . "' );\r\n";
          return $config_file;
          break;
        case 'DB_CHARSET':
          if ( 'utf8mb4' === $wpdb->charset || ( ! $wpdb->charset && $wpdb->has_cap( 'utf8mb4' ) ) ) {
            $config_file[ $line_num ] = "define( '" . $constant . "'," . $padding . "'utf8mb4' );\r\n";
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
          $config_file[ $line_num ] = "define( '" . $constant . "'," . $padding . "'" . $secret_keys[ $key++ ] . "' );\r\n";
          break;
      }
    }

    unset( $line );

  }

  // A method that handles the template views
  private function load_template($template_file) {

    include_once ABSPATH . WPINC . '/setup/views/' . $template_file . '.phtml';

  }

}