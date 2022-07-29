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
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'web_db' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', 'Sndfld113!' );

/** MySQL hostname */
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
define('AUTH_KEY',         'r5vK6C*GlZG+a)tQp8L{cDSckkDV??36-OT!CL|8+TtYIaQ!B)P}k#H;-|9dzp1u');
define('SECURE_AUTH_KEY',  'l?+K,Xk9`OPhdJ+8IvV/3vdH&!T8B5P(zA~q4M;`6?eQwC]MB34|G;4+`6lbYKsK');
define('LOGGED_IN_KEY',    'xO6fMfV1o~>cAM>hElgl6d{JpESb:w [IDWysV]h2E05@9rMd@bsiG8FH8D|J`i|');
define('NONCE_KEY',        ';9A0~M.G#J7I?x+Is0Xry,FMU%L$Q jf=`.~40U_PMtB|S [=RM%bLU5KF+AMvhu');
define('AUTH_SALT',        'TJg|$yn4sbx]1ie8LBXFPJ}LEXPe1h-f<,fe~|@Om@*6Zl+{G~xKkMW(g&<L6knQ');
define('SECURE_AUTH_SALT', 'NQ,#>KTL_SBS-80XIA+hv5;,-Lj.^+xRRvs?a&aozbRVD>H3|7uVv63C>n.uKnJ)');
define('LOGGED_IN_SALT',   '8KNF:v Q9`<+Pi$tvf1k-m[x5q0PluqPF[!{ hH3+Mba>_YqokT!Gw@T]0[y6^kH');
define('NONCE_SALT',       ' M`k?Yj%j WQKy6-?asmpEqp3xcqc1b?4{zr{)qU)$>?uxR/4DK9&N=ay{];qN:!');

/**#@-*/

/**
 * WordPress database table prefix.
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

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
