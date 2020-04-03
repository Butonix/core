<?php 

class Add_Existent_User
{

  private $requester; // POST action from form
  private $action; //adduser value from action
  private $user_details;
  private $user_email;

  public function __construct($requester) {

    //if ( isset( $_REQUEST['action'] ) && 'adduser' == $_REQUEST['action'] ) {

      var_dump($requester);

      die;

    //}

  }

}


//$objEmployee = new Employee('Bob', 'Smith', 30);
 
//echo $objEmployee->getFirstName(); // print 'Bob'
// echo $objEmployee->getLastName(); // prints 'Smith'
// echo $objEmployee->getAge(); // prints '30'


if ( 1 > 2 ):


if ( isset( $_REQUEST['action'] ) && 'adduser' == $_REQUEST['action'] ) {
  check_admin_referer( 'add-user', '_wpnonce_add-user' );

  $user_details = null;
  $user_email   = wp_unslash( $_REQUEST['email'] );

  if ( false !== strpos( $user_email, '@' ) ) {
    $user_details = get_user_by( 'email', $user_email );
  } else {

    if ( current_user_can( 'manage_network_users' ) ) {
      $user_details = get_user_by( 'login', $user_email );
    } else {
      wp_redirect( add_query_arg( array( 'update' => 'enter_email' ), 'user-new.php' ) );
      die();
    }
  }



  if ( ! $user_details ) {
    wp_redirect( add_query_arg( array( 'update' => 'does_not_exist' ), 'user-new.php' ) );
    die();
  }

  if ( ! current_user_can( 'promote_user', $user_details->ID ) ) {
    wp_die(
      '<h1>' . __( 'You need a higher level of permission.' ) . '</h1>' .
      '<p>' . __( 'Sorry, you are not allowed to add users to this network.' ) . '</p>',
      403
    );
  }

  // Adding an existing user to this blog.
  $new_user_email = $user_details->user_email;
  $redirect       = 'user-new.php';
  $username       = $user_details->user_login;
  $user_id        = $user_details->ID;
  if ( null != $username && array_key_exists( $blog_id, get_blogs_of_user( $user_id ) ) ) {
    $redirect = add_query_arg( array( 'update' => 'addexisting' ), 'user-new.php' );
  } else {
    if ( isset( $_POST['noconfirmation'] ) && current_user_can( 'manage_network_users' ) ) {
      $result = add_existing_user_to_blog(
        array(
          'user_id' => $user_id,
          'role'    => $_REQUEST['role'],
        )
      );

      if ( ! is_wp_error( $result ) ) {
        $redirect = add_query_arg(
          array(
            'update'  => 'addnoconfirmation',
            'user_id' => $user_id,
          ),
          'user-new.php'
        );
      } else {
        $redirect = add_query_arg( array( 'update' => 'could_not_add' ), 'user-new.php' );
      }
    } else {
      $newuser_key = wp_generate_password( 20, false );
      add_option(
        'new_user_' . $newuser_key,
        array(
          'user_id' => $user_id,
          'email'   => $user_details->user_email,
          'role'    => $_REQUEST['role'],
        )
      );

      $roles = get_editable_roles();
      $role  = $roles[ $_REQUEST['role'] ];

      /**
       * Fires immediately after a user is invited to join a site, but before the notification is sent.
       *
       * @since 4.4.0
       *
       * @param int    $user_id     The invited user's ID.
       * @param array  $role        Array containing role information for the invited user.
       * @param string $newuser_key The key of the invitation.
       */
      do_action( 'invite_user', $user_id, $role, $newuser_key );

      $switched_locale = switch_to_locale( get_user_locale( $user_details ) );

      /* translators: 1: Site title, 2: Site URL, 3: User role, 4: Activation URL. */
      $message = __(
        'Hi,

You\'ve been invited to join \'%1$s\' at
%2$s with the role of %3$s.

Please click the following link to confirm the invite:
%4$s'
      );

      wp_mail(
        $new_user_email,
        sprintf(
          /* translators: Joining confirmation notification email subject. %s: Site title. */
          __( '[%s] Joining Confirmation' ),
          wp_specialchars_decode( get_option( 'blogname' ) )
        ),
        sprintf(
          $message,
          get_option( 'blogname' ),
          home_url(),
          wp_specialchars_decode( translate_user_role( $role['name'] ) ),
          home_url( "/newbloguser/$newuser_key/" )
        )
      );

      if ( $switched_locale ) {
        restore_previous_locale();
      }

      $redirect = add_query_arg( array( 'update' => 'add' ), 'user-new.php' );
    }
  }
  wp_redirect( $redirect );
  die();
} //elseif ( isset( $_REQUEST['action'] ) && 'createuser' == $_REQUEST['action'] ) {



endif;