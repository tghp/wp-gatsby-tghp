<?php
/**
 * Plugin Name: WP Gatsby TGHP
 * Description: Additional optimisations for a WordPress site to as a source for Gatsby sites.
 * Author: TGHP
 * Version: 1.1.0
 */

// Define constants
define('WP_GATSBY_TGHP_PLUGIN_VERSION', '1.1.0');
define('WP_GATSBY_TGHP_PLUGIN_NAME', 'wp-gatsby-tghp');
define('WP_GATSBY_TGHP_PLUGIN_PATH', dirname(__FILE__));
define('WP_GATSBY_TGHP_PLUGIN_URL', untrailingslashit(plugins_url('/', __FILE__)));


// Exit if accessed directly.
if (! defined('ABSPATH') ) {
    exit;
}
// Abort plugin loading if WordPress is upgrading
if (defined('WP_INSTALLING') && WP_INSTALLING) {
    exit;
}

// Init plugin
require WP_GATSBY_TGHP_PLUGIN_PATH . '/src/WPGatsbyTGHP.php';

/**
 * Return WorldsFair instance
 *
 * @return \TGHP\WPGatsbyTGHP\WPGatsbyTGHP
 */
function WPGatsbyTGHP()
{
    return \TGHP\WPGatsbyTGHP\WPGatsbyTGHP::instance();
}

WPGatsbyTGHP();
