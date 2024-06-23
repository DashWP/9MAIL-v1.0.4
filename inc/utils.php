<?php

namespace EmTmpl\Inc;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Utils {

	protected static $instance = null;

	private function __construct() {
	}

	public static function init() {
		if ( null == self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public static function get_admin_emails() {
		return apply_filters( 'emtmpl_admin_emails', [
			'wp_new_user_notification_email_admin'  => esc_html__( 'Notify user signup to admin', '9mail-wordpress-email-templates-designer' ),
			'wp_password_change_notification_email' => esc_html__( "Notify user's password change to admin", '9mail-wordpress-email-templates-designer' ),
			'new_admin_email_content'               => esc_html__( 'Change admin email', '9mail-wordpress-email-templates-designer' ),
			'comment_moderation_text'               => esc_html__( 'Notify moderator new comment', '9mail-wordpress-email-templates-designer' ),
			'user_request_confirmed_email_content'  => esc_html__( 'Confirmed personal data', '9mail-wordpress-email-templates-designer' ),
		] );
	}

	public static function get_user_emails() {
		$emails = [
			'wp_new_user_notification_email'         => esc_html__( 'Signup', '9mail-wordpress-email-templates-designer' ),
			'new_user_email_content'                 => esc_html__( 'Email change request', '9mail-wordpress-email-templates-designer' ),
			'password_change_email'                  => esc_html__( 'Change password', '9mail-wordpress-email-templates-designer' ),
			'retrieve_password_message'              => esc_html__( 'Reset password', '9mail-wordpress-email-templates-designer' ),
			'email_change_email'                     => esc_html__( 'Changed email notification', '9mail-wordpress-email-templates-designer' ),
			'comment_notification_text'              => esc_html__( 'Comment notification', '9mail-wordpress-email-templates-designer' ),
			'user_request_action_email_content'      => esc_html__( 'Request personal data', '9mail-wordpress-email-templates-designer' ),
			'wp_privacy_personal_data_email_content' => esc_html__( 'Send personal data export', '9mail-wordpress-email-templates-designer' ),
		];

		if ( class_exists( 'WPForms' ) || class_exists('\WPForms\WPForms')) {
			$emails['wpforms_email_message'] = esc_html__( 'WPforms email message' );
		}

		return apply_filters( 'emtmpl_user_emails', $emails );
	}

	public static function get_common_emails() {
		return apply_filters( 'emtmpl_common_emails', [
			'default' => esc_html__( 'Default', '9mail-wordpress-email-templates-designer' ),
		] );
	}

	public static function get_email_ids() {
		return apply_filters( 'emtmpl_email_types', [] );
	}

	public static function common_shortcodes() {
		return  [
			'{admin_email}'  => get_bloginfo( 'admin_email' ),
			'{home_url}'     => home_url(),
			'{site_url}'     => site_url(),
			'{site_title}'   => get_bloginfo( 'name' ),
			'{current_year}' => date_i18n( 'Y', current_time( 'U' ) ),
		] ;
	}

	public static function email_type_shortcodes() {
		$hash = md5( 'johndoe@domain.com' . time() . wp_rand() );

		return [
			'wp_new_user_notification_email' => [
				'{user_name}'    => 'johndoe',
				'{password_url}' => wp_login_url() . '?action=rp&key=XGump6bHNV6bdJJ1C5eI&login=johndoe',
				'{login_url}'    => wp_login_url(),
			],

			'wp_password_change_notification_email' => [
				'{user_name}'  => 'johndoe',
				'{user_email}' => 'johndoe@domain.com',
			],

			'wp_new_user_notification_email_admin' => [
				'{user_name}'  => 'johndoe',
				'{user_email}' => 'johndoe@domain.com',
			],

			'password_change_email' => [
				'{user_name}'  => 'johndoe',
				'{user_email}' => 'johndoe@domain.com',
			],

			'retrieve_password_message' => [
				'{user_name}'          => 'johndoe',
				'{user_email}'         => 'johndoe@domain.com',
				'{reset_password_url}' => wp_login_url() . '?action=rp&key=XGump6bHNV6bdJJ1C5eI&login=johndoe',
				'{user_IP}'            => ! empty( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( $_SERVER['REMOTE_ADDR'] ) : '',
			],

			'new_admin_email_content' => [
				'{user_name}' => 'johndoe',
				'{new_email}' => 'johndoe@domain.com',
				'{admin_url}' => self_admin_url( 'options.php?adminhash=' . $hash ),
			],

			'new_user_email_content' => [
				'{user_name}'        => 'johndoe',
				'{new_email}'        => 'johndoe@domain.com',
				'{change_email_url}' => admin_url( 'profile.php?newuseremail=' . $hash ),
			],

			'email_change_email' => [
				'{user_name}'     => 'johndoe',
				'{new_email}'     => 'new_johndoe@domain.com',
				'{current_email}' => 'johndoe@domain.com',
			],

			'comment_moderation_text' => [
				'{post_title}'            => 'Hello world',
				'{post_url}'              => site_url( 'hello-world' ),
				'{comment_author}'        => 'JohnDoe',
				'{comment_author_IP}'     => '1.2.3.4',
				'{comment_author_domain}' => 'DESKTOP-R9T3T49',
				'{comment_author_email}'  => 'johndoe@domain.com',
				'{comment_author_url}'    => 'https://johndoe.com',
				'{comment_content}'       => 'Great content',
				'{approve_comment_url}'   => admin_url( "comment.php?action=approve&c=1#wpbody-content" ),
				'{trash_comment_url}'     => admin_url( "comment.php?action=trash&c=1#wpbody-content" ),
				'{spam_comment_url}'      => admin_url( "comment.php?action=spam&c=1#wpbody-content" ),
				'{delete_comment_url}'    => admin_url( "comment.php?action=delete&c=1#wpbody-content" ),
				'{comment_count}'         => 3,
				'{moderation_panel_url}'  => admin_url( 'edit-comments.php?comment_status=moderated#wpbody-content' ),
			],

			'comment_notification_text' => [
				'{post_title}'            => 'Hello world',
				'{post_url}'              => site_url( 'hello-world' ),
				'{comment_author}'        => 'JohnDoe',
				'{comment_author_IP}'     => '1.2.3.4',
				'{comment_author_domain}' => 'DESKTOP-R9T3T49',
				'{comment_author_email}'  => 'johndoe@domain.com',
				'{comment_author_url}'    => 'https://johndoe.com',
				'{comment_content}'       => 'Great content',
				'{comment_url}'           => site_url( 'hello-world' ) . '/#comment-1',
				'{trash_comment_url}'     => admin_url( "comment.php?action=trash&c=1#wpbody-content" ),
				'{spam_comment_url}'      => admin_url( "comment.php?action=spam&c=1#wpbody-content" ),
				'{delete_comment_url}'    => admin_url( "comment.php?action=delete&c=1#wpbody-content" ),
			],

			'user_request_action_email_content' => [
				'{request_type}' => 'Export personal data',
				'{confirm_url}'  => wp_login_url() . '?action=confirmaction&request_id=204&confirm_key=ClrMYDEAjGSQhDWxS5j0',
			],

			'user_request_confirmed_email_content' => [
				'{request_type}' => 'Export personal data',
				'{manage_url}'   => wp_login_url() . '?action=confirmaction&request_id=204&confirm_key=ClrMYDEAjGSQhDWxS5j0',
			],

			'wp_privacy_personal_data_email_content' => [
				'{expiration}'      => date_i18n( get_option( 'date_format' ), current_time( 'U' ) + 3 * DAY_IN_SECONDS ),
				'{export_file_url}' => wp_privacy_exports_url() . 'wp-personal-data-file-UvGXRJaazE4lyJEBH1NWWP3qwBb48h11.zip',
			],
		];
	}

	public static function shortcode_for_editor_replace() {
		$sc            = self::common_shortcodes();
		$email_type_sc = self::email_type_shortcodes();

		if ( ! empty( $email_type_sc ) ) {
			foreach ( $email_type_sc as $key => $items ) {
				$sc = array_merge( $sc, $items );
			}
		}

		return $sc;
	}

	public static function register_email_type() {
		$r                   = [];
		$register_email_type = self::register_3rd_email_type();
		if ( ! empty( $register_email_type ) && is_array( $register_email_type ) ) {
			foreach ( $register_email_type as $id => $data ) {
				if ( empty( $data['name'] ) ) {
					continue;
				}
				$r[ $id ] = $data['name'];
			}
		}

		return $r;
	}

	public static function get_accept_elements_data() {
		$basic_elements = apply_filters( 'emtmpl_register_element_for_all_email_type', [
			'layout/grid1cols',
			'layout/grid2cols',
			'layout/grid3cols',
			'layout/grid4cols',
			'html/text',
			'html/image',
			'html/button',
			'html/post',
			'html/contact',
			'html/menu',
			'html/social',
			'html/divider',
			'html/spacer',
		] );

		$emails = [
			'default' => [ 'html/recover_content' ],
		];

		foreach ( $emails as $type => $el ) {
			$emails[ $type ] = array_merge( $basic_elements, $el );
		}

		$register_email_type = self::register_3rd_email_type();
		if ( ! empty( $register_email_type ) && is_array( $register_email_type ) ) {
			foreach ( $register_email_type as $id => $data ) {
				$accept        = empty( $data['accept_elements'] ) ? [] : $data['accept_elements'];
				$emails[ $id ] = array_merge( $basic_elements, $accept );
			}
		}

		return $emails;
	}

	public static function get_hide_rules_data() {
		$r = [ 'default' => [ 'country' ] ];

		$register_email_type = self::register_3rd_email_type();
		if ( ! empty( $register_email_type ) && is_array( $register_email_type ) ) {
			foreach ( $register_email_type as $id => $data ) {
				if ( empty( $data['hide_rules'] ) ) {
					continue;
				}
				$r[ $id ] = $data['hide_rules'];
			}
		}

		return $r;
	}

	public static function register_3rd_email_type() {
		return apply_filters( 'emtmpl_register_email_type', [] );
	}

	public static function register_shortcode_for_builder() {
		return apply_filters( 'emtmpl_live_edit_shortcodes', [] );
	}

	public static function get_register_shortcode_for_builder() {
		$result = [];
		$scs    = self::register_shortcode_for_builder();

		if ( ! empty( $scs ) && is_array( $scs ) ) {
			foreach ( $scs as $key => $sc ) {
				if ( ! is_array( $sc ) ) {
					continue;
				}
				$result = array_merge( $result, array_keys( $sc ) );
			}
		}

		return $result;
	}

	public static function get_register_shortcode_for_text_editor() {
		$result = [];

		$email_types = self::register_email_type();
		$scs         = self::register_shortcode_for_builder();

		if ( ! empty( $email_types ) && is_array( $email_types ) ) {
			foreach ( $email_types as $key => $name ) {
				$sc = ! empty( $scs[ $key ] ) ? $scs[ $key ] : '';
				if ( ! $sc || ! is_array( $sc ) ) {
					continue;
				}
				$menu = [];
				foreach ( $sc as $text => $value ) {
					if ( ! $text ) {
						continue;
					}
					$menu[] = [ 'text' => $text, 'value' => $text ];
				}
				$result[ $key ] = [ 'text' => $name, 'menu' => $menu ];
			}
		}

		return $result;
	}

	public static function get_register_shortcode_for_replace() {
		$result = [];

		$scs = self::register_shortcode_for_builder();
		if ( ! empty( $scs ) && is_array( $scs ) ) {
			foreach ( $scs as $key => $sc ) {
				$result = array_merge( $result, $sc );
			}
		}

		return $result;
	}

	public static function get_admin_bar_stt() {
		return get_option( 'emtmpl_admin_bar_stt' );
	}

	public static function default_shortcode_for_replace() {
		return [
			'{admin_email}' => get_option( 'admin_email' ),
			'{site_title}'  => get_bloginfo( 'name' ),
			'{site_url}'    => site_url(),
			'{home_url}'    => home_url(),

			'{user_login}'    => '',
			'{user_password}' => '',
			'{current_year}'  => date_i18n( 'Y', current_time( 'U' ) ),
		];
	}

	public static function minify_html( $message ) {
		$replace = [
			'/\>[^\S ]+/s' => '>',     // strip whitespaces after tags, except space
			'/[^\S ]+\</s' => '<',     // strip whitespaces before tags, except space
			'/(\s)+/s'     => '\\1',         // shorten multiple whitespace sequences
		];

		return preg_replace( array_keys( $replace ), array_values( $replace ), $message );
	}

	public static function insert_block( $block, $title ) {
		$args    = [
			'post_title'  => $title,
			'post_status' => 'publish',
			'post_type'   => 'emtmpl_block',
		];
		$post_id = wp_insert_post( $args );
		$block   = str_replace( '\\', '\\\\', $block );
		update_post_meta( $post_id, 'emtmpl_email_structure', $block );

		return $post_id;
	}

	public static function parse_block( $arg ) {
		$arg = json_decode( $arg, true );

		return isset( $arg['rows'][0] ) ? wp_json_encode( $arg['rows'][0] ) : '';
	}
}

