<?php

namespace Core\Auth;

// Authentication required
class CoreAuthRequired {

/**
 * Checks if a user is logged in, if not it redirects them to the login page.
 *
 * When this code is called from a page, it checks to see if the user viewing the page is logged in.
 * If the user is not logged in, they are redirected to the login page. The user is redirected
 * in such a way that, upon logging in, they will be sent directly to the page they were originally
 * trying to access.
 *
 * @since 1.5.0
 */

  public function __construct() {
    
    $secure = ( is_ssl() || force_ssl_admin() );

    /**
     * Filters whether to use a secure authentication redirect.
     *
     * @since 3.1.0
     *
     * @param bool $secure Whether to use a secure authentication redirect. Default false.
     */
    $secure = apply_filters( 'secure_auth_redirect', $secure );

    // If https is required and request is http, redirect.
    if ( $secure && ! is_ssl() && false !== strpos( $_SERVER['REQUEST_URI'], 'wp-admin' ) ) {
      if ( 0 === strpos( $_SERVER['REQUEST_URI'], 'http' ) ) {
        wp_redirect( set_url_scheme( $_SERVER['REQUEST_URI'], 'https' ) );
        exit();
      } else {
        wp_redirect( 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] );
        exit();
      }
    }

    /**
     * Filters the authentication redirect scheme.
     *
     * @since 2.9.0
     *
     * @param string $scheme Authentication redirect scheme. Default empty.
     */
    $scheme = apply_filters( 'auth_redirect_scheme', '' );

    $user_id = wp_validate_auth_cookie( '', $scheme );
    if ( $user_id ) {
      /**
       * Fires before the authentication redirect.
       *
       * @since 2.8.0
       *
       * @param int $user_id User ID.
       */
      do_action( 'auth_redirect', $user_id );

      // If the user wants ssl but the session is not ssl, redirect.
      if ( ! $secure && get_user_option( 'use_ssl', $user_id ) && false !== strpos( $_SERVER['REQUEST_URI'], 'wp-admin' ) ) {
        if ( 0 === strpos( $_SERVER['REQUEST_URI'], 'http' ) ) {
          wp_redirect( set_url_scheme( $_SERVER['REQUEST_URI'], 'https' ) );
          exit();
        } else {
          wp_redirect( 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] );
          exit();
        }
      }

      return; // The cookie is good, so we're done.
    }

    // The cookie is no good, so force login.
    nocache_headers();

    $redirect = ( strpos( $_SERVER['REQUEST_URI'], '/options.php' ) && wp_get_referer() ) ? wp_get_referer() : set_url_scheme( 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] );

    $login_url = wp_login_url( $redirect, true );

    wp_redirect( $login_url );
    exit();

  }
}

  // Initiate
  
  new CoreAuthRequired();

