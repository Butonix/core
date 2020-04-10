<?php

namespace Core\Phtml;

class RenderPage {

  /**
   * Enqueue scripts & styles
   */
  public function enqueue_scripts( $scripts ) {
    
    foreach ($scripts as $script_line) {

      wp_enqueue_script( $script_line );  
    
    }

  }

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

  protected function get_template_view($template_view, $page_settings = null) {

    echo '<div class="wrap">';
    
    require_once ABSPATH . ADMIN_DIR . '/views/' . $template_view . '.phtml';
    
    echo '</div>';

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

/**
 *  Charti CMS
 *  Render Template
 *  It handles all the views during the installation proccess
 *  @since 1.0
 */
class RenderBlock {

  function __construct($dir_path, $template_file, $data = null) {

    // pass any dat we need so we can load in our templates
    $this->data = $data;

    // Initialize the Setup
    // $this->body_classes[] = 'wp-core-ui';

    $this->load_template($dir_path, $template_file . '.phtml');
  }

  /**
   * A method that handles all template views
   */
  public function load_template($dir_path, $template_file) {
    // we are going to use .phtml extension for a better structure view
    require ABSPATH . ADMIN_DIR . '/views/' . $dir_path . $template_file;

  }
}