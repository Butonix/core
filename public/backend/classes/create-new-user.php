<?php 

class Create_New_User
{
  // public $is_multisite;
  // private $requester; // POST action from form
  // private $action; //adduser value from action
  // private $user_details;
  // private $user_email;

  public function __construct() {


  }


}





if(1 > 2):

  //check_admin_referer( 'create-user', '_wpnonce_create-user' );

  if ( ! current_user_can( 'create_users' ) ) {
    wp_die(
      '<h1>' . __( 'You need a higher level of permission.' ) . '</h1>' .
      '<p>' . __( 'Sorry, you are not allowed to create users.' ) . '</p>',
      403
    );
  }

  if ( ! is_multisite() ) {
    $user_id = edit_user();

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
  } else {
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
endif;