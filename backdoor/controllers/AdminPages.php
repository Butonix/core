<?php

namespace AdminPageInit;

class AdminPageController
{
    public $args = array();
    public $permission_type = null;
    public $permission_message = null;

    function init() {
      $this->permission_handler();
      $this->get_header();
      $this->get_template_view();
      $this->get_footer();      
    }

    function permission_handler() {

      if( $this->permission_type ) {
        if ( ! current_user_can( $this->permission_type ) ) {
          wp_die( __( $this->permission_message ) );
        }
      }
    }

    protected function get_template_view()
    {

      $this->render_template_view($this->args['template_view'], $this->args['template_content']);
      
    }

    public function render_template_view($content_view, $template_view) {

      require_once ROOTPATH . 'backdoor/content/' . $content_view . '.php';

      require_once ROOTPATH . 'backdoor/views/' . $template_view . '.phtml';

    }

    protected function get_header() {

     require_once ROOTPATH . 'backdoor/controllers/header.php';

    }

    protected function get_footer() {

      require_once ROOTPATH . 'backdoor/controllers/footer.php';

    }
}