<?php
/**
 * Privacy Policy Guide Screen.
 *
 * @package WordPress
 * @subpackage Administration
 */

/** WordPress Administration Bootstrap */
require_once __DIR__ . '/admin.php';

if ( ! current_user_can( 'manage_privacy_options' ) ) {
	wp_die( __( 'Sorry, you are not allowed to manage privacy on this site.' ) );
}

if ( ! class_exists( 'WP_Privacy_Policy_Content' ) ) {
	include_once ABSPATH . ADMIN_DIR . '/includes/class-wp-privacy-policy-content.php';
}

$title = __( 'Privacy Policy Guide' );

wp_enqueue_script( 'privacy-tools' );

require_once ABSPATH . ADMIN_DIR . '/admin-header.php';

?>
<div class="wrap">
	<h1><?php echo esc_html( $title ); ?></h1>

	<div class="wp-privacy-policy-guide">
		<?php WP_Privacy_Policy_Content::privacy_policy_guide(); ?>
	</div>
</div>
<?php

require_once ABSPATH . ADMIN_DIR . '/admin-footer.php';
