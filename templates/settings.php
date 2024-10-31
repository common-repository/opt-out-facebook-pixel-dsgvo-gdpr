<div class="wrap" id="fboo">
    <h2><?php esc_html_e( 'Facebook Pixel Opt-Out', FBOO_TEXT_DOMAIN ); ?></h2>

	<?php $this->messages->render( true ); ?>

    <p>
		<?php printf( esc_html__( "Use this shortcode on every page or post you want, to display the FB Opt Out: %s", FBOO_TEXT_DOMAIN ), '<code title="' . esc_attr__( 'Click to copy the shortcode!', FBOO_TEXT_DOMAIN ) . '">' . esc_html( FBOO_SHORTCODE ) . '</code>' ); ?> <span class="fboo-clipboard dashicons dashicons-admin-page" title="<?php esc_attr_e( 'Click to copy the shortcode!', FBOO_TEXT_DOMAIN ); ?>" data-copy="<?php echo esc_attr( FBOO_SHORTCODE ); ?>"></span>
    </p>

	<?php
	$_check   = '';
	$checked  = 0;
	$dontknow = 0;

	foreach ( $checklist as $check ):

		if ( ! empty( $check['url'] ) ) {
			$check['label'] = '<a title="' . esc_html__( "Got to page", FBOO_TEXT_DOMAIN ) . '" href="' . esc_url( $check['url'] ) . '">' . $check['label'] . '<span class="dashicons dashicons-external"></span></a>';
		}

		$_check .= sprintf( '<li class="%s">%s</li>', ( false !== $check['checked'] ? ( is_null( $check['checked'] ) ? 'dontknow' : 'check' ) : '' ), $check['label'] );

		if ( ! empty( $check['checked'] ) ) {
			$checked ++;
		} elseif ( is_null( $check['checked'] ) ) {
			$dontknow ++;
		}
	endforeach;

	printf( '<div id="fboo-checklist" class="%s"><h2>%s</h2><ul class="fboo-check">%s</ul></div>', ( ( count( $checklist ) - $dontknow ) == $checked ? ( $dontknow == 0 ? 'done' : 'dontknow' ) : 'todo' ), esc_html__( 'Current status of this website', FBOO_TEXT_DOMAIN ), $_check );
	?>


    <form method="post">
        <table class="form-table">
            <tbody>
            <tr>
                <th scope="row">
                    <label><?php esc_html_e( "Status", FBOO_TEXT_DOMAIN ); ?></label>
                </th>
                <td>
                    <label for="fboo-status">
                        <input type="checkbox" name="fboo[status]" id="fboo-status" value="on" <?php checked( true, ( $status == 'on' || empty( $status ) ) ); ?> />
						<?php esc_html_e( "Enable Opt-Out function", FBOO_TEXT_DOMAIN ); ?>
                    </label>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label><?php esc_html_e( "Code", FBOO_TEXT_DOMAIN ); ?></label>
                </th>
                <td>
                    <fieldset>
						<?php foreach ( $fb_plugins as $key => &$info ): ?>
                            <label for="fb-plugin-<?php echo $key; ?>">
                                <input type="radio" name="fboo[fb_plugin]" id="fb-plugin-<?php echo esc_attr( $key ); ?>" value="<?php echo esc_attr( $key ); ?>" <?php echo ( ! isset( $info['is_active'] ) || $info['is_active'] ) ? '' : 'disabled="disabled"'; ?> <?php checked( $key, $fb_plugin ); ?> />
								<?php echo esc_html( $info['label'] ); ?>

								<?php
								$link_text = null;

								if ( isset( $info['is_active'] ) && ! $info['is_active'] ):
									$link_text = esc_html__( 'Not activated', FBOO_TEXT_DOMAIN );
									$url       = esc_url( admin_url( 'plugins.php?plugin_status=inactive' ) );
								endif;

								if ( isset( $info['is_active'] ) && ! $info['is_installed'] ):
									$link_text = esc_html__( 'Not installed', FBOO_TEXT_DOMAIN );
									$url       = esc_url( $info['url'] );
								endif;
								?>

								<?php if ( ! empty( $link_text ) ): ?>
                                    <small>(<a href="<?php echo $url; ?>" target="<?php echo isset( $info['target'] ) ? esc_attr( $info['target'] ) : '_self'; ?>"><?php echo $link_text; ?></a>)</small>
								<?php endif; ?>
                            </label>
                            <br/>
						<?php endforeach; ?>

                        <label for="fb-plugin-manual">
                            <input type="radio" name="fboo[fb_plugin]" id="fb-plugin-manual" value="manual" <?php checked( true, ( $fb_plugin == 'manual' || empty( $fb_plugin ) ) ); ?> />
                            <textarea id="fboo-code" name="fboo[code]" placeholder="<!-- Facebook Pixel Code --> ... <!-- End Facebook Pixel Code -->" cols="49" rows="9"><?php echo $code; ?></textarea>
                        </label>
                    </fieldset>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label><?php esc_html_e( "Status check", FBOO_TEXT_DOMAIN ); ?></label>
                </th>
                <td>
                    <label for="fboo-disable-monitoring"><input type="checkbox" name="fboo[disable_monitoring]" value="1" id="fboo-disable-monitoring" <?php checked( $disable_monitoring, 1 ); ?>> <?php esc_html_e( "Suppress the message in the dashboard if the settings are not data protection compliant.", FBOO_TEXT_DOMAIN ); ?></label>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label><?php esc_html_e( "Force reload", FBOO_TEXT_DOMAIN ); ?></label>
                </th>
                <td>
                    <label for="fboo-force-reload"><input type="checkbox" name="fboo[force_reload]" value="1" id="fboo-force-reload" <?php checked( $force_reload, 1 ); ?>> <?php esc_html_e( "Force page reload after the click on the link.", FBOO_TEXT_DOMAIN ); ?></label>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="fboo-privacy-page"><?php esc_html_e( "Privacy Policy Page", FBOO_TEXT_DOMAIN ); ?></label>
                </th>
                <td>
					<?php wp_dropdown_pages( array(
						'selected'          => empty( $privacy_page_id ) ? 0 : $privacy_page_id,
						'id'                => 'fboo-privacy-page',
						'name'              => 'fboo[privacy_page_id]',
						'show_option_none'  => esc_html__( "— Select —", FBOO_TEXT_DOMAIN ),
						'option_none_value' => 0,
						'post_status'       => 'publish,draft',
					) ); ?>
					<?php if ( ! empty( $privacy_page_id ) ): ?>
                        <a href="<?php echo esc_url( admin_url( 'post.php?action=edit&post=' . $privacy_page_id ) ); ?>" class="dashicons dashicons-welcome-write-blog" title="<?php esc_html_e( 'Edit page', FBOO_TEXT_DOMAIN ); ?>"></a>
					<?php endif; ?>
                    <p>
                        <small><?php esc_html_e( "Select the page, where you will use this shortcode, for the monitoring.", FBOO_TEXT_DOMAIN ); ?></small>
                    </p>

					<?php if ( version_compare( PHP_VERSION, '4.9.6', '>=' ) ): ?>
                        <p>
                            <label for="fboo-wp-privacy-page"><input type="checkbox" name="fboo[wp_privacy_page]" value="1" id="fboo-wp-privacy-page" data-id="<?php echo esc_attr( $wp_privacy_page_id ); ?>" <?php checked( $wp_privacy_page, 1 ); ?>> <?php esc_html_e( "Synchronize with WordPress Privacy Policy page.", FBOO_TEXT_DOMAIN ); ?></label>
                        </p>
					<?php endif; ?>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="fboo-link-deactivate"><?php esc_html_e( "Text of link for deactivate", FBOO_TEXT_DOMAIN ); ?></label>
                </th>
                <td>
                    <input type="text" id="fboo-link-deactivate" name="fboo[link_deactivate]" class="regular-text" value="<?php echo esc_attr( $link_deactivate ); ?>"/>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="fboo-popup-deactivate"><?php esc_html_e( "Popup Text for deactivate", FBOO_TEXT_DOMAIN ); ?>
                    </label>
                </th>
                <td>
                    <input type="text" id="fboo-popup-deactivate" name="fboo[popup_deactivate]" class="regular-text" value="<?php echo esc_attr( $popup_deactivate ); ?>"/> <span class="fboo-empty-popup <?php echo empty( $popup_deactivate ) ? 'empty' : ''; ?>" title="<?php esc_attr_e( 'Click to empty field, to disable the popup.', FBOO_TEXT_DOMAIN ); ?>">&#10006;</span>
                    <p>
                        <small><?php esc_html_e( "Leave empty to disable popup", FBOO_TEXT_DOMAIN ); ?></small>
                    </p>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="fboo-link-activate"><?php esc_html_e( "Text of link for activate", FBOO_TEXT_DOMAIN ); ?></label>
                </th>
                <td>
                    <input type="text" id="fboo-link-activate" name="fboo[link_activate]" class="regular-text" value="<?php echo esc_attr( $link_activate ); ?>"/>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="fboo-popup-activate"><?php esc_html_e( "Popup Text for activate", FBOO_TEXT_DOMAIN ); ?></label>
                </th>
                <td>
                    <input type="text" id="fboo-popup-activate" name="fboo[popup_activate]" class="regular-text" value="<?php echo esc_attr( $popup_activate ); ?>"/> <span class="fboo-empty-popup <?php echo empty( $popup_activate ) ? 'empty' : ''; ?>" title="<?php esc_attr_e( 'Click to empty field, to disable the popup.', FBOO_TEXT_DOMAIN ); ?>">&#10006;</span>
                    <p>
                        <small><?php esc_html_e( "Leave empty to disable popup", FBOO_TEXT_DOMAIN ); ?></small>
                    </p>
                </td>
            </tr>

            </tbody>
        </table>

		<?php wp_nonce_field( 'fboo-settings' ); ?>

        <p class="submit">
            <input type="submit" name="submit" id="submit" class="button button-primary" value="<?php esc_html_e( "Save Changes" ); ?>">
        </p>
    </form>

	<?php if ( ! file_exists( WP_PLUGIN_DIR . '/opt-out-for-google-analytics/ga-opt-out.php' ) ): ?>
        <h3><?php esc_html_e( 'Do you know our Opt-Out for Google Analytics? Click on the banner for more information.', FBOO_TEXT_DOMAIN ); ?></h3>
        <a class="fboo-promotion" href="https://wordpress.org/plugins/opt-out-for-google-analytics/" target="_blank">
            <img src="https://ps.w.org/opt-out-for-google-analytics/assets/banner-772x250.png" alt="Opt-Out Google Analytics">
        </a>
	<?php endif; ?>
</div>