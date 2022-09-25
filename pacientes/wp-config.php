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
define( 'DB_NAME', 'laesper1_website' );

/** MySQL database username */
define( 'DB_USER', 'laesper1_website' );

/** MySQL database password */
define( 'DB_PASSWORD', 'laesperanza2021#' );

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
define( 'AUTH_KEY',         'xcl8w4l<ljO_14g{wI?L;@J7T}pR<{6oJ(7Kma{E0S+!?c8T%},f82`x5d-aI~dX' );
define( 'SECURE_AUTH_KEY',  'GZ!e[Ztlr.rxbn,)(J`oyo.Bc[G7^8[tN.<ge:W{~>5@/5g>Jb[G1T[xejwUm;`U' );
define( 'LOGGED_IN_KEY',    '82Y,|by,q0jTcQo_/lCs7xPs/}p,4-;&g#[owliW_MS$-R6)R6`l|@X=vV>h&!gS' );
define( 'NONCE_KEY',        '!`vS^QYgU* -/GoOe;8+n<7=r5[FTQ*x>iGb{ L^e1KD}sf|V<Gg~.0Af+WKp !R' );
define( 'AUTH_SALT',        'qmrbqvI<JUZsm3c>ZxeOH%2@EJF6zv1W7}Apywi#`Z]N=B*H8H|c!VL2,Fo]dP=e' );
define( 'SECURE_AUTH_SALT', '/Y:v0vQ):M,/fno=o(7L~,C0tutxD%Sv/~W[?2Mr:sN1;{JZ*5w0m$a!W+7A]PRP' );
define( 'LOGGED_IN_SALT',   '0/+c7r4aW@4R??#|C0s#Cs8{T8le3[2.1u]<&iP:l?QaCh`!nK|!EvoE885FNld=' );
define( 'NONCE_SALT',       'We4L`tS=+uH4AQYH:Nq5`oLg9My#D$R(C2bF|D`lNz%N?4I5G]B=o0-#mm<y(1Rr' );

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
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );
