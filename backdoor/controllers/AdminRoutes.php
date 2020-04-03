<?php

class AdminRoutes {

  public function __construct() {
    $this->init();
  }

  function init() {
    // Create the route
    require_once ROUTESPATH . 'admin/system.php';
  }

}

new AdminRoutes;
