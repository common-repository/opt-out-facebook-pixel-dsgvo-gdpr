<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class FBOO_Admin {
	private $messages;

	/**
	 * FBOO_Admin constructor.
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'menu' ) );
		add_action( 'admin_notices', array( $this, 'admin_notices' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'admin_head', array( $this, 'admin_head' ) );
		add_action( 'save_post_page', array( $this, 'save_page' ), 10, 1 );

		add_filter( 'plugin_action_links', array( $this, 'plugin_action_links' ), 10, 2 );

		$this->messages = FBOO_Messages::getInstance();
	}

	/**
	 * Clear the checklist cache after the page saved.
	 *
	 * @param int $post_id ID of the post
	 */
	public function save_page( $post_id ) {
		// run only if this page is configured for the monitoring
		if ( $post_id != FBOO_Utils::get_option( 'privacy_page_id' ) || $post_id != FBOO_Utils::get_option( 'wp_privacy_page' ) ) {
			return;
		}

		FBOO_Utils::delete_todo_cache();
	}

	/**
	 * Load TinyMCE filters and hooks
	 */
	public function admin_head() {
		global $post;

		// Check if it is the WP Privacy page and user has access to it
		if ( ! current_user_can( 'edit_pages' ) || empty( $post ) || $post->ID != get_option( 'wp_page_for_privacy_policy', 0 ) || get_user_option( 'rich_editing' ) != 'true' ) {
			return;
		}

		add_filter( "mce_external_plugins", array( $this, 'add_tinymce_plugin' ) );
		add_filter( 'mce_buttons', array( $this, 'register_tinymce_button' ) );
	}

	/**
	 * Add this plugin to TinyMCE.
	 *
	 * @param array $plugins List with plugin URLs
	 *
	 * @return array List with plugin URLs
	 */
	public function add_tinymce_plugin( $plugins ) {
		$plugins['fboptout'] = FBOO_PLUGIN_URL . '/assets/tinymce.js';

		return $plugins;
	}

	/**
	 * Add buttons to TinyMCE.
	 *
	 * @param array $buttons List of buttons
	 *
	 * @return array List of buttons
	 */
	public function register_tinymce_button( $buttons ) {
		array_push( $buttons, "fboptout" );

		return $buttons;
	}

	/**
	 * Add menu items to the admin menu
	 */
	public function menu() {
		add_options_page( esc_html__( 'Facebook Pixel Opt-Out', FBOO_TEXT_DOMAIN ), 'FB Opt-Out', FBOO_CAPABILITY, 'fboo', array( $this, 'menu_settings' ) );
	}

	/**
	 * Customize the action links.
	 *
	 * @param array $links Actions links.
	 * @param string $file Filename of the activated plugin.
	 *
	 * @return array Action links.
	 */
	public function plugin_action_links( $links, $file ) {
		$this_plugin = FBOO_PLUGIN_NAME . '/fb-opt-out.php';

		if ( $file == $this_plugin ) {
			$settings_link = '<a href="' . esc_url( admin_url( 'options-general.php?page=fboo' ) ) . '">' . esc_html__( 'Settings' ) . '</a>';
			array_unshift( $links, $settings_link );
		}

		return $links;
	}

	/**
	 * Enqueue scripts and styles for the admin pages.
	 */
	public function enqueue_scripts() {
		// Run only if functionallity on the right pages
		if ( isset( $_GET['page'] ) && $_GET['page'] != 'fboo' ) {
			return;
		}

		wp_enqueue_style( 'fboo-admin-styles', FBOO_PLUGIN_URL . '/assets/admin.css' );

		wp_enqueue_script( 'fboo-admin-script', FBOO_PLUGIN_URL . '/assets/admin.js', array( 'jquery' ), false, true );

		wp_localize_script( 'fboo-admin-script', 'fboo', array(
			'text' => array(
				'copied'    => esc_html__( 'Copied!', FBOO_TEXT_DOMAIN ),
				'notcopied' => esc_html__( "Couldn't copy!", FBOO_TEXT_DOMAIN ),
			),
		) );
	}

	/**
	 * Add admin notices, if function is enabled and no UA code is configured.
	 */
	public function admin_notices() {
		// Run only if open todos available.
		if ( ! current_user_can( FBOO_CAPABILITY ) || FBOO_Utils::get_option( 'disable_monitoring', false ) || ! FBOO_Utils::has_todos() || ( isset( $_GET['page'] ) && $_GET['page'] == 'fboo' ) ) {
			return;
		}

		echo '<div class="notice notice-error is-dismissable"><p>' . sprintf( __( 'Facebook Pixel does not appear to function in compliance with data protection regulations. Please check the settings <a href="%s">here</a>.', FBOO_TEXT_DOMAIN ), esc_url( admin_url( 'options-general.php?page=fboo' ) ) ) . '</p></div>';
	}

	/**
	 * Show the settings page.
	 */
	public function menu_settings() {
		// Handle form save
		if ( isset( $_POST['fboo'] ) ) {
			$form_data = $this->save_menu_settings();
		} else {
			$form_data = FBOO_Utils::get_options();
		}

		$fb_plugins = array(
			'pixelyoursite' => array(
				'label'        => esc_html__( 'PixelYourSite', FBOO_TEXT_DOMAIN ),
				'url'          => admin_url( 'plugin-install.php?tab=search&s=PixelYourSite' ),
				'is_active'    => ( is_plugin_active( 'pixelyoursite/facebook-pixel-master.php' ) || is_plugin_active( 'pixelyoursite-pro/pixelyoursite-pro.php' ) ),
				'is_installed' => ( file_exists( WP_PLUGIN_DIR . '/pixelyoursite/facebook-pixel-master.php' ) || file_exists( WP_PLUGIN_DIR . '/pixelyoursite-pro/pixelyoursite-pro.php' ) ),
			),
			'pixelcat'      => array(
				'label'        => esc_html__( 'Pixel Cat', FBOO_TEXT_DOMAIN ),
				'url'          => admin_url( 'plugin-install.php?tab=search&s=Pixel+Cat' ),
				'is_active'    => is_plugin_active( 'facebook-conversion-pixel/facebook-conversion-pixel.php' ),
				'is_installed' => file_exists( WP_PLUGIN_DIR . '/facebook-conversion-pixel/facebook-conversion-pixel.php' ),
			),
			'pixelcaffeine' => array(
				'label'        => esc_html__( 'Pixel Caffeine', FBOO_TEXT_DOMAIN ),
				'url'          => admin_url( 'plugin-install.php?tab=search&s=Pixel+Caffeine' ),
				'is_active'    => is_plugin_active( 'pixel-caffeine/pixel-caffeine.php' ),
				'is_installed' => file_exists( WP_PLUGIN_DIR . '/pixel-caffeine/pixel-caffeine.php' ),
			),
			'fbp4wp'        => array(
				'label'        => esc_html__( 'Facebook Pixel for WP', FBOO_TEXT_DOMAIN ),
				'url'          => admin_url( 'plugin-install.php?tab=search&s=Facebook+Pixel+for+WP' ),
				'is_active'    => is_plugin_active( 'ns-facebook-pixel-for-wp/ns-facebook-pixel-for-wp.php' ),
				'is_installed' => file_exists( WP_PLUGIN_DIR . '/ns-facebook-pixel-for-wp/ns-facebook-pixel-for-wp.php' ),
			),
			'gtm'           => array(
				'label' => esc_html__( 'Google Tag Manager', FBOO_TEXT_DOMAIN ),
			),
		);

		extract( $form_data );

		// if plugin set as current but not installed or activated, remove it from settings
		if ( ! empty( $fb_plugin ) && isset( $fb_plugins[ $fb_plugin ] ) && ( ( isset( $fb_plugins[ $fb_plugin ]['is_active'] ) && ! $fb_plugins[ $fb_plugin ]['is_active'] ) || isset( $fb_plugins[ $fb_plugin ]['is_installed'] ) && ! $fb_plugins[ $fb_plugin ]['is_installed'] ) ) {
			delete_option( FBOO_PREFIX . 'fb_plugin' );
			$fb_plugin = null;
		}

		$checklist          = FBOO_Utils::check_todos( $form_data, true );
		$wp_privacy_page_id = get_option( 'wp_page_for_privacy_policy', 0 );

		include_once FBOO_PLUGIN_DIR . '/templates/settings.php';
	}

	/**
	 * Handle the submited form data.
	 *
	 * @return array Options with its value.
	 */
	private function save_menu_settings() {

		// Check if form submited by the right site
		if ( ! isset( $_REQUEST['_wpnonce'] ) || ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'fboo-settings' ) ) {
			$this->messages->addError( esc_html__( 'Security check fail!', FBOO_TEXT_DOMAIN ) );

			return array();
		}

		// Validate inputs
		$data = filter_var_array( $_POST['fboo'], array(
			'fb_plugin'          => FILTER_SANITIZE_STRING,
			'link_deactivate'    => FILTER_SANITIZE_STRING,
			'link_activate'      => FILTER_SANITIZE_STRING,
			'code'               => FILTER_SANITIZE_SPECIAL_CHARS,
			'popup_deactivate'   => FILTER_SANITIZE_STRING,
			'popup_activate'     => FILTER_SANITIZE_STRING,
			'status'             => FILTER_SANITIZE_STRING,
			'privacy_page_id'    => FILTER_SANITIZE_STRING,
			'disable_monitoring' => FILTER_SANITIZE_NUMBER_INT,
			'force_reload'       => FILTER_SANITIZE_NUMBER_INT,
			'wp_privacy_page'    => FILTER_SANITIZE_NUMBER_INT,
		), false );

		// Removes space from start and end of the text.
		$data['link_activate']    = trim( $data['link_activate'] );
		$data['link_deactivate']  = trim( $data['link_deactivate'] );
		$data['popup_activate']   = trim( $data['popup_activate'] );
		$data['popup_deactivate'] = trim( $data['popup_deactivate'] );

		if ( empty( $data['link_deactivate'] ) ) {
			$this->messages->addError( esc_html__( "Please enter the text for the deactivate link, otherwise link doesn't appears!", FBOO_TEXT_DOMAIN ) );
		}

		if ( empty( $data['link_activate'] ) ) {
			$this->messages->addError( esc_html__( "Please enter the text for the activate link, otherwise link doesn't appears!", FBOO_TEXT_DOMAIN ) );
		}

		if ( $this->messages->hasError() ) {
			return $data;
		}

		// If an option is not set, fill it with the default value.
		$data = array_merge( FBOO_Utils::get_options_list(), $data );

		// Sync. with WP Privacy page
		if ( ! empty( $data['wp_privacy_page'] ) ) {
			$data['privacy_page_id'] = get_option( 'wp_page_for_privacy_policy', 0 );
		}

		foreach ( $data as $k => $v ) {
			update_option( FBOO_PREFIX . $k, $v );
		}

		$this->messages->addSuccess( esc_html__( 'Settings saved.', FBOO_TEXT_DOMAIN ) );

		return $data;
	}
}