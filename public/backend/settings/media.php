<?php
/**
 * Media settings administration panel.
 *
 * @package WordPress
 * @subpackage Administration
 *
 *	Charti CMS OOP in Mind
 *	@since 0.1
 */

/** WordPress Administration Bootstrap */
require_once __DIR__ . '/../admin.php';
/* Converted to class with OOP in mind */
require_once __DIR__ . '/class/Settings.php'; // The Parent Class that we are going to extend
require_once __DIR__ . '/class/SettingsMedia.php';

$config = array(
	'title_page' => __( 'Media Settings' ),
	'parent' => 'settings/index.php'
);

new Settings_Media_Page($config);

?>
