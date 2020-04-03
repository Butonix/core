<?php 

class Settings_Reading_Page extends Settings_page {

  public function __construct() {
    // Check if the current user has enough permissions to manage this page
    parent::permission_handler('manage_options');
    // load the global admin header template
    parent::get_header();
    // get the template
    parent::get_template_view('reading');
    // load the global admin footer template
    parent::get_footer();

  }

}