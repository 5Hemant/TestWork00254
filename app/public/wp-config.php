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
define( 'AUTH_KEY',          'GS9%R!9/HlR?_b8H[h-W_=1+NUbt*#(zedr_A}O#CQ`tq}p/lMXaA[VyjgH:XINy' );
define( 'SECURE_AUTH_KEY',   'p{3cTe.fow-B%JgI8A&vxp_F@r~M@KuqD9l`Dje9RK9F6aKs~*]Uy=@DxAfTe*vk' );
define( 'LOGGED_IN_KEY',     '-BY*3yysY:q+_qpJ=9r:+BhZ)$Kr9J*~D<~hTafHg<-xf}y^_MlB/xkUyNoudGi~' );
define( 'NONCE_KEY',         '2-[>LJxMk^&4{$kY.`J*-QX$qQI-M(v)0 ?_Qo2r*)?tp~Lle~_f9;fQ|Bp>EH(g' );
define( 'AUTH_SALT',         '9r:Ei#&^_@=zkBS>FW+IOPu:~%`4*xC=KPH($1aV< ~0>57Jg9$RgP)MbdKQ79[Y' );
define( 'SECURE_AUTH_SALT',  '2hm}]Xt[}{17iRzPn8mOVCkoC>#s9dtlP>S{&4_QPVu&Q$f$.RLo<|T8wwFB0gzl' );
define( 'LOGGED_IN_SALT',    '^T{1z6|P?z|e#njUKMhgMFnI$K./_D4oP I9_#_qNIoD4(ew{|pe{g*CvtlbV:HW' );
define( 'NONCE_SALT',        'm?xochJWP/<Vcg3GL8._~Mq$5+>{6G5HZ3:Z4HBY4Sj4bBd!3 ,J:S5070]z5:O_' );
define( 'WP_CACHE_KEY_SALT', 'I1h$Ajn|@eLl/uC*(Vfn iA#zu1az|K=be`(vO7;J~M`gVKv;t07Wk]p&V]g3OG~' );


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
