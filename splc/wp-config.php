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
define('DB_NAME', 'splc');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

define('WP_HOME', 'http://localhost/project-program/splc');

define('WP_SITEURL', 'http://localhost/project-program/splc');


/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '$2 @:02+64n-wLYaO3o<-k)+-gR@_wh-;iWj-HCVp.SyOGA`w6VhmDi|q47(n4:Z');
define('SECURE_AUTH_KEY',  'PZZ&SQ :eiWv-n*|(# v]Pf {CN,@&Rk}3IeTpyZ<m~R5Z24c 0Rj}wh ?0*viYZ');
define('LOGGED_IN_KEY',    'uDdVjV MddD<|~W3|$M0p-7GV7Fsa^y|2N9M=F}yY;@G+1<ba`]2RjP}o?N/i xv');
define('NONCE_KEY',        'P7`<Pr|y~Z-f>K8WG<j=q{w-It xz-{v2UNV,B,3-2q(5T0zp-5slME5]<!v{IPC');
define('AUTH_SALT',        'B%5ueSx`GZ:4RQ{iM1Hx<<#e; ;_>B[%Fpu=&{hj2q#U$q5483OK:@,W?;39-#s4');
define('SECURE_AUTH_SALT', 'r.E.5jIk4O|M.oz7|C>0IEX45vZe|VqKL1~<I&FZnHBkep~?>f$K3JEnt9a7aC`p');
define('LOGGED_IN_SALT',   'LLxQH/[nsr$A(2^#./e+1o43<SP>Pns|lq^ttyI &}:7<iC+o%hR`~|?2y`5@9#p');
define('NONCE_SALT',       '-_l(RM0i-_b_dLO=Lg0TqC<%~X+a-BK]BMiku1tlErhE>>[-rn,DkK]pP6iW|Azs');

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
