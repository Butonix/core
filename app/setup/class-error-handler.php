<?php
/*

  The Core Setup Configuration

  @since 1.0
*/
class ErrorHandler {
  
  public function __construct($message, $args = null) {
    
    $this->message_handler($message, $args);
  
  }
  
  public function message_handler($message, $args) {
    
    if(is_array($args)) {
      // let's pass the $args to sprintf in case there are more things to output
      wp_die( sprintf($message, $args[0], $args[1]) );

    } else {
      // a simple error message
      wp_die( sprintf($message, $args) );     
    }
  
  }
}