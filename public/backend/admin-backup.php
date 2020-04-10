<?php

namespace Core\Bootstrap;

use Core\TemplateRender\Render as Render;
use Core\ErrorHandler\ErrorHandler as Error;
use Core\PermissionHandler\CurrentUserCant as CurrentUserCant;

/**
 * Administration Bootstrap
 *
 * @package WordPress
 * @subpackage Administration
 */

/**
 * In WordPress Administration Screens
 *
 * @since 2.3.2
 */
if ( ! defined( 'WP_ADMIN' ) ) {
  define( 'WP_ADMIN', true );
}

if ( ! defined( 'WP_NETWORK_ADMIN' ) ) {
  define( 'WP_NETWORK_ADMIN', false );
}

if ( ! defined( 'WP_USER_ADMIN' ) ) {
  define( 'WP_USER_ADMIN', false );
}

if ( ! WP_NETWORK_ADMIN && ! WP_USER_ADMIN ) {
  define( 'WP_BLOG_ADMIN', true );
}

if ( isset( $_GET['import'] ) && ! defined( 'WP_LOAD_IMPORTERS' ) ) {
  define( 'WP_LOAD_IMPORTERS', true );
}

require_once dirname( __DIR__ ) . '/autoloader.php';

require_once ABSPATH . ADMIN_DIR . '/classes/wrapper/class-admin-bootstrap-wrapper.php';

nocache_headers();

class BootstrapCore {

  public $page_now;

  public $wp_importers;

  public $hook_suffix;

  private $plugin_page;

  public $type_now;

  public $tax_now;

  public $editing;

  public $page_hook = null;

  public function __construct($values) {

    // Bring the Cron
    $this->core_scheduled_event();

    // Set screen options
    set_screen_options();

    wp_enqueue_script( 'common' );
    
    // From by WP globals;
    // We are going to pull all values we need
    $this->page_now_constructor($values);

    // Whether the instance has a multi network setup
    // Includes a specific navigation menu 
    
    $this->is_network_admin();

    /**
     * Fires as an admin screen or script is being initialized.
     *
     * Note, this does not just run on user-facing admin screens.
     * It runs on admin-ajax.php and admin-post.php as well.
     *
     * This is roughly analogous to the more general {@see 'init'} hook, which fires earlier.
     *
     * @since 2.5.0
     */
    do_action( 'admin_init' );

    $this->is_plugin_page();

    $this->hook_prefix();

  }

  /**
   * 
   */
  public function page_now_constructor( $values ) {

    $this->page_now = $values['page_now'];

    $this->wp_importers = $values['wp_importers'];

    $this->hook_suffix = $values['hook_suffix'];

    $this->plugin_page = $values['plugin_page'];

    $this->type_now = 'page';

    $this->page_now_handler();
    
    $this->get_current_screen();

  }

  public function core_scheduled_event() {

    // Schedule Trash collection.
    if ( ! wp_next_scheduled( 'wp_scheduled_delete' ) && ! wp_installing() ) {
      wp_schedule_event( time(), 'daily', 'wp_scheduled_delete' );
    }

    // Schedule transient cleanup.
    if ( ! wp_next_scheduled( 'delete_expired_transients' ) && ! wp_installing() ) {
      wp_schedule_event( time(), 'daily', 'delete_expired_transients' );
    }

  }

  /**
   * Page Type Handler
   * Can handle: page, post_type, taxonomy/term
   */
  public function page_now_handler() {
    
    // $date_format = __( 'F j, Y' );
    // $time_format = __( 'g:i a' );

    $editing = false;

    if ( isset( $_GET['page'] ) ) {
      
      $this->plugin_page = wp_unslash( $_GET['page'] );
      
      $this->plugin_page = plugin_basename( $this->plugin_page );

    }

    if ( isset( $_REQUEST['post_type'] ) && post_type_exists( $_REQUEST['post_type'] ) ) {
      
      $this->type_now = $_REQUEST['post_type'];
    
    } else {
      
      $this->type_now = '';

    }

    if ( isset( $_REQUEST['taxonomy'] ) && taxonomy_exists( $_REQUEST['taxonomy'] ) ) {
    
      $this->tax_now = $_REQUEST['taxonomy'];
    
    } else {
      
      $this->tax_now = '';

    }
  }

  /**
   *  Admin Menu Navigation Handler
   *  Require a specific menu navigation depending on instance setup
   */
  private function is_network_admin() {

    if ( WP_NETWORK_ADMIN ) {
      
      // If is network active
      $this->__require_once( 'admin', '/network/menu.php' );

    } elseif ( WP_USER_ADMIN ) {
      
      // If is network active @users
      $this->__require_once( 'admin', '/user/menu.php' );
    
    } else {
    
      // If is network active @users
      $this->__require_once( 'admin', '/menu.php' );

    }

    if ( current_user_can( 'manage_options' ) ) {
    
      wp_raise_memory_limit( 'admin' );
    
    }

  }

  /**
   * 
   */
  private function is_plugin_page() {
    
    if ( isset( $this->plugin_page ) ) {

      if ( ! empty( $this->type_now ) ) {

        $the_parent = $this->page_now . '?post_type=' . $this->type_now;

      } else {

        $the_parent = $this->page_now;

      }


      $this->page_hook = get_plugin_page_hook( $this->plugin_page, $the_parent );

      if ( ! $this->page_hook ) {

        $this->page_hook = get_plugin_page_hook( $this->plugin_page, $this->plugin_page );

        // Back-compat for plugins using add_management_page().
        if ( empty( $this->page_hook ) && 'edit.php' === $this->page_now && get_plugin_page_hook( $this->plugin_page, 'tools.php' ) ) {
          
          // There could be plugin specific params on the URL, so we need the whole query string.
          if ( ! empty( $_SERVER['QUERY_STRING'] ) ) {
            
            $query_string = $_SERVER['QUERY_STRING'];

          } else {

            $query_string = 'page=' . $this->plugin_page;

          }

          wp_redirect( admin_url( 'tools.php?' . $query_string ) );

          exit;
        }
      }
      unset( $the_parent );
    }

  }
  /**
   * 
   */
  private function hook_prefix() {
    
    //$hook_suffix = '';
    
    if ( isset( $this->page_hook ) ) {
    
      $this->hook_suffix = $this->page_hook;
    
    } elseif ( isset( $this->plugin_page ) ) {
    
      $this->hook_suffix = $this->plugin_page;
    
    } elseif ( isset( $this->page_now ) ) {
      
      $this->hook_suffix = $this->page_now;

    }

    set_current_screen();

  }

  /**
   * 
   */
  private function db_upgrade_checker() {
    if ( get_option( 'db_upgraded' ) ) {
      flush_rewrite_rules();
      update_option( 'db_upgraded', false );

      /**
       * Fires on the next page load after a successful DB upgrade.
       *
       * @since 2.8.0
       */

      do_action( 'after_db_upgrade' );

    } elseif ( get_option( 'db_version' ) != $wp_db_version && empty( $_POST ) ) {

      if ( ! is_multisite() ) {
        //wp_redirect( admin_url( 'upgrade.php?_wp_http_referer=' . urlencode( wp_unslash( $_SERVER['REQUEST_URI'] ) ) ) );
        //exit;
      }

      /**
       * Filters whether to attempt to perform the multisite DB upgrade routine.
       *
       * In single site, the user would be redirected to ADMIN_DIR/upgrade.php.
       * In multisite, the DB upgrade routine is automatically fired, but only
       * when this filter returns true.
       *
       * If the network is 50 sites or less, it will run every time. Otherwise,
       * it will throttle itself to reduce load.
       *
       * @since MU (3.0.0)
       *
       * @param bool $do_mu_upgrade Whether to perform the Multisite upgrade routine. Default true.
       */
      if ( apply_filters( 'do_mu_upgrade', true ) ) {

        /**
         *  Charti CMS disabled this verification
         *  @since 0.1
         */

        $c = '1'; //get_blog_count();

        /*
         * If there are 50 or fewer sites, run every time. Otherwise, throttle to reduce load:
         * attempt to do no more than threshold value, with some +/- allowed.
         */
        if ( $c <= 50 || ( $c > 50 && mt_rand( 0, (int) ( $c / 50 ) ) === 1 ) ) {
          require_once ABSPATH . WPINC . '/http.php';
          $response = wp_remote_get(
            admin_url( 'upgrade.php?step=1' ),
            array(
              'timeout'     => 120,
              'httpversion' => '1.1',
            )
          );
          /** This action is documented in ADMIN_DIR/network/upgrade.php */
          do_action( 'after_mu_upgrade', $response );
          unset( $response );
        }
        unset( $c );
      }
    }

  }

  /**
   * 
   */
  private function do_action($action) {

    do_action( $action );

  }


  public function do_the_do() {
    return $this->type_now;
  }

  public function page_hook() {
    return $this->page_hook;
  }

  public function hook_suffix() {
    return $this->hook_suffix;
  }

  /**
   * 
   */
  function get_current_screen() {
    
    if ( isset( $this->plugin_page ) ) {

      if ( $this->page_hook ) {
        
        $this->do_action('load-' . $this->page_hook);

        // When noheader param is not set
        if ( ! isset( $_GET['noheader'] ) ) {

          new Render(ADMIN_DIR, '/admin-header.php', $this->page_hook);

        }

        $this->do_action( $this->page_hook );

      } else {
        
        // Fires when plugin is not valid
        if ( validate_file( $this->plugin_page ) ) {
          
          new Error( __( 'Invalid plugin page.' ) );

        }

        if ( ! ( file_exists( WP_PLUGIN_DIR . "/$this->plugin_page" ) && is_file( WP_PLUGIN_DIR . "/$this->plugin_page" ) ) && ! ( file_exists( WPMU_PLUGIN_DIR . "/$this->plugin_page" ) && is_file( WPMU_PLUGIN_DIR . "/$this->plugin_page" ) ) ) {

          wp_die( sprintf( __( 'Cannot load %s.' ), htmlentities( $this->plugin_page ) ) );

        }


        /**
         * Fires before a particular screen is loaded.
         *
         * The load-* hook fires in a number of contexts. This hook is for plugin screens
         * where the file to load is directly included, rather than the use of a function.
         *
         * The dynamic portion of the hook name, `$plugin_page`, refers to the plugin basename.
         *
         * @see plugin_basename()
         *
         * @since 1.5.0
         */
        $this->do_action( 'load-' . $this->plugin_page ); // phpcs:ignore WordPress.NamingConventions.ValidHookName.UseUnderscores

        if ( ! isset( $_GET['noheader'] ) ) {
          
          new Render(ADMIN_DIR, '/admin-header.php', $this->plugin_page);

          //$this->do_action('admin', '/admin-header.php');
        }

        if ( file_exists( WPMU_PLUGIN_DIR . "/$this->plugin_page" ) ) {
          include WPMU_PLUGIN_DIR . "/$this->plugin_page";
        } else {
          include WP_PLUGIN_DIR . "/$this->plugin_page";
        }

      }

    } elseif ( isset( $_GET['import'] ) ) {

      $importer = $_GET['import'];

      // Check if the current user has enough permissions, otherwise quit 
      throw new CurrentUserCant('import', 'Sorry, you are not allowed to import content into this site.');

      if ( validate_file( $importer ) ) {
        wp_redirect( admin_url( 'import.php?invalid=' . $importer ) );
        exit;
      }

      if ( ! isset( $this->wp_importers[ $importer ] ) || ! is_callable( $this->wp_importers[ $importer ][2] ) ) {
        wp_redirect( admin_url( 'import.php?invalid=' . $importer ) );

        exit;
      }


      /**
       * Fires before an importer screen is loaded.
       *
       * The dynamic portion of the hook name, `$importer`, refers to the importer slug.
       *
       * @since 3.5.0
       */
      do_action( "load-importer-{$importer}" ); // phpcs:ignore WordPress.NamingConventions.ValidHookName.UseUnderscores

      $parent_file  = 'tools.php';
      $submenu_file = 'import.php';
      $title        = __( 'Import' );

      // When noheader param is not set
      if ( ! isset( $_GET['noheader'] ) ) {

        new Render(ADMIN_DIR, '/admin-header.php', $this->page_hook);

        //$this->__require_once( 'admin', '/admin-header.php' );
      }

      $this->__require_once( 'admin', '/includes/upgrade.php' );

      define( 'WP_IMPORTING', true );

      /**
       * Whether to filter imported data through kses on import.
       *
       * Multisite uses this hook to filter all data through kses by default,
       * as a super administrator may be assisting an untrusted user.
       *
       * @since 3.1.0
       *
       * @param bool $force Whether to force data to be filtered through kses. Default false.
       */
      if ( apply_filters( 'force_filtered_html_on_import', false ) ) {
        kses_init_filters();  // Always filter imported data with kses on multisite.
      }

      call_user_func( $wp_importers[ $importer ][2] );
      
      $this->__require_once( 'admin', '/admin-footer.php' );

      // Make sure rules are flushed.
      flush_rewrite_rules( false );

      exit();


    } else {
          
      /**
       * Fires before a particular screen is loaded.
       *
       * The load-* hook fires in a number of contexts. This hook is for core screens.
       *
       * The dynamic portion of the hook name, `$this->page_now`, is a global variable
       * referring to the filename of the current page, such as 'admin.php',
       * 'post-new.php' etc. A complete hook for the latter would be
       * 'load-post-new.php'.
       *
       * @since 2.1.0
       */
      $this->do_action( 'load-' . $this->page_now ); // phpcs:ignore WordPress.NamingConventions.ValidHookName.UseUnderscores

      /*
       * The following hooks are fired to ensure backward compatibility.
       * In all other cases, 'load-' . $this->page_now should be used instead.
       */
      if ( 'page' === $this->type_now ) {

        if ( 'post-new.php' === $this->page_now ) {
          $this->do_action( 'load-page-new.php' ); // phpcs:ignore WordPress.NamingConventions.ValidHookName.UseUnderscores

        } elseif ( 'post.php' === $this->page_now ) {
          $this->do_action( 'load-page.php' ); // phpcs:ignore WordPress.NamingConventions.ValidHookName.UseUnderscores
        }

      } elseif ( 'edit-tags.php' === $this->page_now ) {

        if ( 'category' === $this->tax_now ) {
          $this->do_action( 'load-categories.php' ); // phpcs:ignore WordPress.NamingConventions.ValidHookName.UseUnderscores

        } elseif ( 'link_category' === $this->page_now ) {
          $this->do_action( 'load-edit-link-categories.php' ); // phpcs:ignore WordPress.NamingConventions.ValidHookName.UseUnderscores
        }

      } elseif ( 'term.php' === $this->page_now ) {
        $this->do_action( 'load-edit-tags.php' ); // phpcs:ignore WordPress.NamingConventions.ValidHookName.UseUnderscores
      }

    }

    if ( ! empty( $_REQUEST['action'] ) ) {

      $action = $_REQUEST['action'];

      /**
       * Fires when an 'action' request variable is sent.
       *
       * The dynamic portion of the hook name, `$action`, refers to
       * the action derived from the `GET` or `POST` request.
       *
       * @since WP 2.6.0
       */
      $this->do_action( 'admin_action_' . $action );
    }

  }

  /**
   * A simple php file handler
   */
  private function __require_once($dir, $file_path) {
      
      if ( $dir == 'admin' ) {
        $dir = ADMIN_DIR;
      }

      if ( $dir == 'app' ) {
        $dir = WPINC;
      }

      require_once ABSPATH . $dir . $file_path;
  }

}


