<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class FBOO_Public {
	private $form_data;

	/**
	 * FBOO_Public constructor.
	 */
	public function __construct() {
		add_action( 'wp_head', array( $this, 'head_script' ), - 1 );
		add_action( 'wp_print_scripts', array( $this, 'dequeue_script' ), 100 );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		add_shortcode( 'fb_optout', array( $this, 'shortcode' ) );

		if ( has_filter( 'widget_text', 'do_shortcode' ) !== false ) {
			add_filter( 'widget_text', 'do_shortcode' );
		}

		$this->form_data = FBOO_Utils::get_options();
	}

	/**
	 * Enqueue scripts and styles for the public pages.
	 */
	public function enqueue_scripts() {
		$min = SCRIPT_DEBUG ? '' : '.min';

		wp_register_script( 'fboo-public', FBOO_PLUGIN_URL . '/assets/public' . $min . '.js', array(), false, true );
	}

	/**
	 * Dequeue scripts to hide FB Pixel code.
	 */
	public function dequeue_script() {
		if ( ! $this->isOptOut() ) {
			return;
		}

		switch ( $this->form_data['fb_plugin'] ) {
			case 'pixelyoursite':
				wp_dequeue_script( 'pys-public' );
				wp_dequeue_script( 'fb_pixel_pro' );

				break;
		}
	}

	/**
	 * Handle the shortcode.
	 *
	 * @return string HTML code or empty string on error.
	 */
	public function shortcode() {

		// Disable shortcode if status is deactivated or set to manual without code.
		if ( $this->form_data['status'] == 'off' ) {
			return '';
		}

		$current_status = isset( $_COOKIE['fb-opt-out'] ) && $_COOKIE['fb-opt-out'] == true ? 'activate' : 'deactivate';
		$json_data      = array(
			'link_deactivate' => apply_filters( 'fboo_link_deactivate_text', esc_js( $this->form_data['link_deactivate'] ) ),
			'link_activate'   => apply_filters( 'fboo_link_activate_text', esc_js( $this->form_data['link_activate'] ) ),
			'force_reload'    => apply_filters( 'fboo_force_reload', boolval( $this->form_data['force_reload'] ) ),
			'disable_string'  => 'fb-opt-out',
		);

		if ( ! empty( $this->form_data['popup_activate'] ) ) {
			$json_data['popup_activate'] = apply_filters( 'fboo_popup_activate_text', esc_js( $this->form_data['popup_activate'] ) );
		}

		if ( ! empty( $this->form_data['popup_deactivate'] ) ) {
			$json_data['popup_deactivate'] = apply_filters( 'fboo_popup_deactivate_text', esc_js( $this->form_data['popup_deactivate'] ) );
		}

		wp_localize_script( 'fboo-public', 'fboo_data', $json_data );
		wp_enqueue_script( 'fboo-public' );

		do_action( 'fboo_before_shortcode', $current_status );

		$html = '<a href="javascript:fboo_handle_optout();" id="fboo-link" class="fboo-link-' . esc_attr( $current_status ) . '">' . esc_html( $this->form_data[ "link_" . $current_status ] ) . '</a>';

		do_action( 'fboo_after_shortcode', $current_status );

		return $html;
	}

	/**
	 * Adds the FB Pixel Code (manual mode) code to the header.
	 */
	public function head_script() {
		if ( $this->isOptOut() ) {
			$this->remove_action();

			return;
		}

		$code = html_entity_decode( $this->form_data['code'], ENT_QUOTES );

		if ( $this->form_data['fb_plugin'] != 'manual' || empty( $this->form_data['code'] ) ) {
			return;
		}

		do_action( 'fboo_before_head_script', $code );

		echo $code;

		do_action( 'fboo_after_head_script', $code );
	}

	/**
	 * Remove some WP actions, to hide the FB Pixel code.
	 */
	public function remove_action() {
		switch ( $this->form_data['fb_plugin'] ) {
			case 'pixelcat':
				remove_action( 'wp_head', 'fca_pc_maybe_add_pixel', 1 );
				break;

			case 'pixelcaffeine':
				remove_action( 'wp_head', array( 'AEPC_Pixel_Scripts', 'pixel_init' ), 99 );
				remove_action( 'wp_footer', array( 'AEPC_Pixel_Scripts', 'pixel_init' ), 1 );
				break;

			case 'fbp4wp':
				remove_action( 'wp_head', 'fbp4wp_write_fb_pixel', 10 );
				break;
		}
	}

	/**
	 * Check if opt-out is set.
	 *
	 * @return bool True is user has opt-out, otherwise false.
	 */
	private function isOptOut() {
		return ( $this->form_data['status'] == 'off' || empty( $this->form_data['fb_plugin'] ) || ( isset( $_COOKIE['fb-opt-out'] ) && $_COOKIE['fb-opt-out'] == true ) );
	}

}