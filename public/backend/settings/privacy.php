<?php
/**
 * Privacy Settings Screen.
 *
 * @package WordPress
 * @subpackage Administration
 *
 *  Charti CMS OOP in Mind
 *  @since 0.1
 */

/** WordPress Administration Bootstrap */
require_once __DIR__ . '/../admin.php'; // Admin init
/* Converted to class with OOP in mind */
require_once __DIR__ . '/class/Settings.php'; // The Parent Class that we are going to extend
require_once __DIR__ . '/class/SettingsPrivacy.php'; // The Class used for Privacy Page


// Current page settings
$config = array(
	'title_page' => __( 'Permalink Settings' ),
	'parent' => 'settings/index.php'
);

new Settings_Privacy_Page($wp_rewrite);
// grab the template
$template_content = Settings_Privacy_Page::template_content($config);