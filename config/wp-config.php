<?php
/**
 * WordPress base configuration.
 */

// ** Database settings - You can get this info from your web host ** //
define('DB_NAME', 'wordpress');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('DB_HOST', 'localhost');
define('DB_CHARSET', 'utf8');
define('DB_COLLATE', '');

/**#@+
 * Authentication unique keys and salts.
 */
define('AUTH_KEY',         'mzftrynx1tov4iverxx0pevrf5jxqm6ywepdtaproyv6ksioz3mg0dnciciyimcd');
define('SECURE_AUTH_KEY',  '9q8pnirxy4mum5y9vcykstdfslzms9njzmexjdfv6xhvvtmnepborclrghqj5ecw');
define('LOGGED_IN_KEY',    'rprncrvw7hwgrk5wpb0rugvcctuxhdhxugitxitifjcjamkf6l6uofxnavswk8th');
define('NONCE_KEY',        'eoxch6tfirekyjiqsfhdkyi4yatskguufa8ykolxyi0a2miin45abwqc2hkbtm1b');
define('AUTH_SALT',        'lzwcb7z0yjs00jduzzwnpntbcyj4l4icb6i1bihjgl5ppixinirssvsjh9mbjvqq');
define('SECURE_AUTH_SALT', 'z3leovdafvjxw2lsl95fmpjbiwtap5tda4zuvolycop5owttfviqbthzmx6xsuwr');
define('LOGGED_IN_SALT',   'xujstsn3waudrekwegvenkjdyzvlkakuqvmczis1ptq4gj3x58muygc1vypxlmb4');
define('NONCE_SALT',       'atrmd1mhrrtksubvtzwcaj97p1kaisdcvqd9trfds7sfnvr9sjeagzw2tf0c4akp');

/**#@-*/

/**
 * WordPress database table prefix.
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 */
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', true);
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Define site URL and home URL
define('WP_HOME', 'http://localhost/wordpress');
define('WP_SITEURL', 'http://localhost/wordpress');

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if (!defined('ABSPATH')) {
    define('ABSPATH', __DIR__ . '/');
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php'; 