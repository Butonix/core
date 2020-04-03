<?php
/**
 * Reading settings administration panel.
 *
 * @package WordPress
 * @subpackage Administration
 */

/** WordPress Administration Bootstrap */
require_once __DIR__ . '/../admin.php';
require_once __DIR__ . '/class/Settings.php';
require_once __DIR__ . '/class/SettingsReading.php';


global $wp_rewrite;

$config = array(
	'title_page' => __( 'Reading Settings' ),
	'parent' => 'settings/index.php'
);

new Settings_Reading_Page($wp_rewrite);
$template_content = Settings_Reading_Page::template_content($config);


if ( ! current_user_can( 'manage_options' ) ) {
	wp_die( __( 'Sorry, you are not allowed to manage options for this site.' ) );
}

$title       = __( 'Reading Settings' );
$parent_file = 'settings/index.php';

//add_action( 'admin_head', 'options_reading_add_js' );

?>
