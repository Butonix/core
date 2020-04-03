<?php
/**
 * Core Administration API
 *
 * @package WordPress
 * @subpackage Administration
 * @since 2.3.0
 */

if ( ! defined( 'WP_ADMIN' ) ) {
	/*
	 * This file is being included from a file other than ADMIN_DIR/admin.php, so
	 * some setup was skipped. Make sure the admin message catalog is loaded since
	 * load_default_textdomain() will not have done so in this context.
	 */
	load_textdomain( 'default', WP_LANG_DIR . '/admin-' . get_locale() . '.mo' );
}

/** WordPress Administration Hooks */
require_once ABSPATH . ADMIN_ROOT . '/includes/admin-filters.php';

/** WordPress Administration File API */
require_once ABSPATH . ADMIN_ROOT . '/includes/file.php';

/** WordPress Image Administration API */
require_once ABSPATH . ADMIN_ROOT . '/includes/image.php';

/** WordPress Media Administration API */
require_once ABSPATH . ADMIN_ROOT . '/includes/media.php';

/** WordPress Import Administration API */
require_once ABSPATH . ADMIN_ROOT . '/includes/import.php';

/** WordPress Misc Administration API */
require_once ABSPATH . ADMIN_ROOT . '/includes/misc.php';

/** WordPress Misc Administration API */
require_once ABSPATH . ADMIN_ROOT . '/includes/class-wp-privacy-policy-content.php';

/** WordPress Options Administration API */
require_once ABSPATH . ADMIN_ROOT . '/includes/options.php';

/** Plugin Administration API */
require_once ABSPATH . ADMIN_ROOT . '/includes/class-plugin-initializer.php';

/** WordPress Post Administration API */
require_once ABSPATH . ADMIN_ROOT . '/includes/post.php';

/** WordPress Administration Screen API */
require_once ABSPATH . ADMIN_ROOT . '/includes/class-wp-screen.php';
require_once ABSPATH . ADMIN_ROOT . '/includes/screen.php';

/** WordPress Taxonomy Administration API */
require_once ABSPATH . ADMIN_ROOT . '/includes/taxonomy.php';

/** WordPress Template Administration API */
require_once ABSPATH . ADMIN_ROOT . '/includes/template.php';

/** WordPress List Table Administration API and base class */
require_once ABSPATH . ADMIN_ROOT . '/includes/class-wp-list-table.php';
require_once ABSPATH . ADMIN_ROOT . '/includes/class-wp-list-table-compat.php';
require_once ABSPATH . ADMIN_ROOT . '/includes/list-table.php';

/** WordPress Theme Administration API */
require_once ABSPATH . ADMIN_ROOT . '/includes/theme.php';

/** WordPress Privacy Functions */
require_once ABSPATH . ADMIN_ROOT . '/includes/privacy-tools.php';

/** WordPress Privacy List Table classes. */
// Previously in ADMIN_DIR/includes/user.php. Need to be loaded for backward compatibility.
require_once ABSPATH . ADMIN_ROOT . '/includes/class-wp-privacy-requests-table.php';
require_once ABSPATH . ADMIN_ROOT . '/includes/class-wp-privacy-data-export-requests-list-table.php';
require_once ABSPATH . ADMIN_ROOT . '/includes/class-wp-privacy-data-removal-requests-list-table.php';

/** WordPress User Administration API */
require_once ABSPATH . ADMIN_ROOT . '/includes/user.php';

/** WordPress Site Icon API */
//require_once ABSPATH . ADMIN_DIR . '/includes/class-wp-site-icon.php';

/** WordPress Update Administration API */
require_once ABSPATH . ADMIN_ROOT . '/includes/update.php';

/** WordPress Deprecated Administration API */
//require_once ABSPATH . ADMIN_DIR . '/includes/deprecated.php';

/** WordPress Multisite support API */
if ( is_multisite() ) {
	require_once ABSPATH . ADMIN_ROOT . '/includes/ms-admin-filters.php';
	require_once ABSPATH . ADMIN_ROOT . '/includes/ms.php';
	require_once ABSPATH . ADMIN_ROOT . '/includes/ms-deprecated.php';
}
