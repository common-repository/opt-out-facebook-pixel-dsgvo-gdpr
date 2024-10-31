<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class FBOO_Utils {
	/**
	 * List of all options with the default value.
	 *
	 * @param bool $only_names Show only the names (keys) of the options, no defaults.
	 *
	 * @return array
	 */
	public static function get_options_list( $only_names = false ) {
		$options = array(
			'fb_plugin'          => 'manual',
			'link_deactivate'    => esc_html__( 'Disallow Facebook Pixel to track me', FBOO_TEXT_DOMAIN ),
			'link_activate'      => esc_html__( 'Allow Facebook Pixel to track me', FBOO_TEXT_DOMAIN ),
			'code'               => null,
			'popup_deactivate'   => esc_html__( 'Tracking is now disabled. Click the link again to enable it.', FBOO_TEXT_DOMAIN ),
			'popup_activate'     => esc_html__( 'Tracking is now enabled. Click the link again to disable it.', FBOO_TEXT_DOMAIN ),
			'status'             => 'off',
			'privacy_page_id'    => 0,
			'disable_monitoring' => 0,
			'force_reload'       => 0,
			'wp_privacy_page'    => 0,
		);

		if ( ! empty( $only_names ) ) {
			return array_keys( $options );
		}

		return $options;
	}

	/**
	 * Returns all options from this plugin
	 * @return array with fields (fieldname => fieldvalue)
	 */
	public static function get_options() {
		// Set defaults
		$data = self::get_options_list();

		foreach ( $data as $k => &$v ) {
			$option = get_option( FBOO_PREFIX . $k, $v );

			if ( is_string( $option ) ) {
				$option = stripslashes( $option );
			}

			$data[ $k ] = $option;
		}

		// Prefer WordPress Privacy page, is sync is enabled.
		if ( ! empty( $data['wp_privacy_page'] ) ) {
			$data['privacy_page_id'] = get_option( 'wp_page_for_privacy_policy', $data['privacy_page_id'] );
		}

		return $data;
	}

	/**
	 * Return value of a option.
	 *
	 * @param string $name   Name of the option.
	 * @param mixed $default Value to be returned, if option wasn't found. (Default: null)
	 *
	 * @return mixed Value of the option if found, otherwise the default value.
	 */
	public static function get_option( $name, $default = null ) {
		$names = self::get_options_list( true );

		if ( empty( $name ) || ! in_array( $name, $names, true ) ) {
			return $default;
		}

		$option = get_option( FBOO_PREFIX . $name, $default );

		// Prefer WordPress Privacy page, is sync is enabled.
		if ( $name == 'privacy_page_id' && ! empty( self::get_option( 'wp_privacy_page' ) ) ) {
			$option = get_option( 'wp_page_for_privacy_policy', $option );
		}

		if ( is_string( $option ) ) {
			return stripslashes( $option );
		}

		return $option;
	}

	/**
	 * Checking the status of the current website.
	 *
	 * @param array $data      Data with the plugin options.
	 * @param bool $get_labels Return array with labels. (Default: false)
	 *
	 * @return array Labeld array or an array with sum of the states.
	 */
	public static function check_todos( $data, $get_labels = false ) {
		// Check if shortcode is set on page
		if ( 0 == $data['privacy_page_id'] ) {
			$privacy_page_accessibile = $shortcode_url = $shortcode_available = null;
		} else {
			$page = get_post( $data['privacy_page_id'] );

			$page_content        = sanitize_post_field( 'post_content', $page->post_content, $page->ID, 'raw' );
			$shortcode_available = ( ! empty( $page_content ) && false !== strpos( $page_content, FBOO_SHORTCODE ) );
			$shortcode_url       = admin_url( 'post.php?action=edit&post=' . $data['privacy_page_id'] );

			$privacy_page_accessibile = ( sanitize_post_field( 'post_status', $page->post_status, $page->ID, 'raw' ) == 'publish' && empty( $page->post_password ) );
		}

		$checklist = array(
			array(
				'label'   => esc_html__( 'Opt-Out Enabled', FBOO_TEXT_DOMAIN ),
				'checked' => ( $data['status'] == 'on' || empty( $data['status'] ) ),
			),
			array(
				'label'   => esc_html__( 'Found shortcode on page', FBOO_TEXT_DOMAIN ),
				'checked' => $shortcode_available,
				'url'     => $shortcode_url,
			),
			array(
				'label'   => esc_html__( 'Page accessibile', FBOO_TEXT_DOMAIN ),
				'checked' => $privacy_page_accessibile,
				'url'     => $shortcode_url,
			),
		);

		// Remove cached value if status of todos has been updated.
		self::delete_todo_cache();

		// Return labeled array
		if ( ! empty( $get_labels ) ) {
			return $checklist;
		}

		$dontknow = $check = $todo = 0;

		foreach ( $checklist as $item ) {
			if ( false === $item['checked'] ) {
				$todo ++;
			} elseif ( true === $item['checked'] ) {
				$check ++;
			} else {
				$dontknow ++;
			}
		}

		return array(
			'sum'      => count( $checklist ),
			'dontknow' => $dontknow,
			'check'    => $check,
			'todo'     => $todo,
		);
	}

	/**
	 * Delete the checklist cache
	 *
	 * @return bool True on success, otherwise false
	 */
	public static function delete_todo_cache() {
		return delete_transient( FBOO_PREFIX . 'has_todos' );
	}

	/**
	 * Check if there are open todos.
	 *
	 * @return bool True if open todos available, otherwise false.
	 */
	public static function has_todos() {
		$transient_name = FBOO_PREFIX . 'has_todos';
		$has_todos      = get_transient( $transient_name );

		if ( false !== $has_todos ) {
			return boolval( $has_todos );
		}

		$data  = self::get_options();
		$todos = self::check_todos( $data );

		if ( empty( $todos ) ) {
			return;
		}

		$has_todos = ( ( $todos['sum'] - $todos['dontknow'] ) == $todos['check'] ? 0 : 1 );

		delete_transient( $transient_name );
		set_transient( $transient_name, $has_todos, 7 * DAY_IN_SECONDS );

		return boolval( $has_todos );
	}
}