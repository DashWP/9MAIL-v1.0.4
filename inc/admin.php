<?php

namespace EmTmpl\Inc;

defined( 'ABSPATH' ) || exit;

class Admin {

	protected static $instance = null;

	private function __construct() {
		add_action( 'admin_footer', array( $this, 'support_section' ) );
		add_action( 'admin_menu', [ $this, 'settings_menu' ] );
		add_action( 'admin_init', [ $this, 'save_settings' ] );
	}

	public static function instance() {
		return self::$instance == null ? self::$instance = new self() : self::$instance;
	}

	public function support_section() {
		if ( get_current_screen()->id === 'edit-emtmpl' ) {
			?>

            <div id="emtmpl-in-all-email-page">
				<?php do_action( 'villatheme_support_9mail-wordpress-email-templates-designer' ); ?>
            </div>
		<?php }
	}

	public function settings_menu() {
		add_submenu_page(
			apply_filters( 'emtmpl_setting_page_parent_slug', 'edit.php?post_type=emtmpl' ),
			esc_html__( 'Settings', '9mail-wordpress-email-templates-designer' ),
			esc_html__( 'Settings', '9mail-wordpress-email-templates-designer' ),
			'manage_options',
			'emtmpl_settings',
			[ $this, 'settings_page' ]
		);
	}

	public function settings_page() {
		$codecanyon_id = 38594489;
		$key           = get_option( 'emtmpl_auto_update_key' );

		?>
        <div id="emtmpl-settings-page">
            <form method="post" class="vi-ui form villatheme-support-auto-update-key">
				<?php wp_nonce_field( 'emtmpl_save_settings' ) ?>
                <h2>AUTO UPDATE PLUGIN</h2>
                <div class="vi-ui segment">
                    <div class="vi-ui action input fluid">
                        <input type="text" name="emtmpl_auto_update_key" id="auto-update-key" class="villatheme-autoupdate-key-field" value="<?php echo esc_attr( $key ) ?>">

                        <button type="button" class="vi-ui button green small villatheme-get-key-button"
                                data-href="https://api.envato.com/authorization?response_type=code&amp;client_id=villatheme-download-keys-6wzzaeue&amp;redirect_uri=https://villatheme.com/update-key"
                                data-id="<?php echo esc_attr( $codecanyon_id ) ?>">
                            Get Key
                        </button>
                    </div>

                    <p class="description">
                        Please fill your key what you get from <a target="_blank" href="https://villatheme.com/my-download">https://villatheme.com/my-download</a>.
                        You can automatically update this plugin. See <a target="_blank" href="https://villatheme.com/knowledge-base/how-to-use-auto-update-feature/">guide</a>
                    </p>
                </div>

                <button type="submit" class="vi-ui button labeled icon primary" name="emtmpl_save_settings" value="save_settings">
                    <i class="save icon"> </i>
                    Save Key
                </button>
                <button type="submit" class="vi-ui button labeled icon" name="emtmpl_save_settings" value="save_n_check_key">
                    <i class="send icon"> </i>
                    Save &amp; Check key
                </button>

            </form>
        </div>

		<?php
		do_action( 'villatheme_support_9mail-wordpress-email-templates-designer' );
	}

	public function save_settings() {
		if ( isset( $_POST['_wpnonce'], $_POST['emtmpl_save_settings'] )
		     && wp_verify_nonce( sanitize_key( $_POST['_wpnonce'] ), 'emtmpl_save_settings' )
		     && current_user_can( 'manage_options' ) ) {

			$key = $_POST['emtmpl_auto_update_key'] ? sanitize_text_field( $_POST['emtmpl_auto_update_key'] ) : '';

			update_option( 'emtmpl_auto_update_key', $key );

			if ( $_POST['emtmpl_save_settings'] === 'save_n_check_key' && $key ) {
				delete_site_transient( 'update_plugins' );
				delete_transient( 'villatheme_item_120635' );
				delete_option( '9mail-wordpress-email-templates-designer_messages' );
				do_action( 'villatheme_save_and_check_key_9mail-wordpress-email-templates-designer', $key );
			}

		}
	}
}

