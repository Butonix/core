<?php
/**
 * The base configuration for WordPress
 *
 * The configuration.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "configuration.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-configuration-php/
 * @see https://gist.github.com/MikeNGarrett/e20d77ca8ba4ae62adf5 for more constants
 * @package WordPress
 */
// Let CoreProtector fight with all bad monkeys out there while you drink your tea
  //require_once(__DIR__  . '/CoreProtector.php');

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'scotchbox' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', 'root' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

define( 'COOKIEPATH',  $_SERVER['HTTP_HOST'] . '/' ); // You should set this explicitely. 
define( 'COOKIE_DOMAIN', $_SERVER[ 'HTTP_HOST' ] );
define( 'ADMIN_COOKIE_PATH', $_SERVER[ 'HTTP_HOST' . ADMIN_DIR ]);
define( 'SITECOOKIEPATH', $_SERVER['HTTP_HOST'] . '/' ); // You should set this explicitely. 
define( 'PLUGINS_COOKIE_PATH', preg_replace( '|https?://[^/]+|i', '', WP_PLUGIN_URL ) );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'HQ5lAolX#TA_`H!$]<H/2|(87S;`,Qdv1Rfhl*$x>Q{#fvPZh^h:<MduOxT^~HeD' );
define( 'SECURE_AUTH_KEY',  'W6HyLB5,mP{}rsHJae!7M# 0*T*[?kPY|o-` PI{ny K~&Q;E+A,87*Yha]XPvwY' );
define( 'LOGGED_IN_KEY',    ')^$w81@YR4/M1EP2)2R[PkFm[1f+l=PxNo{yw8wj?)8aQSd(&yTz$fq5F<j(Q!)~' );
define( 'NONCE_KEY',        'w[?f7*SDTmn~keA1oq/(>wWEJ&>t^ALHyVc;h~C,$5H=)wt~NKx5zEN%j^|i2i.L' );
define( 'AUTH_SALT',        '}<-zC>?)X>-R;wQ:0lmk.WtM+n#b/?[O?w[jX^g_FdVt5Ic[0ysV5_mowmB6m]9E' );
define( 'SECURE_AUTH_SALT', '2V;_(Xb$u>_)iVf}&(Yz[C$!~Mg3DMMgpx.qx{1bTt/P:uxA,*:d;e(pmg@RKpZ0' );
define( 'LOGGED_IN_SALT',   '{WAcEAU2hX~FNt$L[yi x)8_t!@&=H^ab~(5z%iHy(HHf+Nxl+J@fM~5dc/z:ICa' );
define( 'NONCE_SALT',       'rs%(_~uJK7szoS_z47vj4$jY0N+/EP(k7T71wi+;7Ma#mZmYA$~+lq#;APndhM<O' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', true );

// Use dev versions of core JS and CSS files (only needed if you are modifying these core files)
define( 'SCRIPT_DEBUG', true );

/** Enable Cache by WP Rocket */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once 'settings.php';
