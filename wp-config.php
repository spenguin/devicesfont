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
define( 'DB_NAME', 'wp_devicefonts' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

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
define( 'AUTH_KEY',         'bAKf?/daRq4N/VKp<r|&9!EMD3ibNK(<wgq7`@TX+e%5J!oNwG?]J1Kl+3`f4ZpD' );
define( 'SECURE_AUTH_KEY',  ',LdU$Il#hmoeN]:PEdwii 7A9tdpNYiGRIF7f&zP}24TVxAZ`6YpWYze<j}/Tfw,' );
define( 'LOGGED_IN_KEY',    ')0][;Z;cx&+d))rMoGf+?1eb2kOgw;$K57oJ{xOL.f$XsWi0yRA6H{b/od1r+@>B' );
define( 'NONCE_KEY',        '4DA3Bn#/uv`b_~8S59^eMZhtxKtx;s[GP!BcY6;!Tx1*YyjRNvDl/-EBxtP9r;Bx' );
define( 'AUTH_SALT',        'iGq[YE7U%F?3p&x>.Rm:LCgmuEn=IfMS#3rq4EWNGN8G;5161KeyTw%3f(&g#l1+' );
define( 'SECURE_AUTH_SALT', '%*f(#|Q)2eri_dFNAFE!R[_vE>8YXTF^acqYcP4}.#:80;th(rPS^B2*Fxwe%5`8' );
define( 'LOGGED_IN_SALT',   '+!_FiMymWrh+3Iq7#};$&qb.fk>1J%Dx/0(?i;JH2GM~?sP${p^?jW%;`6Mnp}!v' );
define( 'NONCE_SALT',       'T5[-a. W^MFM_sy{@,cJ{mJ{~s`8jgI+p{rTn-quy[X,V |w.H2(.JG%P+oB44s0' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'df_';

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
