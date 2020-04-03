<?php
/**
 * Permalink Settings Administration Screen.
 *
 * @package WordPress
 * @subpackage Administration
 *
 *  Charti CMS OOP in Mind
 *  @since 0.1
 */

/** WordPress Administration Bootstrap */
require_once __DIR__ . '/../admin.php';
/* Converted to class with OOP in mind */
require_once __DIR__ . '/class/Settings.php';
require_once __DIR__ . '/class/SettingsPermalinks.php';

global $wp_rewrite;

$config = array(
	'title_page' => __( 'Permalink Settings' ),
	'parent' => 'settings/index.php'
);

new Settings_Permalinks_Page($wp_rewrite);
$template_content = Settings_Permalinks_Page::template_content($config);
