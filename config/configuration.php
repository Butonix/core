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
 *
 * @package WordPress
 */
  
// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'charti_core' );

/** MySQL database username */
define( 'DB_USER', 'homestead' );

/** MySQL database password */
define( 'DB_PASSWORD', 'secret' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '#knYAJvpGMXq[PwKnFsb$`r?$@vzj[,,~{YfRuZV@:a-d)v$u}#}q~+[5JndD-6q' );
define( 'SECURE_AUTH_KEY',  'nH5EB9ynQXwm4IzmB)|Dl%MSQ5YMMjc`-?%/}y 1`vC}1>)no~bnv#d3?!Phe:)/' );
define( 'LOGGED_IN_KEY',    'kZ~_~*6(z+83sk,{k@y6r.@zZERu;!@~ihDhQxTP!$&va/0Rl@,9=z#bWM$14bRb' );
define( 'NONCE_KEY',        ';~=/5U4OfMe*`OcYpCx$H&Qp:7%f:v#Mjn&*Fc#:sW[*p4Dvf)IS6#Lp+kCl+re6' );
define( 'AUTH_SALT',        '= _5@bsyDCcx3e.#M4at#;N%MKwyW_cOYz`gpZVt:ncuQ>~o3[Hu]jOzjgxtD;ro' );
define( 'SECURE_AUTH_SALT', '{)owT2].DRm96KpTzCn. ) 4|7&*u{>:$(bFc/PUi?8AA<@(_Jz@l%]fhT#rY}3n' );
define( 'LOGGED_IN_SALT',   '[Tn4M5:O}(O@sFX)bUz1%0R{g[Eru>K.J,E?SP8[ciY|8P29HN,[XB/oj#hLJsBP' );
define( 'NONCE_SALT',       'bvw;_T@ow<3.B(`8QTej#EB_N?wMF&o XE&inb5Tynts$`?9nARh({i5Z$6sB3%D' );

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
define( 'WP_DEBUG', false );

// Use dev versions of core JS and CSS files (only needed if you are modifying these core files)
define( 'SCRIPT_DEBUG', true );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once CONFIG_DIR . 'settings.php';
