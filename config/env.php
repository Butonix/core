<?php
/**
  *   Charti CMS
  *   
  *   @since 0.1
  *
  **/ 
  
  /*
    This will be useful in future compatibility
    for Plugins & Themes between Chartí CMS  and Wordpress
  */
  define( 'IS_CHARTI', true ); // this should stay true

  /*
    CMS Core
    All directories are located outside of the public directory
      
      ROOT_PATH - Points to the root of the project
      ROUTES_PATH - Points to the Routing directory
      WPINC - Points to the WP & Charti Core directory
      CONFIG_DIR - Points to configuration directory
  */
  define( 'ROOTPATH', ABSPATH . '../' );

  define( 'ROUTESPATH', ABSPATH . '../routes/' );

  define( 'WPINC', '../app' );

  define( 'CONFIG_DIR', ROOTPATH . 'config/' );
  
  // CMS Admin
  define( 'ADMIN_ROOT', 'backdoor' ); // Points to private admin directory

  define( 'ADMIN_DIR', 'backdoor' ); // The ex. wp-admin, which is located in public/admin
  
  define( 'ADMIN_ASSETS', ADMIN_DIR . '/assets/admin' ); // 
  // Media, Plugin & Themes
  define( 'WP_CONTENT_DIR', 'resources' ); // Ex wp-content
  define( 'UPLOADS', 'media' );
  // define( 'WP_PLUGIN_DIR', ABSPATH . '../resources/plugins' );
  define( 'CHARTI_CONFIGURATIONS__DIR', WP_CONTENT_DIR . '/configurations' );

  // Development 
  define( 'SCRIPT_DEBUG', true );