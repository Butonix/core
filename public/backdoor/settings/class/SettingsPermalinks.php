<?php

class Settings_Permalinks_Page extends Settings_page {
  
  public $wp_rewrite, $stored_permalinks, $is_nginx = false, $prefix, $blog_prefix, $writable;

  public function __construct($wp_rewrite) {
    $this->wp_rewrite = $wp_rewrite;
    // grab stored permalinks structure
    $this->stored_permalinks = $this->stored_permalinks_structure();
    // Check if the current user has enough permissions to manage this page
    parent::permission_handler('manage_options');
    // get the current site structure
    $this->permalinks_structure($this->stored_permalinks);
    // flush the rewrite rules from wp
    flush_rewrite_rules();
    // load the global admin header template
    parent::get_header();
    // get the template
    parent::get_template_view('permalinks');
    // load the global admin footer template
    parent::get_footer();
  }

  // retrieve stored data from db
  public function stored_permalinks_structure() {
    $get_permalinks = array(

      'home_path'           => get_home_path(),
      'iis7_permalinks'     => iis7_supports_permalinks(),
      'permalink_structure' => get_option( 'permalink_structure' ),
      'category_base'   => get_option( 'category_base' ),
      'tag_base'        => get_option( 'tag_base' ),
      'update_required' => false

    );

    return $get_permalinks;
  }


  function permalinks_structure($get_stored_permalinks) {
    
    $this->stored_permalinks = $get_stored_permalinks;

    $this->iis7_checker();

    if ( $this->stored_permalinks['iis7_permalinks'] ) {
      if ( ( ! file_exists( $this->stored_permalinks['home_path'] . 'web.config' ) && win_is_writable( $this->stored_permalinks['home_path'] ) ) || win_is_writable( $this->stored_permalinks['home_path'] . 'web.config' ) ) {
        $this->writable = true;
      } else {
        $this->writable = false;
      }

      // TODOCHARTI this will fail for ngnix servers. needs to be fixed
    } elseif ( $this->is_nginx ) {
      $this->writable = false;
    } else {
      if ( ( ! file_exists( $this->stored_permalinks['home_path'] . '.htaccess' ) && is_writable( $this->stored_permalinks['home_path'] ) ) || is_writable( $this->stored_permalinks['home_path'] . '.htaccess' ) ) {
        $this->writable = true;
      } else {
        $this->writable        = false;
        $existing_rules  = array_filter( extract_from_markers( $this->stored_permalinks['home_path'] . '.htaccess', 'WordPress' ) );
        $new_rules       = array_filter( explode( "\n", $this->wp_rewrite->mod_rewrite_rules() ) );
        $update_required = ( $new_rules !== $existing_rules );
      }

    }
    
    if ( isset( $_POST['permalink_structure'] ) || isset( $_POST['category_base'] ) ) {
      check_admin_referer( 'update-permalink' );
      $this->form_handler();
    }

  }

  private function blog_prefix() {
    /**
     * In a subdirectory configuration of multisite, the `/blog` prefix is used by
     * default on the main site to avoid collisions with other sites created on that
     * network. If the `permalink_structure` option has been changed to remove this
     * base prefix, WordPress core can no longer account for the possible collision.
     */
    $this->blog_prefix = '';

    if ( is_multisite() && ! is_subdomain_install() && is_main_site() && 0 === strpos( $this->stored_permalinks['permalink_structure'], '/blog/' ) ) {
      $this->blog_prefix = '/blog';
    }
    return $this->blog_prefix;
  }

  private function iis7_checker() {
    if ( $this->stored_permalinks['iis7_permalinks'] ) {
      if ( ( ! file_exists( $this->stored_permalinks['home_path'] . 'web.config' ) && win_is_writable( $this->stored_permalinks['home_path'] ) ) || win_is_writable( $this->stored_permalinks['home_path'] . 'web.config' ) ) {
        return $this->writable = true;
      } else {
        return $this->writable = false;
      }
    }
  }

  private function category_handler() {
    if ( isset( $_POST['category_base'] ) ) {
      $category_base = $_POST['category_base'];

      if ( ! empty( $category_base ) ) {
        $category_base = $this->blog_prefix() . preg_replace( '#/+#', '/', '/' . str_replace( '#', '', $category_base ) );
      }

      return $this->wp_rewrite->set_category_base( $category_base );
    }
  }

  private function tag_handler() {
      if ( isset( $_POST['tag_base'] ) ) {
        $tag_base = $_POST['tag_base'];
        if ( ! empty( $tag_base ) ) {
          $tag_base = $this->blog_prefix . preg_replace( '#/+#', '/', '/' . str_replace( '#', '', $tag_base ) );
        }
        $this->wp_rewrite->set_tag_base( $tag_base );
      }
  }

  // the message output expects to have $content string,
  // a secondary $string that is going to be used in
  // case we have $formatted data > sprintf()
  private function message_output($content, $string = null, $formatted = null ) {
    if( $string && formatted ) {
      return sprintf($content, $string);
    } else {
      return __( $content );
    }
  }

  private function form_handler() {
    
    $using_index_permalinks = $this->wp_rewrite->using_index_permalinks();

  //if ( isset( $_POST['permalink_structure'] ) ) {
    if ( isset( $_POST['selection'] ) && 'custom' != $_POST['selection'] ) {
      $permalink_structure = $_POST['selection'];
    } else {
      $permalink_structure = $_POST['permalink_structure'];
    }

    if ( ! empty( $permalink_structure ) ) {
      $permalink_structure = preg_replace( '#/+#', '/', '/' . str_replace( '#', '', $permalink_structure ) );
      if ( $this->prefix && $this->blog_prefix ) {
        $permalink_structure = $this->prefix . preg_replace( '#^/?index\.php#', '', $permalink_structure );
      } else {
        $permalink_structure = $this->blog_prefix . $permalink_structure;
      }
    }

    $permalink_structure = sanitize_option( 'permalink_structure', $permalink_structure );

    $this->wp_rewrite->set_permalink_structure( $permalink_structure );
  //}

    // handle the category base. if any..
    $this->category_handler();
    
    // handle the tag slug. if any
    $this->tag_handler();
    
    // success notification
    $message = $this->message_output('Permalink structure updated.');

    if ( $this->stored_permalinks['iis7_permalinks'] ) {
      if ( $permalink_structure && ! $using_index_permalinks && ! $this->writable ) {
        $message = $this->message_output('You should update your %s file now.', '<code>web.config</code>',  true);
        return $message;
      
      } elseif ( $permalink_structure && ! $using_index_permalinks && $this->writable ) {
        $this->message_output('Permalink structure updated. Remove write access on %s file now!', '<code>web.config</code>',  true);
        return $message;
      }

    } elseif ( ! $this->is_nginx && $permalink_structure && ! $using_index_permalinks && ! $this->writable && $update_required ) {
        $this->message_output('You should update your %s file now.', '<code>web.config</code>',  true);
        return $message;
    }


    if ( ! get_settings_errors() ) {
      
      add_settings_error( 'general', 'settings_updated', $message, 'success' );
    }

    set_transient( 'settings_errors', get_settings_errors(), 30 );

    wp_redirect( admin_url( 'settings/permalink.php?settings-updated=true' ) );
    exit;
  }

}