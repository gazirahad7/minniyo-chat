<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://minniyo.com
 * @since      1.0.0
 *
 * @package    Minniyo_Chat
 * @subpackage Minniyo_Chat/admin
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Minniyo_Chat
 * @subpackage Minniyo_Chat/admin
 * @author     Minniyo <support@minniyo.com>
 */
class Minniyo_Chat_Admin {
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
	 * @param      string $plugin_name       The name of this plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		if ( ! $this->is_valid_page() ) {
			return;
		}

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/minniyo-chat-admin.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		if ( ! $this->is_valid_page() ) {
			return;
		}

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/minniyo-chat-admin.js', array( 'jquery' ), $this->version, false );

		// Pass nonce and AJAX URL to JavaScript.
		wp_localize_script(
			$this->plugin_name,
			'minnch_ajax',
			array(
				'nonce' => wp_create_nonce( 'minnch_nonce' ),
			)
		);
	}

	/**
	 * Check if current page is valid to load scripts
	 *
	 * @since    1.0.0
	 * @return   boolean
	 */
	public function is_valid_page() {
		$screen = get_current_screen();
		if ( isset( $screen->id ) && in_array( $screen->id, array( 'toplevel_page_minniyo-chat' ), true ) ) {
			return true;
		}
		return false;
	}

	/**
	 * Register the admin menu
	 *
	 * @since    1.0.0
	 */
	public function menus() {

		// Add main menu page.
		add_menu_page(
			'Minniyo Chat - Integrate Chatbot with WordPress',
			'Minniyo Chat',
			'manage_options',
			'minniyo-chat',
			array( $this, 'chat_configuration' ),
			plugin_dir_url( __FILE__ ) . 'images/minniyo-icon.png',
			26
		);
	}

	/**
	 * Display Chat Configuration Page
	 *
	 * @since    1.0.0
	 */
	public function chat_configuration() {
		include_once plugin_dir_path( __FILE__ ) . 'partials/minniyo-chat-admin-display.php';
	}

	/**
	 * Handle AJAX test connection request
	 *
	 * @since    1.0.0
	 */
	public function ajax_test_connection() {
		// Verify nonce.
		check_ajax_referer( 'minnch_nonce', 'nonce' );

		// Check user permissions.
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error(
				array(
					'message' => __( 'You do not have permission to perform this action.', 'minniyo-chat' ),
				)
			);
		}

		// Get API key.
		$api_key = isset( $_POST['api_key'] ) ? sanitize_text_field( wp_unslash( $_POST['api_key'] ) ) : '';

		if ( empty( $api_key ) ) {
			wp_send_json_error(
				array(
					'message' => __( 'API Key is required.', 'minniyo-chat' ),
				)
			);
		}

		// Test the connection.
		$embed_url = MINNCH_APP_EMBED_URL . $api_key;
		$response  = wp_remote_get(
			$embed_url,
			array(
				'timeout'     => 15,
				'redirection' => 5,
				'httpversion' => '1.1',
				'user-agent'  => 'WordPress/' . get_bloginfo( 'version' ) . '; ' . get_bloginfo( 'url' ),
				'headers'     => array(
					'Referer' => get_site_url(),
					'Origin'  => get_site_url(),
				),
			)
		);

		if ( is_wp_error( $response ) ) {
			wp_send_json_error(
				array(
					'message' => sprintf(
						/* translators: %s: error message */
						__( 'Connection failed: %s', 'minniyo-chat' ),
						$response->get_error_message()
					),
					'tips'    => array(
						__( 'Check your internet connection.', 'minniyo-chat' ),
						__( 'Verify your API key is correct.', 'minniyo-chat' ),
						__( 'Contact support if the issue persists.', 'minniyo-chat' ),
					),
				)
			);
		}

		$response_code = wp_remote_retrieve_response_code( $response );

		if ( 200 === $response_code ) {
			wp_send_json_success(
				array(
					'message' => __( 'Connection successful! Your chatbot is configured correctly.', 'minniyo-chat' ),
				)
			);
		} else {
			wp_send_json_error(
				array(
					'message' => sprintf(
						/* translators: %d: HTTP response code */
						__( 'Connection failed with status code: %d', 'minniyo-chat' ),
						$response_code
					),
					'tips'    => array(
						__( 'Verify your embed code is accurate.', 'minniyo-chat' ),
						__( 'Check that your domain is in the allowed domains list.', 'minniyo-chat' ),
						__( 'Ensure your API key is active in your Minniyo dashboard.', 'minniyo-chat' ),
					),
				)
			);
		}
	}

	/**
	 * Handle AJAX save settings request
	 *
	 * @since    1.0.0
	 */
	public function ajax_save_settings() {
		// Verify nonce.
		check_ajax_referer( 'minnch_nonce', 'nonce' );

		// Check user permissions.
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error(
				array(
					'message' => __( 'You do not have permission to perform this action.', 'minniyo-chat' ),
				)
			);
		}

		// Get and sanitize data.
		$api_key         = isset( $_POST['api_key'] ) ? sanitize_text_field( wp_unslash( $_POST['api_key'] ) ) : '';
		$chatbot_enabled = isset( $_POST['chatbot_enabled'] ) ? sanitize_text_field( wp_unslash( $_POST['chatbot_enabled'] ) ) : '0';

		if ( empty( $api_key ) ) {
			wp_send_json_error(
				array(
					'message' => __( 'API Key is required.', 'minniyo-chat' ),
				)
			);
		}

		// Save options.
		update_option( 'minnch_api_key', $api_key );
		update_option( 'minnch_enabled', $chatbot_enabled );

		wp_send_json_success(
			array(
				'message' => __( 'Settings saved successfully!', 'minniyo-chat' ),
			)
		);
	}
}
