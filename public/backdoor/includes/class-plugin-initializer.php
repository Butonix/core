<?php
/**
 *  Plugin Administration Wrapper
 *  Now Plugin Initializer can be loaded via namespace
 *
 *  @package Chartí CMS
 *  @subpackage Administration
 *  @since 2.3.0 via Wordpress
 ** @since 0.1 by Chartí CMS
 */

namespace Plugin_Initializer;

class PluginWrapper {

  public function __construct() {

    $this->Initializer();

  }

  function Initializer() {
  
    require_once ABSPATH . ADMIN_DIR . '/includes/plugin.php';
  
  }
  
}

new \Plugin_Initializer\PluginWrapper;
