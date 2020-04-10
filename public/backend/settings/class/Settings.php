<?php

class Settings_page {

  protected function get_header() {
      require_once ABSPATH . ADMIN_DIR . '/admin-header.php';
  }

  protected function get_footer() {
      require_once ABSPATH . ADMIN_DIR . '/admin-footer.php';
  }

  protected function permission_handler($permission_type) {
    
    if ( ! current_user_can( $permission_type ) ) {
      wp_die( __( 'Sorry, you are not allowed to manage options for this site.' ) );
    }
  }

  // notification output using wp native function
  // see https://developer.wordpress.org/reference/functions/add_settings_error/
  protected function notification_output($string, $code, $message, $type) {
    // string $setting, string $code, string $message, string $type = 'error', 'success', 'warning', 'info'
    add_settings_error($string, $code, $message, $type );
  }

  protected function get_template_view($template_view) {
    require_once __DIR__ . '/../views/' . $template_view . '.phtml';
  }


  public static function template_content($config) {
    // $template_contents = array(
    //  'image_sizes' => __('Image sizes'),
    //  'image_sizes_description' => __('The sizes listed below determine the maximum dimensions in pixels to use when adding an image to the Media Library.'),
    //  'thumbnail_size' => __('Thumbnail size')

    // );

    return $config;

    //var_dump($template_contents);

  }

}