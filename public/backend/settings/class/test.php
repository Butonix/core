<?php

/**
  
  This is just a starter work that will be applied on Chartí CMS dashboard

**/

namespace AdminDashboard;

use Rareloop\WordPress\Router\Router;

class TestController
{
    public function init()
    {

        $title       = __( 'Manage Themes' );
        $parent_file = 'themes.php';

        $this->is_user_logged_in();

        $this->get_template_view('test');
    }

    protected function get_template_view($template_view) {
      /** WordPress Administration Bootstrap */
      require_once __DIR__ . '/../../admin.php';

      require_once ABSPATH . ADMIN_DIR . '/admin-header.php';

      require __DIR__ . '/../views/' . $template_view . '.php';

      require_once ABSPATH . ADMIN_DIR . '/admin-footer.php';
    }

    private function is_user_logged_in() {

      if( is_user_logged_in() ):
        
        return;

      else:

      header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");
          exit("<!DOCTYPE HTML PUBLIC \"-//IETF//DTD HTML 2.0//EN\">\r\n<html><head>\r\n<title>404 Not Found</title>\r\n</head><body>\r\n<h1>Not Found</h1>\r\n<p>The requested URL " . $_SERVER['SCRIPT_NAME'] . " was not found on this server.</p>\r\n</body></html>");
      endif;
    }
}

// routes.php
Router::map(['GET'], 'backdoor/charticms', 'AdminDashboard\TestController@init');

//$current_screen = 'Test Page';
//WP_Screen::set_current_screen();