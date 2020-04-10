<?php

/*

  More about
  https://premium.wpmudev.org/blog/advanced-wordpress-development-writing-object-oriented-plugins/
  https://gist.github.com/danielpataki/cb790e16c4aae22f91e32407c4dcd9c7#file-full-php
  https://code.tutsplus.com/tutorials/basics-of-object-oriented-programming-in-php--cms-31910

  https://stackoverflow.com/questions/15499513/php-call-class-method-function

  https://stackoverflow.com/questions/12662962/calling-a-php-class-from-within-another-class

  https://stackoverflow.com/questions/2350937/php-fatal-error-using-this-when-not-in-object-context

  https://stackoverflow.com/questions/1468616/call-a-class-inside-another-class-in-php

*/


Class UserHandler {

  private static $config_stacks;

  private $is_multisite;
  private $user_permisions = array();

  public function __construct($config) {
    self::$config_stacks = $config;

    // before we start let's check if the current users has enough stamina
    User_Permission_Handler::handler($config);

    // apply on multi sites. Send a notification to the new user via email
    if( self::$config_stacks['is_multisite'] ) {
      add_filter( 'wpmu_signup_user_notification_email', 'admin_created_user_email' );
    }

    // Verify the request
    User_Request_Handler::handler();

  }

  public function stacks() {
    return self::$config_stacks;
  }

}


class User_Request_Handler {

  static function handler() {

    if(isset($_REQUEST['action'])) {
      // adding an existent user
      if($_REQUEST['action'] == 'adduser' ) {
        // check if the nonce is set and still active for adding an existent user
        check_admin_referer( 'add-user', '_wpnonce_add-user' );
        //new Add_Existent_User( $_REQUEST['action'] );
      }
      // creating a new user
      if( $_REQUEST['action'] == 'createuser' ) {
        // check if the nonce is set and still active for creating a new user
        //check_admin_referer( 'create-user', '_wpnonce_create-user' );
        new Create_New_User();
      }
    }

    return;
  }
}

// $this->is_multisite, $this->user_permissions['can_create_users'], $this->user_permissions['can_promote_users']
class User_Permission_Handler {

  public function handler($config) {

    $is_multisite = $config['is_multisite'];
    $user_permissions = $config['current_user'];
    $can_create_users = $user_permissions['can_create_users'];
    $can_promote_users = $user_permissions['can_promote_users'];

    $notification_title = __( 'You need a higher level of permission.' );

    // first of all check if this is a multisite
    // setup a specific message
    if ( $is_multisite ) {
      if( ! $can_create_users && ! $can_promote_users ) {
        $notification_message = __( 'Sorry, you are not allowed to add users to this network.' );
        self::error_template($notification_message, $notification_title);
      }

    // setup a specific message for current user
    } elseif( ! $can_create_users) {
      $notification_message = __( 'Sorry, you are not allowed to create users.' );
      self::error_template($notification_title, $notification_message);
    }

    //return $config;

  }

  private function error_template($title, $message) {
    wp_die(
      '<h1>' . $title . '</h1>' .
      '<p>' . $message . '</p>',
      403
    );
  }

}


class Create_New_User
{
  // public $is_multisite;
  // private $requester; // POST action from form
  // private $action; //adduser value from action
  // private $user_details;
  // private $user_email;

  public function __construct() {
    // Grab curent user info and push to Permission handler
    $get_stacks = UserHandler::stacks();
    User_Permission_Handler::handler($get_stacks);

    if( ! is_multisite() ) {
      $user_id = edit_user();

      $this->redirect_handler($user_id);
    
    } else {

      $this->mu_create_the_user();
    }

  }

  private function redirect_handler($user_id) {

      if ( is_wp_error( $user_id ) ) {
        $add_user_errors = $user_id;
      } else {

        if ( current_user_can( 'list_users' ) ) {
          $redirect = 'users.php?update=add&id=' . $user_id;
        } else {
          $redirect = add_query_arg( 'update', 'add', 'user-new.php' );
        }

        wp_redirect( $redirect );

        die();
      }
  }


  // to be extended. first have to setup the multi sites environment. tomorrow or so..

  private function mu_create_the_user() {

    // Adding a new user to this site.
    $new_user_email = wp_unslash( $_REQUEST['email'] );
    $user_details   = wpmu_validate_user_signup( $_REQUEST['user_login'], $new_user_email );

    if ( is_wp_error( $user_details['errors'] ) && $user_details['errors']->has_errors() ) {
      $add_user_errors = $user_details['errors'];
    } else {
      /** This filter is documented in wp-includes/user.php */
      $new_user_login = apply_filters( 'pre_user_login', sanitize_user( wp_unslash( $_REQUEST['user_login'] ), true ) );
      if ( isset( $_POST['noconfirmation'] ) && current_user_can( 'manage_network_users' ) ) {
        add_filter( 'wpmu_signup_user_notification', '__return_false' );  // Disable confirmation email.
        add_filter( 'wpmu_welcome_user_notification', '__return_false' ); // Disable welcome email.
      }
      wpmu_signup_user(
        $new_user_login,
        $new_user_email,
        array(
          'add_to_blog' => get_current_blog_id(),
          'new_role'    => $_REQUEST['role'],
        )
      );
      if ( isset( $_POST['noconfirmation'] ) && current_user_can( 'manage_network_users' ) ) {
        $key      = $wpdb->get_var( $wpdb->prepare( "SELECT activation_key FROM {$wpdb->signups} WHERE user_login = %s AND user_email = %s", $new_user_login, $new_user_email ) );
        $new_user = wpmu_activate_signup( $key );
        if ( is_wp_error( $new_user ) ) {
          $redirect = add_query_arg( array( 'update' => 'addnoconfirmation' ), 'user-new.php' );
        } elseif ( ! is_user_member_of_blog( $new_user['user_id'] ) ) {
          $redirect = add_query_arg( array( 'update' => 'created_could_not_add' ), 'user-new.php' );
        } else {
          $redirect = add_query_arg(
            array(
              'update'  => 'addnoconfirmation',
              'user_id' => $new_user['user_id'],
            ),
            'user-new.php'
          );
        }
      } else {
        $redirect = add_query_arg( array( 'update' => 'newuserconfirmation' ), 'user-new.php' );
      }
      wp_redirect( $redirect );
      die();
    }

  }


}