<?php
/**
 * Adds inline scripts required for the TinyMCE in the block editor.
 *
 * These TinyMCE init settings are used to extend and override the default settings
 * from `_WP_Editors::default_settings()` for the Classic block.
 *
 * @since 5.0.0
 *
 * @global WP_Scripts $wp_scripts
 */
function wp_tinymce_inline_scripts() {
  return false;

  global $wp_scripts;

  /** This filter is documented in ADMIN_ASSETS/class-wp-editor.php */
  $editor_settings = apply_filters( 'wp_editor_settings', array( 'tinymce' => true ), 'classic-block' );

  $tinymce_plugins = array(
    'charmap',
    'colorpicker',
    'hr',
    'lists',
    'media',
    'paste',
    'tabfocus',
    'textcolor',
    'fullscreen',
    'wordpress',
    'wpautoresize',
    'wpeditimage',
    'wpemoji',
    'wpgallery',
    'wplink',
    'wpdialogs',
    'wptextpattern',
    'wpview',
  );

  /** This filter is documented in ADMIN_ASSETS/class-wp-editor.php */
  $tinymce_plugins = apply_filters( 'tiny_mce_plugins', $tinymce_plugins, 'classic-block' );
  $tinymce_plugins = array_unique( $tinymce_plugins );

  $disable_captions = false;
  // Runs after `tiny_mce_plugins` but before `mce_buttons`.
  /** This filter is documented in ADMIN_DIR/includes/media.php */
  if ( apply_filters( 'disable_captions', '' ) ) {
    $disable_captions = true;
  }

  $toolbar1 = array(
    'formatselect',
    'bold',
    'italic',
    'bullist',
    'numlist',
    'blockquote',
    'alignleft',
    'aligncenter',
    'alignright',
    'link',
    'unlink',
    'wp_more',
    'spellchecker',
    'wp_add_media',
    'wp_adv',
  );

  /** This filter is documented in ADMIN_ASSETS/class-wp-editor.php */
  $toolbar1 = apply_filters( 'mce_buttons', $toolbar1, 'classic-block' );

  $toolbar2 = array(
    'strikethrough',
    'hr',
    'forecolor',
    'pastetext',
    'removeformat',
    'charmap',
    'outdent',
    'indent',
    'undo',
    'redo',
    'wp_help',
  );

  /** This filter is documented in ADMIN_ASSETS/class-wp-editor.php */
  $toolbar2 = apply_filters( 'mce_buttons_2', $toolbar2, 'classic-block' );
  /** This filter is documented in ADMIN_ASSETS/class-wp-editor.php */
  $toolbar3 = apply_filters( 'mce_buttons_3', array(), 'classic-block' );
  /** This filter is documented in ADMIN_ASSETS/class-wp-editor.php */
  $toolbar4 = apply_filters( 'mce_buttons_4', array(), 'classic-block' );
  /** This filter is documented in ADMIN_ASSETS/class-wp-editor.php */
  $external_plugins = apply_filters( 'mce_external_plugins', array(), 'classic-block' );

  $tinymce_settings = array(
    'plugins'              => implode( ',', $tinymce_plugins ),
    'toolbar1'             => implode( ',', $toolbar1 ),
    'toolbar2'             => implode( ',', $toolbar2 ),
    'toolbar3'             => implode( ',', $toolbar3 ),
    'toolbar4'             => implode( ',', $toolbar4 ),
    'external_plugins'     => wp_json_encode( $external_plugins ),
    'classic_block_editor' => true,
  );

  if ( $disable_captions ) {
    $tinymce_settings['wpeditimage_disable_captions'] = true;
  }

  if ( ! empty( $editor_settings['tinymce'] ) && is_array( $editor_settings['tinymce'] ) ) {
    array_merge( $tinymce_settings, $editor_settings['tinymce'] );
  }

  /** This filter is documented in ADMIN_ASSETS/class-wp-editor.php */
  $tinymce_settings = apply_filters( 'tiny_mce_before_init', $tinymce_settings, 'classic-block' );

  // Do "by hand" translation from PHP array to js object.
  // Prevents breakage in some custom settings.
  $init_obj = '';
  foreach ( $tinymce_settings as $key => $value ) {
    if ( is_bool( $value ) ) {
      $val       = $value ? 'true' : 'false';
      $init_obj .= $key . ':' . $val . ',';
      continue;
    } elseif ( ! empty( $value ) && is_string( $value ) && (
      ( '{' === $value[0] && '}' === $value[ strlen( $value ) - 1 ] ) ||
      ( '[' === $value[0] && ']' === $value[ strlen( $value ) - 1 ] ) ||
      preg_match( '/^\(?function ?\(/', $value ) ) ) {
      $init_obj .= $key . ':' . $value . ',';
      continue;
    }
    $init_obj .= $key . ':"' . $value . '",';
  }

  $init_obj = '{' . trim( $init_obj, ' ,' ) . '}';

  $script = 'window.wpEditorL10n = {
    tinymce: {
      baseURL: ' . wp_json_encode( includes_url( 'js/tinymce' ) ) . ',
      suffix: ' . ( SCRIPT_DEBUG ? '""' : '".min"' ) . ',
      settings: ' . $init_obj . ',
    }
  }';

  $wp_scripts->add_inline_script( 'wp-block-library', $script, 'before' );
}