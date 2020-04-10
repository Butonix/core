<?php

namespace Core\PermissionHandler;

use Core\ErrorHandler\ErrorHandler as Error;

  /**
   *  A wrapper for current_user_can() together with wp_die which is also
   *  wrapped into a beautiful namespace Core\ErrorHandler\ErrorHandler
   *  
   *  Returns false and quit the proccess
   *
   *  @since 1.0
   */
class CurrentUserCant {
  
  public function __construct($capability, $message = null) {
    
    if( current_user_can($capability) ) { 
      
      if ( $message ) {
        /* If there is any message specified will prompt that */
        new Error( $message );
      
      } else {
        /* In some cases you don't want a specific message so here you go */
        new Error( __('You are not allowed to access this page.') );

      }
    }
  }

}
