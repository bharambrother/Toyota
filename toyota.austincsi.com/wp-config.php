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
define('DB_NAME', 'talentlink');

/** MySQL database username */
define('DB_USER', 'yy4duxcdh4j7yg9s');

/** MySQL database password */
define('DB_PASSWORD', 'man56k8g7y48gvgz');

/** MySQL hostname */
define('DB_HOST', 'database.toyota.austincsi.com');

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
define('AUTH_KEY',         'lkwwplpyfayacibwckcjkfkhrxr2uzlie9clzbbdbqoysak1vfeduw7o0gsxced0');
define('SECURE_AUTH_KEY',  'sp2mqml8yerrowpegxpwqezlioshwgfdd9loxy3ntf6qzfpgzkellis9ezqie8zh');
define('LOGGED_IN_KEY',    '5c5xwcj7hlaicyjcrjl84h6rflnquhmlatgpcwcsav3rc05chm2h5ulo2bens4qt');
define('NONCE_KEY',        'jiei3dom99lczypkgmazv0zgwl8hsye488elx76pgxgy3rbcbhrckfu9re4093va');
define('AUTH_SALT',        'kvysw4urw1tbxqtgsogyajqde2tdve4zr5c0ozvm3zuv0hi7g8lwnrbdmjdztqd6');
define('SECURE_AUTH_SALT', 'kq4nanql4tta91isycejivv2xki3r0fqkf58wgqwolwo2lixlowkwfv7awkpwoak');
define('LOGGED_IN_SALT',   'dav3ue68mnbeksnmygalpx7bdoi3bfmwsapisjmuqz7d76xqucefoxusbq5buhut');
define('NONCE_SALT',       'cdm0htczfhnxlfk2lzjvstt0cspdtkqv3kyemicayqedbmuolyzfj3y9gimxtk11');

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
