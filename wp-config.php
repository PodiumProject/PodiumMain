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
define('DB_NAME', 'u241080205_vynex');

/** MySQL database username */
define('DB_USER', 'u241080205_hysaw');

/** MySQL database password */
define('DB_PASSWORD', 'HyDyJyWaqe');

/** MySQL hostname */
define('DB_HOST', 'mysql');

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
define('AUTH_KEY',         't74s1esvaclavh39PryjDUvx4TzxLWNpiFH5bnhnCCJ0lb7D6Z7vQVWJTZfZNhaO');
define('SECURE_AUTH_KEY',  'JOXzl7o2TCGjYIdAqzEeuDMDKNTIyR7U6gaDoev8qnJXrXU4VMXAIlrCRqYbOeLN');
define('LOGGED_IN_KEY',    'BnxO9qkhn6MzpGIYRlbAiYaDYipnq4Vjdm0RELL7FoaLoWGDL7lkFtWgmHI7m15p');
define('NONCE_KEY',        'Wd7jGRmKSIq38lA3VCVvXysj3UWBhndf2t1a8jkellxdLOyjRuDsEj5FyI45fXW5');
define('AUTH_SALT',        'iGCfvXKYJwlavdU5Rv3lahBIppTiEmJSU5Cky2eIY7ZpF5tdSQ1ObnAsv70o7CeA');
define('SECURE_AUTH_SALT', 'kRg9EfybgLXFR6u0OzIklOpRVuymrPmlu64hBqtisyIwavtB4ia1nwspCooOY9Ht');
define('LOGGED_IN_SALT',   'b3sejTk25vGUVM1G4Wp9duUrHpAXtDrHaCJbR7CYo1TzCVoCx36HsIZbDSUQyqpd');
define('NONCE_SALT',       'RFzUBLpNn69uT4iPf7QjzB8KZqFLUoYr40Vxa870wogHu7PuqCVeUF3Kqm6nvWga');

/**
 * Other customizations.
 */
define('FS_METHOD','direct');define('FS_CHMOD_DIR',0755);define('FS_CHMOD_FILE',0644);
define('WP_TEMP_DIR',dirname(__FILE__).'/wp-content/uploads');

/**
 * Turn off automatic updates since these are managed upstream.
 */
define('AUTOMATIC_UPDATER_DISABLED', true);


/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'lgan_';

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
