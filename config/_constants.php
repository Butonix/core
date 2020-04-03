<?php
/**
  *   Charti CMS
  *   
  *   @since 0.1
  *
  **/ 
  
// Let CoreProtector fight with all bad monkeys out there while you drink your tea

  define( 'ROOTPATH', ABSPATH . '../' );
  define( 'ROUTESPATH', ABSPATH . '../routes/' );
  define( 'WPINC', '../app' );
  define( 'CONFIG_DIR', ROOTPATH . 'config/' );
  
  define( 'ADMIN_ROOT', 'backdoor' );
  define( 'ADMIN_DIR', 'backdoor' );
  define( 'ADMIN_ASSETS', ADMIN_DIR . '/assets/admin' );
  // define( 'WP_PLUGIN_DIR', ABSPATH . '../resources/plugins' );
  
  define( 'WP_CONTENT_DIR', 'resources' );
  
  define( 'UPLOADS', 'media' );
  
  define( 'CHARTI_CONFIGURATIONS__DIR', WP_CONTENT_DIR . '/configurations' );


  // Development

  // Enabled by default.
  // Will make Thirsty Core to load unmified styles & scripts

  define( 'SCRIPT_DEBUG', true );