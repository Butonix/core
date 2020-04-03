<?php
/*

  Charti CMS
  Raw method to get the current page without any other dependencies
  
*/

class Raw_Screen {

  function screen_page_parameters() {
    //die;
    if ($_GET) {
      $get_paramters = $_GET;
      return $get_paramters;
    }
  }

  function screen_page() {
    
    $get_self = $_SERVER['PHP_SELF'];
    
    $url = explode(ADMIN_DIR , $get_self);

    $url = str_replace('/', '', $url[1]);

    //get_raw_screen_parameters();
    return $url;
  }
}

function get_raw() {
  $get_raw_screen = new Raw_Screen();
  return $get_raw_screen;
}