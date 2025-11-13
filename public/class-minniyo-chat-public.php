<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://minniyo.com
 * @since      1.0.0
 *
 * @package    Minniyo_Chat
 * @subpackage Minniyo_Chat/public
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Minniyo_Chat
 * @subpackage Minniyo_Chat/public
 * @author     Minniyo <support@minniyo.com>
 */
class Minniyo_Chat_Public {
	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $plugin_name       The name of the plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	/**
	 * Enqueue scripts for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		// Check if chatbot is enabled.
		$chatbot_enabled = get_option( 'minnch_enabled', '0' );

		if ( '1' !== $chatbot_enabled ) {
			return;
		}

		// Get API key.
		$api_key = get_option( 'minnch_api_key', '' );

		if ( empty( $api_key ) ) {
			return;
		}

		// Enqueue the external chatbot script.
		$embed_url = MINNCH_APP_EMBED_URL . esc_attr( $api_key );

		// Generate a unique script handle for the embed script.
		$script_handle = sprintf( '%s-embed', $this->plugin_name );

		wp_enqueue_script(
			$script_handle,
			esc_url( $embed_url ),
			array(),
			$this->version,
			array(
				'strategy'  => 'async',
				'in_footer' => true,
			)
		);

		// Add inline script to initialize the chatbot.
		$inline_script = 'var Minniyo_API=Minniyo_API||{}, Minniyo_LoadStart=new Date();';
		wp_add_inline_script( $script_handle, $inline_script, 'before' );
	}
}
