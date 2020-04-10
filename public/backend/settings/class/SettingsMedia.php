<?php


class Settings_Media_Page extends Settings_page
{

  public function __construct($config)
  {

    // Check if the current user has enough permissions
    parent::permission_handler('manage_options');

    // Grab title and parent 
    $this->title = $config['title_page'];
    $this->parent = $config['parent'];

    // load the global admin header template
    parent::get_header();
    // get the template
    parent::get_template_view('media');
    // load the global admin footer template
    parent::get_footer();

  }

  private function page_settings() {
    return $this->title;
  }

  private function parent_page() {
    return $this->parent;
  }

  // public static function template_content($config) {

  //   return array(
  //     'image_sizes' => __('Image sizes'),
  //     'image_sizes_description' => __('The sizes listed below determine the maximum dimensions in pixels to use when adding an image to the Media Library.'),
  //     'thumbnail_size' => __('Thumbnail size')

  //   );

  //   return $template_contents;

  // }

}

