<?php

/**
 *  Charti CMS
 *  Registers TinyMCE scripts in a clean way
 *
 *  @since 0.1
 *
 */

/* Enqueue scripts & styles */
function v2admin_tinymce_scripts() {

  if (get_raw()->screen_page() == 'post.php' || 'post-new.php' == get_raw()->screen_page()) {
    
    if (get_raw()->screen_page() == 'post-new.php') {
      $get_raw = 'new';
    } elseif(get_raw()->screen_page_parameters()) {
      $get_raw = get_raw()->screen_page_parameters();
    }

    $scripts = array(
        "tinymce.min" => "tinymce/tinymce.min.js",
        "tinymce.plugin.min" => "tinymce/plugins/lists/plugin.min.js",
    );

    if( $get_raw['action'] == 'edit' || $get_raw == 'new' ):
      foreach ( $scripts as $id => $script ):
          wp_enqueue_script( 'charti-'.$id, '/' . ADMIN_ASSETS .'/js/'. $script, true );
      endforeach;
    endif;
  }
}