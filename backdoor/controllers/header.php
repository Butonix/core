<?php
/**
 * About This Version administration panel.
 *
 * @package WordPress
 * @subpackage Administration
 */

/** WordPress Administration Bootstrap */
require_once ABSPATH . ADMIN_DIR . '/admin.php';

/* translators: Page title of the About WordPress page in the admin. */

list( $display_version ) = explode( '-', get_bloginfo( 'version' ) );

require_once ABSPATH . ADMIN_DIR . '/admin-header.php';

?>