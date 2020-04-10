<?php

class Settings_Privacy_Page extends Settings_page {

  public $privacy_policy_page_exists;

  public function __construct() {
    // Check if the current user has enough permissions to manage this page
    parent::permission_handler('manage_privacy_options');
    
    $this->form_handler();
    // load the global admin header template
    parent::get_header();
    // get the template
    parent::get_template_view('privacy');
    // load the global admin footer template
    parent::get_footer();
  }

  protected function action_handler() {

    $action = isset( $_POST['action'] ) ? $_POST['action'] : '';
      if ( ! empty( $action ) ) {
        // check admin nonce
        check_admin_referer( $action );
        // call for it with action: 'set-privacy-page' or 'create-privacy-page'
        $this->privacy_policy_id($action);
      }

    return $action;
  }

  function privacy_policy_id($action) {
    if ( 'set-privacy-page' === $action ) {
      $privacy_policy_page_id = isset( $_POST['page_for_privacy_policy'] ) ? (int) $_POST['page_for_privacy_policy'] : 0;
      update_option( 'wp_page_for_privacy_policy', $privacy_policy_page_id );

      return $privacy_policy_page_id;
    }
  }

  protected function get_post_status($id) {
    $get_post_status = get_post_status( $id );
    return $get_post_status;
  }

  protected function notification_handler($string, $code, $message, $type){
    add_settings_error($string, $code, $message, $type );

    //parent::notification_output($string, $code, $message, $type);    
  }

  protected function current_page($action) {
    
    $privacy_page_updated_message = __( 'Privacy Policy page updated successfully.' );

    if ( $this->privacy_policy_id($action) ) {

      $get_post_status = $this->get_post_status($this->privacy_policy_id($action));

      if ('publish' === $get_post_status && current_user_can( 'edit_theme_options' ) && current_theme_supports( 'menus' ) ) {

        $privacy_page_updated_message = sprintf(
          /* translators: %s: URL to Customizer -> Menus. */
          __( 'Privacy Policy page setting updated successfully. Remember to <a href="%s">update your menus</a>!' ),
          esc_url( add_query_arg( 'autofocus[panel]', 'nav_menus', admin_url( 'customize.php' ) ) )
        );
      }

      // whether if there is a draft or a published page
      $this->notification_handler('page_for_privacy_policy', 'page_for_privacy_policy', $privacy_page_updated_message, 'success');
    }


    //var_dump(add_settings_error('page_for_privacy_policy', 'page_for_privacy_policy', 'Privacy Policy page updated successfully.', 'success'));

    //var_dump(get_settings_errors());
  
  }

  protected function form_handler() {

    $action = $this->action_handler();
    $privacy_policy_page_id = $this->privacy_policy_id($action);

      $this->current_page($action);


      if ( 'create-privacy-page' === $action ) {

        if ( ! class_exists( 'WP_Privacy_Policy_Content' ) ) {
          require_once ABSPATH . ADMIN_DIR . '/includes/class-wp-privacy-policy-content.php';
        }

        $privacy_policy_page_content = WP_Privacy_Policy_Content::get_default_content();
        $privacy_policy_page_id      = wp_insert_post(
          array(
            'post_title'   => __( 'Privacy Policy' ),
            'post_status'  => 'draft',
            'post_type'    => 'page',
            'post_content' => $privacy_policy_page_content,
          ),
          true
        );

        if ( is_wp_error( $privacy_policy_page_id ) ) {
          add_settings_error(
            'page_for_privacy_policy',
            'page_for_privacy_policy',
            __( 'Unable to create a Privacy Policy page.' ),
            'error'
          );
        } else {
          update_option( 'wp_page_for_privacy_policy', $privacy_policy_page_id );

          wp_redirect( admin_url( 'post.php?post=' . $privacy_policy_page_id . '&action=edit' ) );
          exit;
        }
      }

    // If a Privacy Policy page ID is available, make sure the page actually exists. If not, display an error.
    $this->privacy_policy_page_exists = false;
    $privacy_policy_page_id     = (int) get_option( 'wp_page_for_privacy_policy' );

    if ( ! empty( $privacy_policy_page_id ) ) {

      $privacy_policy_page = get_post( $privacy_policy_page_id );

      if ( ! $privacy_policy_page instanceof WP_Post ) {
        add_settings_error(
          'page_for_privacy_policy',
          'page_for_privacy_policy',
          __( 'The currently selected Privacy Policy page does not exist. Please create or select a new page.' ),
          'error'
        );
      } else {
        if ( 'trash' === $privacy_policy_page->post_status ) {
          add_settings_error(
            'page_for_privacy_policy',
            'page_for_privacy_policy',
            sprintf(
              /* translators: %s: URL to Pages Trash. */
              __( 'The currently selected Privacy Policy page is in the Trash. Please create or select a new Privacy Policy page or <a href="%s">restore the current page</a>.' ),
              'edit.php?post_status=trash&post_type=page'
            ),
            'error'
          );
        } else {
          $this->privacy_policy_page_exists = true;
        }
      }
    }

  }

}