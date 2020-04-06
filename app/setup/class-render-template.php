<?php

/**
 *  Charti CMS
 *  Render Install Template
 *  It handles all the views during the installation proccess
 *  @since 1.0
 */
class Render_Install_Template {
  
  /* used for adding css classes when we need */
  public $body_classes  = array();
  public $param;

  function __construct($template_file, $data = null) {
    // pass any dat we need so we can load in our templates
    $this->data = $data;

    // Initialize the Setup
    $this->body_classes[] = 'wp-core-ui';

    $this->load_template($template_file);
  }

  /**
   * A method that handles all template views
   */
  public function load_template($template_file) {
    // we are going to use .phtml extension for a better structure view
    require_once ABSPATH . WPINC . '/setup/views/' . $template_file . '.phtml';

  }
}