<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'local' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'root' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',          'EC0?GPlTlUH0//%Q_3CDA[0td[TOfImY7ubRoJa-l&O9qst&Q*Jbz^!vTf2rirq=' );
define( 'SECURE_AUTH_KEY',   ')qRML&A=dRFHrm9y ?d99XN<5`w:^~lG5{Gtq*:i1E(DA!j*M~AmLr{g@x~P8Cn5' );
define( 'LOGGED_IN_KEY',     '=B=T^e0-NyotuLH# <{)PZ^zX,#b^oCtYSI/PVeoU4TW$@>+]9Y$8XqnF_r)auoB' );
define( 'NONCE_KEY',         '@j%(=UIS$r8iG!?8_9 kDsTq&&{}ij8fEP}@U@pqeByo#eAbW<?*iNFR**BXiN>e' );
define( 'AUTH_SALT',         'V==-g[|hswMTd$R@!KHS8uEm9QTgY%lYr;y<m}HK(X[Oei}vpl3!u4so?(Qr^v7P' );
define( 'SECURE_AUTH_SALT',  'Ed`;y;AA$n}<] /_>ap%zghdX8XW, .dZjRU^Lm:bQs,<V V]+N];|dfl6_kQKB/' );
define( 'LOGGED_IN_SALT',    '>.71B6csxPk9}rj=D <8`P2?AVCBV;JhBWFL%`AcfP1oLVXoxFZR&D~q=>-Z)xgv' );
define( 'NONCE_SALT',        'xwQ:qU@I0d>YB:jnACC|YV=o:MP?&89dH[a*{x j-oulgV?eSe}&OEUPtl$5DTi8' );
define( 'WP_CACHE_KEY_SALT', '>3VPIDK(iJnFp/UUIw>@BD?34zc{AE>e%h(f}A_Ebics!.F!!L1X$s&J ?z2G?~u' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

/* Add any custom values between this line and the "stop editing" line. */



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
if ( ! defined( 'WP_DEBUG' ) ) {
	define( 'WP_DEBUG', false );
}

define( 'WP_ENVIRONMENT_TYPE', 'local' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
