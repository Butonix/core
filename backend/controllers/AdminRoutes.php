<?php

class AdminRoutes {

  public function __construct() {
    $this->init();
  }

  function init() {
    // Create the route
    require_once ROUTES_PATH . 'admin/system.php';
  }

}

new AdminRoutes;
