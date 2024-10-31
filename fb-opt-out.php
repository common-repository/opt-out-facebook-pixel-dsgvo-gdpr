<?php
/*
 * Plugin Name: Opt-Out Facebook Pixel (DSGVO / GDPR)
 * Plugin URI: https://schweizersolutions.com
 * Description: Adds the possibility for the user to opt out from Facebook Pixel. The user will not be tracked by Facebook on this site until the user allows it again, clears cookies or uses a different browser.
 * Version: 1.1
 * Author: Schweizer Solutions GmbH
 * Author URI: https://schweizersolutions.com
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: fb-opt-out
 * Domain Path: /languages
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Define global paths
if ( ! defined( 'FBOO_PLUGIN_NAME' ) ) {
	define( 'FBOO_PLUGIN_NAME', trim( dirname( plugin_basename( __FILE__ ) ), '/' ) );
}

if ( ! defined( 'FBOO_PLUGIN_DIR' ) ) {
	define( 'FBOO_PLUGIN_DIR', WP_PLUGIN_DIR . '/' . FBOO_PLUGIN_NAME );
}

if ( ! defined( 'FBOO_PLUGIN_URL' ) ) {
	define( 'FBOO_PLUGIN_URL', WP_PLUGIN_URL . '/' . FBOO_PLUGIN_NAME );
}

if ( ! defined( 'FBOO_PREFIX' ) ) {
	define( 'FBOO_PREFIX', '_fboo_' );
}

if ( ! defined( 'FBOO_VERSION_KEY' ) ) {
	define( 'FBOO_VERSION_KEY', FBOO_PREFIX . 'version' );
}

if ( ! defined( 'FBOO_TEXT_DOMAIN' ) ) {
	define( 'FBOO_TEXT_DOMAIN', 'fb-opt-out' );
}

if ( ! defined( 'FBOO_LOCALE' ) ) {
	define( 'FBOO_LOCALE', apply_filters( 'plugin_locale', get_locale(), FBOO_TEXT_DOMAIN ) );
}

if ( ! defined( 'FBOO_SHORTCODE' ) ) {
	define( 'FBOO_SHORTCODE', '[fb_optout]' );
}

if ( ! defined( 'FBOO_CAPABILITY' ) ) {
	define( 'FBOO_CAPABILITY', 'manage_options' );
}


Class FBOO {
	/*
	 * Handling the start of the plugin
	 */
	public function init() {
		$this->load_dependencies();
		$this->run();
	}

	/**
	 * Runs initialisation of the plugin
	 */
	public function run() {
		// Load translations
		load_textdomain( FBOO_TEXT_DOMAIN, WP_LANG_DIR . '/' . FBOO_TEXT_DOMAIN . '-' . FBOO_LOCALE . '.mo' );
		load_plugin_textdomain( FBOO_TEXT_DOMAIN, false, FBOO_PLUGIN_NAME . '/languages' );

		// Starts Classes
		if ( is_admin() ) {
			new FBOO_Admin();
		} else {
			new FBOO_Public();
		}
	}

	/**
	 * Load all classes.
	 */
	public function load_dependencies() {
		require_once FBOO_PLUGIN_DIR . '/inc/singleton.class.php';
		require_once FBOO_PLUGIN_DIR . '/inc/messages.class.php';
		require_once FBOO_PLUGIN_DIR . '/inc/utils.class.php';
		include_once FBOO_PLUGIN_DIR . '/inc/admin.class.php';
		include_once FBOO_PLUGIN_DIR . '/inc/public.class.php';
	}

	/**
	 * Redirect to setting page, if plugin got activated.
	 *
	 * @param string $plugin Activated plugin
	 */
	public function activated_plugin( $plugin ) {
		if ( $plugin == plugin_basename( __FILE__ ) ) {
			exit( wp_redirect( esc_url( admin_url( 'options-general.php?page=fboo' ) ) ) );
		}
	}
}

// Start the plugin.
$fboo = new FBOO();

add_action( 'init', array( $fboo, 'init' ) );
add_action( 'activated_plugin', array( $fboo, 'activated_plugin' ) );

function fboo_log( $data, $id = 0 ) {
	$file   = FBOO_PLUGIN_DIR . "/log.txt";
	$string = '##### ' . $id . ' - ' . date( 'd.m.Y H:i:s' ) . ' ####' . PHP_EOL . var_export( $data, true ) . PHP_EOL . PHP_EOL;

	return ( file_put_contents( $file, $string, FILE_APPEND ) !== false );
}