<?php
/**
 * Returns the suffix that can be used for the scripts.
 *
 * There are two suffix types, the normal one and the dev suffix.
 *
 * @since 5.0.0
 *
 * @param string $type The type of suffix to retrieve.
 * @return string The script suffix.
 */
function wp_scripts_get_suffix( $type = '' ) {
  static $suffixes;

  if ( null === $suffixes ) {
    // Include an unmodified $wp_version.
    require ABSPATH . WPINC . '/version.php';

    $develop_src = false !== strpos( $wp_version, '-src' );

    if ( ! defined( 'SCRIPT_DEBUG' ) ) {
      define( 'SCRIPT_DEBUG', $develop_src );
    }
    $suffix     = SCRIPT_DEBUG ? '' : '.min';
    $dev_suffix = $develop_src ? '' : '.min';

    $suffixes = array(
      'suffix'     => $suffix,
      'dev_suffix' => $dev_suffix,
    );
  }

  if ( 'dev' === $type ) {
    return $suffixes['dev_suffix'];
  }

  return $suffixes['suffix'];
}