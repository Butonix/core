<?php
/**
 * Returns contents of an inline script used in appending polyfill scripts for
 * browsers which fail the provided tests. The provided array is a mapping from
 * a condition to verify feature support to its polyfill script handle.
 *
 * @since 5.0.0
 *
 * @param WP_Scripts $scripts WP_Scripts object.
 * @param array      $tests   Features to detect.
 * @return string Conditional polyfill inline script.
 */
function wp_get_script_polyfill( $scripts, $tests ) {
  $polyfill = '';
  foreach ( $tests as $test => $handle ) {
    if ( ! array_key_exists( $handle, $scripts->registered ) ) {
      continue;
    }

    $src = $scripts->registered[ $handle ]->src;
    $ver = $scripts->registered[ $handle ]->ver;

    if ( ! preg_match( '|^(https?:)?//|', $src ) && ! ( $scripts->content_url && 0 === strpos( $src, $scripts->content_url ) ) ) {
      $src = $scripts->base_url . $src;
    }

    if ( ! empty( $ver ) ) {
      $src = add_query_arg( 'ver', $ver, $src );
    }

    /** This filter is documented in ADMIN_ASSETS/class.wp-scripts.php */
    $src = esc_url( apply_filters( 'script_loader_src', $src, $handle ) );

    if ( ! $src ) {
      continue;
    }

    $polyfill .= (
      // Test presence of feature...
      '( ' . $test . ' ) || ' .
      /*
       * ...appending polyfill on any failures. Cautious viewers may balk
       * at the `document.write`. Its caveat of synchronous mid-stream
       * blocking write is exactly the behavior we need though.
       */
      'document.write( \'<script src="' .
      $src .
      '"></scr\' + \'ipt>\' );'
    );
  }

  return $polyfill;
}