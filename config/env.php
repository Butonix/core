<?php

/**
  *   Charti CMS
  *   
  *   @since 0.1
  *
  *
  *  This will be useful in future compatibility
  *  for Plugins & Themes between Chartí CMS and Wordpress
  */
  define( 'IS_CHARTI', true ); // this should stay true

  

  /**
   * ROOT_PATH directory
   * 
   * Found: in root
   * Editable: Yes (For subdirectory installs)
   * Default '../'
   * 
   * @since Charti CMS 1.0
  */
  define( 'ROOT_PATH', ABSPATH . '../' );
  

  /**
   * ROUTES_PATH directory
   * 
   * Found: in root
   * Editable: Yes (For subdirectory installs)
   * Default '../'
   * 
   * @since Charti CMS 1.0
  */
  define( 'ROUTES_PATH', ABSPATH . '../routes/' );

  define( 'WPINC', '../app' );

  define( 'CONFIG_DIR', ROOT_PATH . 'config/' );
  

  /**
   * ADMIN_DIR directory
   * 
   * Found: in public/ADMIN_DIR
   * Editable: Yes (Requires Renaming Directory)
   * Default 'backend'
   * 
   * @since Charti CMS 1.0
  */
  define( 'ADMIN_ROOT', 'backend' ); // Points to private admin directory


  /**
   * ADMIN_DIR directory
   * 
   * Found: in public/ADMIN_DIR
   * Editable: Yes (Requires Renaming Directory)
   * Default 'backend'
   * 
   * @since Charti CMS 1.0
  */
  define( 'ADMIN_DIR', 'backend' );
  

  /**
   * ADMIN_ASSETS directory
   * 
   * Found: in public/backend/ADMIN_ASSETS
   * Editable: Yes (Requires Renaming Directory)
   * Default '/assets/admin'
   * 
   * @since Charti CMS 1.0
  */
  define( 'ADMIN_ASSETS', ADMIN_DIR . '/assets/admin' ); // 
  

  /**
   * WP_CONTENT_DIR directory
   * 
   * Found: in public/WP_CONTENT_DIR
   * Editable: Yes (Requires Renaming Directory)
   * Default: "resources"
   *
   * @since Wordpress
   * Fully integrated @since Charti CMS 1.0
  */
  define( 'WP_CONTENT_DIR', 'resources' ); // Ex wp-content
  

  /**
   * UPLOADS directory
   * 
   * Found: in public/* directory
   * Editable: Yes (Requires Renaming Directory)
   * Default: 'media' 
   *
   * @since Wordpress
   * Fully integrated @since Charti CMS 1.0
  */
  define( 'UPLOADS', 'media' );


  /**
   * WP_PLUGIN_DIR directory
   * 
   * Found: in public/* directory
   * Editable: Yes (Requires Renaming Directory)
   * Default: 'plugins' 
   *
   * @since Wordpress
   * Fully integrated @since Charti CMS 1.0
  */
  
  //define( 'WP_PLUGIN_DIR', ABSPATH . '../resources/plugins' );


  /**
   * CONFIGURATION_DIR directory
   * 
   * Found in root project
   * Editable: Yes (Requires Renaming Directory)
   * Default: 'config' 
   *
   * @since Charti CMS 1.0
  */
  define( 'CONFIGURATION_DIR', WP_CONTENT_DIR . '/configurations' );

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
  define( 'WP_DEBUG', true );

  define( 'WP_DEBUG_DISPLAY', true );

  // Use dev versions of core JS and CSS files (only needed if you are modifying these core files)
  define( 'SCRIPT_DEBUG', true );

  define( 'SHORTINIT', false);
  
  define( 'RESTSPLAIN_DEBUG', true );