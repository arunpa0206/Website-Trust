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
define( 'DB_NAME', 'arun-charity' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

/** MySQL hostname */
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
define( 'AUTH_KEY',         '#krDQNmbstHFA&g$O6P+ <1.Qm)u}T%rXnws`8I3K|MqE#B[!4bx)[Qr7{5 sw`,' );
define( 'SECURE_AUTH_KEY',  'es6c>pZx;aV]{uICh]6}`y7)IT[Up/ FW|THfy1(gu=~Zl VR.sqvbMU]_X0p24Q' );
define( 'LOGGED_IN_KEY',    ':AKPcCT.S%a0#uVC*Ku(oWNL1) |/]OiITN}*%$|;6;[MrjQU7l3{!L~2t.amZPz' );
define( 'NONCE_KEY',        '45Q nP#$O$5J`xkCcD|`U99!m39UDN]dX>B/Uxy|bZ}s8-F{n@+u,Cj^tB$#  #&' );
define( 'AUTH_SALT',        'geLe6F7lmvy/2aq{J}Pa0^Gm*0EG4).6oUfn4IA]FRXbqNsIqIro$;QVvO}HA$9o' );
define( 'SECURE_AUTH_SALT', 'zLD2m+?5PQN. ^5Y-YDq=U4mxvC9u;{>]#zNBK7v==(hu{_%t k+V=fNwTf[#E(!' );
define( 'LOGGED_IN_SALT',   '>DZk(iaVJoztUh6/i.XZ<EqBWSG&%j#eMC#DnIM4!0CmU/r:!}gYcyrzz2XVy58H' );
define( 'NONCE_SALT',       '. 2G7>&DCYD`&URt:aKGQ&N~Q+{e(*hZ;MgqxQ}F7T@4d~#krI?EPUKAZZ[(Qw*o' );

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
