<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'wordpress_581' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

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
define( 'AUTH_KEY',         'EjzN)|xAeI;1ysoX!^j.v$C_ENLJCLY <,4Y{q8hhXoi8PewN2ku|lzv3dVYpix(' );
define( 'SECURE_AUTH_KEY',  'n`-raTV|s6CBphT{Gz<5t+w;4`2Blrn1LuE&[kXC;J)]SBV$)x5D>n3b(_Oahre6' );
define( 'LOGGED_IN_KEY',    'yc2aE?Oy&iLh_04t qUG88%uT_l]*1* H].m ^J:KzU&c|@xc/cdXf~g<XS#t5uQ' );
define( 'NONCE_KEY',        'wsdK1xS~ vsiE4<?T]L]zf.IzrC<oZJO;KF:@Rk}1i:38uX5 +an.Jm~-(pFqbcH' );
define( 'AUTH_SALT',        '+p0KN#?/z;T^ +f4mNg-L-j}.rZZb9Gw*;fJEnQrGsdGy >(MaYn :gQ2 ?9-}ZA' );
define( 'SECURE_AUTH_SALT', 'V3N<RyF:^|E@oy_,4Qc@cD,&VS~qW@j0|]pw|ZkAt& )M91JS7r+ho$io:JQxGw5' );
define( 'LOGGED_IN_SALT',   'k+n6WL6{1b4VafokH)).8 B0nDO0o8s1+Lx@r>L+Kt/~,>bCg>vsKDzX&; fn1H:' );
define( 'NONCE_SALT',       'v{[:W`^M~$s^*XxU1TjQ?b=vstn--I+6J1c0F7.5<N:]K766SO^P^(ulzH :Pb37' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
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
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
