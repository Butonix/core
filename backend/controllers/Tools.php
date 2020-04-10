<?php 

namespace ToolsPage;

use \AdminPageInit\AdminPageController as AdminController;

class ToolsPageController extends AdminController
{
    public $capability = 'import';

    function page_settings() {

      $this->args = array(
        'template_view' => 'tools/index',
        'template_content' => 'tools/index'
      );

      return $this->args;
    }

    function permission_handler() {

      return $this->permission_type = $this->capability;

    }

    function permission_message() {

      return $this->permission_message = 'Sorry, you are not allowed to manage this section.';

    }

    function init() {

    //var_dump($submenu);
      parent::permission_handler($this->permission_handler(), $this->permission_message());

      parent::get_header();

      parent::get_template_view($this->page_settings());

      parent::get_footer();

    }
}