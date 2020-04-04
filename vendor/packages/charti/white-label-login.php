<?php

/**
  * Charti CMS
  *
  * Adding new Login Register Forgot Password as a built in feature
  * 
  * This will also remove the old files: register, wp login and forgot password.
  *
  * @since 0.1
  * @required vendor/router.php
  * @author Cristian George
  * 
**/

function ajax_login_init(){

    wp_register_script('ajax-login',  ('/' . ADMIN_ASSETS . '/vendor/charti/ajax-login.js'), array('jquery') ); 
    wp_enqueue_script('ajax-login');

    wp_localize_script( 'ajax-login', 'ajax_login_object', array( 
        'ajaxurl' => admin_url( 'admin-ajax.php' ),
        'redirecturl' => admin_url(),
        // 'redirecturl' => ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'],
        'loadingmessage' => __('Sending user info, please wait...')
    ));
    
    // Enable the user with no privileges to run ajax_login() in AJAX
    add_action( 'wp_ajax_nopriv_ajaxlogin', 'ajax_login' );
}

function ajax_login(){

    // First check the nonce, if it fails the function will break
    check_ajax_referer( 'ajax-login-nonce', 'security' );

    // Nonce is checked, get the POST data and sign user on
    $info = array();
    $info['user_email'] = $_POST['log'];
    $info['user_password'] = $_POST['password'];
    $info['remember'] = true;

    //$user_signon = wp_signon( $info, false );
    $userdata = get_user_by('email', $info['user_email']);
    $result = wp_check_password($info['user_password'], $userdata->data->user_pass, $userdata->data->ID);

    if ( $result) {

        auto_login( $userdata );

        echo json_encode(
            array(
                'loggedin' => true,
                'message'=> __('Login successful, redirecting...')
            )
        );
    } else {
        echo json_encode(
            array(
                'loggedin' => false,
                'message' => __('Wrong username or password.')
            )
        );
    }

    die();
}

// Execute the action only if the user isn't logged in
if (!is_user_logged_in()) {
    add_action('init', 'ajax_login_init');
    //get_template_part('parts/login');
    //exit;
}

function auto_login( $user ) {

    if ( !is_user_logged_in() ) {

        $user_id = $user->data->ID;
        $user_login = $user->data->user_login;

        wp_set_current_user( $user_id, $user_login );
        wp_set_auth_cookie( $user_id );

    } 
}