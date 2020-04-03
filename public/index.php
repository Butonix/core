<?php
/**
 * Front to the application. This file doesn't do anything, but loads
 * autoload.php which does and tells to load the theme.
 *
 * @package
 */

/**
 * Define if load the theme and output it.
 *
 * @var bool
 */

define( 'WP_USE_THEMES', true );

/** Loads the Environment and Template */
require __DIR__ . '/autoload.php';
