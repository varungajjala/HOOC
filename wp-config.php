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
define('DB_NAME', 'wordpress');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'admin');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

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
define('AUTH_KEY',         '#cglV]iVhL^fr]<W>hUImhVu|%s|QGYR!QE,8bc?B|:Zor,G_<+k1B^$0^!x-NN-');
define('SECURE_AUTH_KEY',  'qA^V5lG1PZlZ$.&f3<)[ObYg%Dc^OQVi+6&+x47?SO=Uqmf,Qug -4`=%QQ|le >');
define('LOGGED_IN_KEY',    '~v(Mg)bJMwhc1BmRNJ7kv,U_8MG`||_TE?)LUA<UNGXU4.S+fww_QAKgT+CgQ7OY');
define('NONCE_KEY',        'xxKc,FcZzFsU:zQ eC5>H)t+Xq3=eGY;+i(t^@5WaK0=%U%@#DUd}Y-L|z}s,-s.');
define('AUTH_SALT',        '|61;E<)ie_ayz2Qq|Hpy#-&}zE-NoFJy`D>xF{4Pn`L+L~yrcOe=~WC6V`-vs9K5');
define('SECURE_AUTH_SALT', '1c.l4+r+ur:BgMj=I%$Y{}~1Cy{o_&Tvkm%SO{J>y:#DzKB>+iw-~10$72bXb `x');
define('LOGGED_IN_SALT',   'ww glY<j:98A/z=Ml~@hw%vc]-ux9o&H$%I}RpFzd7vP<`|d_ZnegxA-.}L-Yj~`');
define('NONCE_SALT',       'e4vsAkdlDq`OC>yo|lYz:>e0Y4s =>7-!DyRd+GIY@t()dY26lFF<yvMB?HBJF M');

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
