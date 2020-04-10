<?php
// https://www.php.net/manual/en/language.namespaces.dynamic.php
// https://stackoverflow.com/questions/3449122/extending-a-class-with-an-other-namespace-with-the-same-classname

namespace WelcomePage;

use \AdminPageInit\AdminPageController as AdminController;

class WelcomePageController extends AdminController
{

    function page_settings() {

      $this->args = array(
        'template_view' => 'settings/welcome',
        'template_content' => 'settings/welcome'
      );

      return $this->args;
      
    }

    function init() {
      parent::get_header();
      echo 'test';
      //parent::get_template_view($this->page_settings());
      parent::get_footer();
    }
}