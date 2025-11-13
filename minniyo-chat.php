<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://minniyo.com
 * @since             1.0.0
 * @package           Minniyo_Chat
 *
 * @wordpress-plugin
 * Plugin Name:       Minniyo Chat
 * Plugin URI:        https://wordpress.org/plugins/minniyo-chat/
 * Description:       Connect your Minniyo Chatbot in WordPress Easily and Hassle Free.
 * Version:           1.0.1
 * Author:            Minniyo
 * Author URI:        https://minniyo.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       minniyo-chat
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Set up the plugin constants.
 * This is where you can define all the constants used throughout the plugin.
 */
define( 'MINNCH_VERSION', '1.0.1' );
define( 'MINNCH_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'MINNCH_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'MINNCH_APP_URL', 'https://app.minniyo.com' );
define( 'MINNCH_APP_EMBED_URL', 'https://app.minniyo.com/chat/embed/' );
define( 'MINNCH_LANDING_PAGE_URL', 'https://minniyo.com' );
define( 'MINNCH_SUPPORT_EMAIL', 'support@minniyo.com' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-minniyo-chat-activator.php
 */
function minnch_activate_plugin() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-minniyo-chat-activator.php';
	Minniyo_Chat_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-minniyo-chat-deactivator.php
 */
function minnch_deactivate_plugin() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-minniyo-chat-deactivator.php';
	Minniyo_Chat_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'minnch_activate_plugin' );
register_deactivation_hook( __FILE__, 'minnch_deactivate_plugin' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-minniyo-chat.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function minnch_run() {
	$plugin = new Minniyo_Chat();
	$plugin->run();
}

minnch_run(); // Kick off the plugin.
