<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://http://minniyo.com
 * @since      1.0.0
 *
 * @package    Minniyo_Chat
 * @subpackage Minniyo_Chat/public
 */

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
	 * Render the chatbot widget in footer
	 *
	 * @since    1.0.0
	 */
	public function render_chatbot_widget() {
		// Check if chatbot is enabled.
		$chatbot_enabled = get_option( 'minniyo_chat_enabled', '0' );

		if ( '1' !== $chatbot_enabled ) {
			return;
		}

		// Get API key.
		$api_key = get_option( 'minniyo_chat_api_key', '' );

		if ( empty( $api_key ) ) {
			return;
		}

		// Output the chatbot embed script.
		$embed_url = MINNIYO_CHAT_APP_EMBED_URL . esc_attr( $api_key );
		?>
		<!--Start of Minniyo Script-->
		<script type="text/javascript">
		var Minniyo_API=Minniyo_API||{}, Minniyo_LoadStart=new Date();
		(function(){
			var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
			s1.async=true;
			s1.src='<?php echo esc_url( $embed_url ); ?>';
			s1.charset='UTF-8';
			s1.setAttribute('crossorigin','*');
			s0.parentNode.insertBefore(s1,s0);
		})();
		</script>
		<!--End of Minniyo Script-->
		<?php
	}
}
