<?php
/**
  * Chartí - A better Permissions Checker for Plugins area
  *
  * @since 0.1
  *
**/

// Check if the current user has enough permissions to do the do
function current_user_can_activate_plugin($plugin) {

  if ( !current_user_can( 'activate_plugin', $plugin ) ) {
    wp_die( __( 'Not allowed to activate this plugin.' ) );
  }
}

function current_user_can_deactivate_plugin($plugin) {
  if ( !current_user_can( 'deactivate_plugin', $plugin ) ) {
    wp_die( __( 'Not allowed to deactivate this plugin.' ) );
  }
}


/**
  * CAN_ACTIVATE_DEACTIVATE Check permissions for plugins in bulk action
  * @since 0.1
  *
**/
function current_user_can_activate_plugins() {
  if ( !current_user_can( 'activate_plugins' ) ) {
    wp_die( __( 'Not allowed to manage plugins for this site.' ) );
  }
}

function current_user_can_deactivate_plugins() {
  if ( !current_user_can( 'deactivate_plugins' ) ) {
    wp_die( __( 'Not allowed to deactivate plugins for this site. ' ) );
  }
}

/**
  * CAN_DELETE
  * @since 0.1
  *
**/
function current_user_can_delete_plugins() {
  if ( ! current_user_can( 'delete_plugins' ) ) {
    wp_die( __( 'Not allowed to delete plugins for this site.' ) );
  }
}

// function current_user_can_delete_plugins() {
//   if ( ! current_user_can( 'delete_plugins' ) ) {
//     wp_die( __( 'Not allowed to delete plugins for this site.' ) );
//   }
// }