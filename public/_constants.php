<?php
/**
  *   Charti CMS
  *   
  *   @since 0.1
  *
  **/ 
  
// Let CoreProtector fight with all bad monkeys out there while you drink your tea

  //define( 'ROOTPATH', '/');
  define( 'WPINC', '../app' );
  define( 'CONFIG_DIR', '../config/' );
  define( 'ADMIN_DIR', 'backdoor' );
  define( 'ADMIN_ASSETS', ADMIN_DIR . '/assets/admin' );
  define( 'WP_CONTENT_DIR', 'dist' );
  define( 'UPLOADS', 'resources/uploads' );
  define( 'CHARTI_CONFIGURATIONS__DIR', WP_CONTENT_DIR . '/configurations' );


  // Development

  // Enabled by default.
  // Will make Thirsty Core to load unmified styles & scripts
  define( 'SCRIPT_DEBUG', true );