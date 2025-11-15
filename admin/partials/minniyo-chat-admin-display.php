<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://minniyo.com
 * @since      1.0.0
 *
 * @package    Minniyo_Chat
 * @subpackage Minniyo_Chat/admin/partials
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Get saved options.
$minnch_api_key         = get_option( 'minnch_api_key', '' );
$minnch_chatbot_enabled = get_option( 'minnch_enabled', '0' );

?>

<div class="minniyo-chat-wrapper">
	<div class="minniyo-chat-header">
		<div class="minniyo-chat-logo">
			<img height="60" src="<?php echo esc_url( MINNCH_PLUGIN_URL . '/admin/images/minniyo-logo.png' ); ?>" alt="Minniyo Logo">
		</div>
		<div class="minniyo-chat-branding">
			<h1>Minniyo</h1>
			<p class="minniyo-tagline">Capture, Qualify & Convert Leads Automatically with Minniyo’s All-in-One System</p>
		</div>
	</div>

	<div class="minniyo-chat-container">
		<div class="minniyo-chat-card">
			<div class="minniyo-card-header">
				<h2><?php esc_html_e( 'Chatbot Configuration', 'minniyo-chat' ); ?></h2>
				<p class="description"><?php esc_html_e( 'Configure your Minniyo chatbot to start engaging with your visitors.', 'minniyo-chat' ); ?></p>
			</div>

			<div class="minniyo-card-body">
				<!-- Status Messages -->
				<div id="minniyo-message" class="minniyo-message" style="display: none;"></div>

				<!-- Enable/Disable Toggle -->
				<div class="minniyo-form-group minniyo-toggle-section">
					<div class="minniyo-toggle-container">
						<label class="minniyo-toggle">
							<input type="checkbox" id="minnch_enabled" name="minnch_enabled" value="1" <?php checked( $minnch_chatbot_enabled, '1' ); ?>>
							<span class="minniyo-toggle-slider"></span>
						</label>
						<div class="minniyo-toggle-label">
							<strong><?php esc_html_e( 'Enable Chatbot', 'minniyo-chat' ); ?></strong>
							<p class="description"><?php esc_html_e( 'Turn on to display the chatbot on your website.', 'minniyo-chat' ); ?></p>
						</div>
					</div>
				</div>

				<!-- API Key Input -->
				<div class="minniyo-form-group">
					<label for="minnch_api_key">
						<strong><?php esc_html_e( 'Integration API Key', 'minniyo-chat' ); ?></strong>
						<span class="required">*</span>
					</label>
					<p class="description">
						<?php
						printf(
							/* translators: %s: URL to setup page */
							esc_html__( 'Get your API key from %s', 'minniyo-chat' ),
							'<a href="' . esc_url( MINNCH_APP_URL . '/widget' ) . '" target="_blank" rel="noopener noreferrer">Minniyo Dashboard <span class="dashicons dashicons-external"></span></a>'
						);
						?>
					</p>
					<input 
						type="text" 
						id="minnch_api_key" 
						name="minnch_api_key" 
						class="minniyo-input" 
						placeholder="<?php esc_attr_e( 'e7257249b770d1d1388*************************', 'minniyo-chat' ); ?>"
						value="<?php echo esc_attr( $minnch_api_key ); ?>"
					/>
				</div>

				<!-- Action Buttons -->
				<div class="minniyo-form-actions">
					<button type="button" id="minniyo-test-connection" class="button button-secondary">
						<span class="dashicons dashicons-update-alt"></span>
						<?php esc_html_e( 'Test Connection', 'minniyo-chat' ); ?>
					</button>
					<button type="button" id="minniyo-save-settings" class="button button-primary">
						<span class="dashicons dashicons-yes" data-loading="false"></span>
						<span class="dashicons dashicons-admin-generic" data-loading="true" style="display: none;"></span>
						<?php esc_html_e( 'Save Settings', 'minniyo-chat' ); ?>
					</button>
				</div>
			</div>
		</div>

		<!-- Help Section -->
		<div class="minniyo-chat-card minniyo-help-card">
			<div class="minniyo-card-header">
				<h2><?php esc_html_e( 'Need Help?', 'minniyo-chat' ); ?></h2>
			</div>
			<div class="minniyo-card-body">
				<div class="minniyo-help-item">
					<span class="dashicons dashicons-admin-tools"></span>
					<div>
						<strong><?php esc_html_e( 'Setup Guide', 'minniyo-chat' ); ?></strong>
						<p><?php esc_html_e( 'Visit our documentation for step-by-step installation instructions.', 'minniyo-chat' ); ?></p>
					</div>
				</div>
				<div class="minniyo-help-item">
					<span class="dashicons dashicons-sos"></span>
					<div>
						<strong><?php esc_html_e( 'Troubleshooting', 'minniyo-chat' ); ?></strong>
						<p><?php esc_html_e( 'If connection fails, check:', 'minniyo-chat' ); ?></p>
						<ul class="minniyo-troubleshooting-list">
							<li><?php esc_html_e( 'Verify your embed code is accurate', 'minniyo-chat' ); ?></li>
							<li><?php esc_html_e( 'Confirm your domain is in the allowed domains list', 'minniyo-chat' ); ?></li>
							<li><?php esc_html_e( 'Ensure your API key is active', 'minniyo-chat' ); ?></li>
						</ul>
					</div>
				</div>
				<div class="minniyo-help-item">
					<span class="dashicons dashicons-email"></span>
					<div>
						<strong><?php esc_html_e( 'Support', 'minniyo-chat' ); ?></strong>
						<p>
							<?php
							printf(
								/* translators: %s: support email */
								esc_html__( 'Contact us at %s for assistance.', 'minniyo-chat' ),
								'<a href="mailto:' . esc_attr( MINNCH_SUPPORT_EMAIL ) . '">' . esc_attr( MINNCH_SUPPORT_EMAIL ) . '</a>'
							);
							?>
						</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
