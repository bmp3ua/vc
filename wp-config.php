<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'vc');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '124578');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '%+X`![dKT2mMK7 5.(~V&ODpOs+I0LmG%.-nWUzkhv[S*Y}%uYwRx%JKM#pv{>g;');
define('SECURE_AUTH_KEY',  '=VQkt}3g{&eoAj4>s08CS@@9=$rG`HZu0rJ!xJoyr]HMEPcNFU|yiHHB?VuIC@YC');
define('LOGGED_IN_KEY',    'o6I,MRCer=/l{pIDqM>5IZ6EA,qOajwQwEE1;m[xn]]cy4/D~Cl<3kE+$.mw1qA;');
define('NONCE_KEY',        'nQaX]z;f},b@VU_cTOa  ^QxiJ0&%yZn3@@WssUx ]p#>Nm(3a`g%C]~)?cWp-,G');
define('AUTH_SALT',        'rpK1d[#..nnLe^)mgw9H;tArE!t0Ob,opr#Stm@0:el!GUIa`=iZ*xjUYZ>f+[]3');
define('SECURE_AUTH_SALT', 'N$l?;:GCxOOD%!>!~RS)jRuZ8`=x{E~HTY$&|7@8W^Byos(WfyHu%hT xZo~/wsS');
define('LOGGED_IN_SALT',   'w2^om>[89}UlC5_BAz9Omo3`)B5;jWPm>*5or>Aoiy]czEoTW!dSZjlO8a`qK(1e');
define('NONCE_SALT',       '=}#gf~n0st<lVJ0m~C;avm.JEqbbQA993=ZA0mG+M^/p%NSaMhAn|49/5Cg[g!Ae');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
