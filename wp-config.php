<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'cl56-a-wordp-ys9');

/** MySQL database username */
define('DB_USER', 'cl56-a-wordp-ys9');

/** MySQL database password */
define('DB_PASSWORD', 'K!fH3V6t3');

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
define('AUTH_KEY',         'AtPggGQrAymkxixG2i6OuCB^mi8j(Q)c5O!qq#u//S4_(E3tOnA_xY=Us(hvpGU(');
define('SECURE_AUTH_KEY',  '=p5SwHh34jxcxlZp##^4E1NH_WqrEG#!HSOLrNUV+YzNDviRxFN3fLmkJMvhLHm!');
define('LOGGED_IN_KEY',    'ENZHsnj8wqnaC7fcezY85fz_!p3a0zXasuSsbN=FL+m52Un2m(GSG5V1-plpAA!u');
define('NONCE_KEY',        'KV#s7k!ds1FkPLwkp4BT+5fk0pG_M2#7XH+MyLz78LM(yR/Mxy9F7d7JymQIEtJW');
define('AUTH_SALT',        'mX7mN_npOtI=rejgofyv7W(Tj34TFbqZftIIgL4kVi5t8kDm0Woa8/oNtM45^f#O');
define('SECURE_AUTH_SALT', 'gP0z5eTc(L!NzI)QPf1e3Un_#shtLASGLwbS^6cm-eG2j9PDwDNdBKtLlji+VJH-');
define('LOGGED_IN_SALT',   'jBhF/TF(ahl_-/2YN6hFI25kKoh2i3Y^oacWga-q0eIryeQ0Pd6J!WN_EJu43lZc');
define('NONCE_SALT',       'A8-Fq3tieTX68kXnglmT5K+urHR4#Bg-6ylJbz/yyK+vrqC9z8A=#q-p/l0qbd4I');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de.mo to wp-content/languages and set WPLANG to 'de' to enable German
 * language support.
 */
define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/**
 *  Change this to true to run multiple blogs on this installation.
 *  Then login as admin and go to Tools -> Network
 */
define('WP_ALLOW_MULTISITE', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

/* Destination directory for file streaming */
define('WP_TEMP_DIR', ABSPATH . 'wp-content/');

