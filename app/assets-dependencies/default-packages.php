<?php 

/**
 * Registers all the WordPress vendor scripts that are in the standardized
 * `js/dist/vendor/` location.
 *
 * For the order of `$scripts->add` see `wp_default_scripts`.
 *
 * @since 5.0.0
 *
 * @param WP_Scripts $scripts WP_Scripts object.
 */
function wp_default_packages_vendor( $scripts ) {
  global $wp_locale;

  $suffix = wp_scripts_get_suffix();

  $vendor_scripts = array(
    'react'     => array( 'wp-polyfill' ),
    'react-dom' => array( 'react' ),
    'moment',
    'lodash',
    'wp-polyfill-fetch',
    'wp-polyfill-formdata',
    'wp-polyfill-node-contains',
    'wp-polyfill-url',
    'wp-polyfill-dom-rect',
    'wp-polyfill-element-closest',
    'wp-polyfill',
  );

  $vendor_scripts_versions = array(
    'react'                       => '16.9.0',
    'react-dom'                   => '16.9.0',
    'moment'                      => '2.22.2',
    'lodash'                      => '4.17.15',
    'wp-polyfill-fetch'           => '3.0.0',
    'wp-polyfill-formdata'        => '3.0.12',
    'wp-polyfill-node-contains'   => '3.42.0',
    'wp-polyfill-url'             => '3.6.4',
    'wp-polyfill-dom-rect'        => '3.42.0',
    'wp-polyfill-element-closest' => '2.0.2',
    'wp-polyfill'                 => '7.4.4',
  );

  foreach ( $vendor_scripts as $handle => $dependencies ) {
    if ( is_string( $dependencies ) ) {
      $handle       = $dependencies;
      $dependencies = array();
    }

    $path    = "/" . ADMIN_ASSETS . "/js/dist/vendor/$handle$suffix.js";
    $version = $vendor_scripts_versions[ $handle ];

    $scripts->add( $handle, $path, $dependencies, $version, 1 );
  }

  $scripts->add( 'wp-polyfill', null, array( 'wp-polyfill' ) );
  did_action( 'init' ) && $scripts->add_inline_script(
    'wp-polyfill',
    wp_get_script_polyfill(
      $scripts,
      array(
        '\'fetch\' in window' => 'wp-polyfill-fetch',
        'document.contains'   => 'wp-polyfill-node-contains',
        'window.DOMRect'      => 'wp-polyfill-dom-rect',
        'window.URL && window.URL.prototype && window.URLSearchParams' => 'wp-polyfill-url',
        'window.FormData && window.FormData.prototype.keys' => 'wp-polyfill-formdata',
        'Element.prototype.matches && Element.prototype.closest' => 'wp-polyfill-element-closest',
      )
    )
  );

  did_action( 'init' ) && $scripts->add_inline_script( 'lodash', 'window.lodash = _.noConflict();' );

  did_action( 'init' ) && $scripts->add_inline_script(
    'moment',
    sprintf(
      "moment.locale( '%s', %s );",
      get_user_locale(),
      wp_json_encode(
        array(
          'months'         => array_values( $wp_locale->month ),
          'monthsShort'    => array_values( $wp_locale->month_abbrev ),
          'weekdays'       => array_values( $wp_locale->weekday ),
          'weekdaysShort'  => array_values( $wp_locale->weekday_abbrev ),
          'week'           => array(
            'dow' => (int) get_option( 'start_of_week', 0 ),
          ),
          'longDateFormat' => array(
            'LT'   => get_option( 'time_format', __( 'g:i a', 'default' ) ),
            'LTS'  => null,
            'L'    => null,
            'LL'   => get_option( 'date_format', __( 'F j, Y', 'default' ) ),
            'LLL'  => __( 'F j, Y g:i a', 'default' ),
            'LLLL' => null,
          ),
        )
      )
    ),
    'after'
  );
}
