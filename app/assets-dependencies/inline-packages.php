<?php
/**
 * Adds inline scripts required for the WordPress JavaScript packages.
 *
 * @since 5.0.0
 *
 * @param WP_Scripts $scripts WP_Scripts object.
 */
function wp_default_packages_inline_scripts( $scripts ) {
  global $wp_locale;

  if ( isset( $scripts->registered['wp-api-fetch'] ) ) {
    $scripts->registered['wp-api-fetch']->deps[] = 'wp-hooks';
  }
  $scripts->add_inline_script(
    'wp-api-fetch',
    sprintf(
      'wp.apiFetch.use( wp.apiFetch.createRootURLMiddleware( "%s" ) );',
      esc_url_raw( get_rest_url() )
    ),
    'after'
  );
  $scripts->add_inline_script(
    'wp-api-fetch',
    implode(
      "\n",
      array(
        sprintf(
          'wp.apiFetch.nonceMiddleware = wp.apiFetch.createNonceMiddleware( "%s" );',
          ( wp_installing() && ! is_multisite() ) ? '' : wp_create_nonce( 'wp_rest' )
        ),
        'wp.apiFetch.use( wp.apiFetch.nonceMiddleware );',
        'wp.apiFetch.use( wp.apiFetch.mediaUploadMiddleware );',
        sprintf(
          'wp.apiFetch.nonceEndpoint = "%s";',
          admin_url( 'admin-ajax.php?action=rest-nonce' )
        ),
      )
    ),
    'after'
  );
  $scripts->add_inline_script(
    'wp-data',
    implode(
      "\n",
      array(
        '( function() {',
        ' var userId = ' . get_current_user_ID() . ';',
        ' var storageKey = "WP_DATA_USER_" + userId;',
        ' wp.data',
        '   .use( wp.data.plugins.persistence, { storageKey: storageKey } );',
        ' wp.data.plugins.persistence.__unstableMigrate( { storageKey: storageKey } );',
        '} )();',
      )
    )
  );

  $scripts->add_inline_script(
    'wp-date',
    sprintf(
      'wp.date.setSettings( %s );',
      wp_json_encode(
        array(
          'l10n'     => array(
            'locale'        => get_user_locale(),
            'months'        => array_values( $wp_locale->month ),
            'monthsShort'   => array_values( $wp_locale->month_abbrev ),
            'weekdays'      => array_values( $wp_locale->weekday ),
            'weekdaysShort' => array_values( $wp_locale->weekday_abbrev ),
            'meridiem'      => (object) $wp_locale->meridiem,
            'relative'      => array(
              /* translators: %s: Duration. */
              'future' => __( '%s from now' ),
              /* translators: %s: Duration. */
              'past'   => __( '%s ago' ),
            ),
          ),
          'formats'  => array(
            /* translators: Time format, see https://www.php.net/date */
            'time'                => get_option( 'time_format', __( 'g:i a' ) ),
            /* translators: Date format, see https://www.php.net/date */
            'date'                => get_option( 'date_format', __( 'F j, Y' ) ),
            /* translators: Date/Time format, see https://www.php.net/date */
            'datetime'            => __( 'F j, Y g:i a' ),
            /* translators: Abbreviated date/time format, see https://www.php.net/date */
            'datetimeAbbreviated' => __( 'M j, Y g:i a' ),
          ),
          'timezone' => array(
            'offset' => get_option( 'gmt_offset', 0 ),
            'string' => get_option( 'timezone_string', 'UTC' ),
          ),
        )
      )
    ),
    'after'
  );

  // Loading the old editor and its config to ensure the classic block works as expected.
  $scripts->add_inline_script(
    'editor',
    'window.wp.oldEditor = window.wp.editor;',
    'after'
  );
}


/**
 * Registers all the WordPress packages scripts.
 *
 * @since 5.0.0
 *
 * @param WP_Scripts $scripts WP_Scripts object.
 */
function wp_default_packages( $scripts ) {
  wp_default_packages_vendor( $scripts );
  wp_default_packages_scripts( $scripts );
  
  if ( did_action( 'init' ) ) {
    wp_default_packages_inline_scripts( $scripts );
  }
}