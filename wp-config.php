<?php
define( 'WP_CACHE', true );

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
define( 'DB_NAME', 'sennovate' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', '127.0.0.1' );

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
define( 'AUTH_KEY',          'DHhqA~Cb`g` R41fh3IF=G=BoY?5@*gQg<C7])?0W7g?U3MO#nMI-g~oli$3Y[OD' );
define( 'SECURE_AUTH_KEY',   '~meX)d9YP`$H!~7eWV Xjb0r{pc~}s><qzWSt$z;.ekc:zRo8r&>1Zb!M,:IYKjH' );
define( 'LOGGED_IN_KEY',     'Ie!s}8r1)Y{zPb~n.()*/}/k(?]kr]/}1%?j~CTMdTyO4K=w<I1Q#,JkH n[?S@s' );
define( 'NONCE_KEY',         'T^$U%3l2B}W.8 d#>,MC:=kwec:k-jx;?^3i#^%>th[Idj)j^ t6 P#|E9@rSxSZ' );
define( 'AUTH_SALT',         'BP=%=5`n?{&J]p+1-%E7vWcY[PCkw/Zz`*XrFck_sQf)1jh4Qn.ZC[U9B*1wCxtx' );
define( 'SECURE_AUTH_SALT',  '5H? Xe0(KE`<]a@}qHmMlh$Zg9gIEFZ,$F<Qf]gBbo$>!aq8x.o&~H{H~%)f,YK$' );
define( 'LOGGED_IN_SALT',    'k}%gfvXd^.-F=@@0~4Z/_TptWF:NHeuhT=G7>Oe={y6S/Loxp#3/YhenUV&`0bGB' );
define( 'NONCE_SALT',        '@S+L mZ;}#,T9XQO4mv;Y#v[=[>^ G)ggZi5K];Z13$^WGVoJD3#-Yz*&eNF@D#+' );
define( 'WP_CACHE_KEY_SALT', 'YsIwv(RW.b[xz<v%L?pPk2B~56 MP+V@x1Ewr=>lKxuEf`E[oi[&g|d/%N#D>}~=' );


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
	define( 'WP_DEBUG', true );
	define( 'WP_DEBUG_LOG', true );
	define( 'WP_DEBUG_DISPLAY', false );
	@ini_set( 'display_errors', 0 );
}

define( 'FS_METHOD', 'direct' );
define( 'COOKIEHASH', '6f39216c320451b54050a17fe93eaf98' );
define( 'WP_AUTO_UPDATE_CORE', 'minor' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
