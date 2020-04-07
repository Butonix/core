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
        "editor.js" => "../vendor/editor.js/dist/editor.js",
        //"tinymce.min" => "tinymce/tinymce.min.js",
        //"tinymce.plugin.min" => "tinymce/plugins/lists/plugin.min.js",
    );

    if( $get_raw['action'] == 'edit' || $get_raw == 'new' ):
      foreach ( $scripts as $id => $script ):
          wp_enqueue_script( 'charti-'.$id, '/' . ADMIN_ASSETS .'/js/'. $script, true );
      endforeach;
    endif;
  }
}

?>
<?php if(2 < 1 ): ?>
<!--  temporary here -->
  <script src="https://cdn.jsdelivr.net/npm/@editorjs/header@latest"></script><!-- Header -->
  <script src="https://cdn.jsdelivr.net/npm/@editorjs/simple-image@latest"></script><!-- Image -->
  <script src="https://cdn.jsdelivr.net/npm/@editorjs/delimiter@latest"></script><!-- Delimiter -->
  <script src="https://cdn.jsdelivr.net/npm/@editorjs/list@latest"></script><!-- List -->
  <script src="https://cdn.jsdelivr.net/npm/@editorjs/checklist@latest"></script><!-- Checklist -->
  <script src="https://cdn.jsdelivr.net/npm/@editorjs/quote@latest"></script><!-- Quote -->
  <script src="https://cdn.jsdelivr.net/npm/@editorjs/code@latest"></script><!-- Code -->
  <script src="https://cdn.jsdelivr.net/npm/@editorjs/embed@latest"></script><!-- Embed -->
  <script src="https://cdn.jsdelivr.net/npm/@editorjs/table@latest"></script><!-- Table -->
  <script src="https://cdn.jsdelivr.net/npm/@editorjs/link@latest"></script><!-- Link -->
  <script src="https://cdn.jsdelivr.net/npm/@editorjs/warning@latest"></script><!-- Warning -->

  <script src="https://cdn.jsdelivr.net/npm/@editorjs/marker@latest"></script><!-- Marker -->
  <script src="https://cdn.jsdelivr.net/npm/@editorjs/inline-code@latest"></script><!-- Inline Code -->
<?php endif; ?>