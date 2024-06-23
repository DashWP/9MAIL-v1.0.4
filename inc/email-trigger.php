<?php

namespace EmTmpl\Inc;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Email_Trigger {

	protected static $instance = null;
	protected $template_id;
	protected $object;
	protected $email_subject;
	protected $use_default_temp = false;
	protected $heading;
	protected $unique = [];
	protected $rendered = false;
	protected $ignore_key = '{{ignore_9mail}}';

	private function __construct() {
		add_filter( 'wp_new_user_notification_email', [ $this, 'user_signup' ], 10, 2 );
		add_filter( 'wp_new_user_notification_email_admin', [ $this, 'user_signup_to_admin' ], 10, 2 );
		add_filter( 'password_change_email', [ $this, 'user_change_password' ], 10, 3 );
		add_filter( 'retrieve_password_message', [ $this, 'retrieve_password_message' ], 10, 4 );
		add_filter( 'new_admin_email_content', [ $this, 'new_admin_email_content' ], 10, 2 );
		add_filter( 'new_user_email_content', [ $this, 'new_user_email_content' ], 10, 2 );
		add_filter( 'wp_password_change_notification_email', [ $this, 'user_password_change_notification_to_admin' ], 10, 2 );
		add_filter( 'email_change_email', [ $this, 'user_confirm_change_email' ], 10, 3 );
		add_filter( 'comment_moderation_text', [ $this, 'admin_comment_moderation' ], 10, 2 );
		add_filter( 'comment_notification_text', [ $this, 'post_author_comment_notification' ], 10, 2 );
		add_filter( 'user_request_action_email_content', [ $this, 'user_request_action_email_content' ], 10, 2 );
		add_filter( 'user_request_confirmed_email_content', [ $this, 'user_request_confirmed_email_content' ], 10, 2 );
		add_filter( 'wp_privacy_personal_data_email_content', [ $this, 'send_personal_data_email_content' ], 10, 3 );
		add_filter( 'wpforms_email_message', [ $this, 'wpforms_email_message' ], 10, 2 );
		add_filter( 'wp_mail', [ $this, 'use_default_email' ], 100 );

		add_action( 'woocommerce_email_footer', array( $this, 'ignore_9mail_for_woocommerce' ) );
	}

	public static function instance() {
		if ( null == self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	private function check_ignore( $message ) {
		return strpos( $message, $this->ignore_key ) !== false;
	}

	public function find_template( $type ) {
		$posts = get_posts( [
			'posts_per_page' => - 1,
			'post_type'      => 'emtmpl',
			'post_status'    => 'publish',
			'meta_key'       => 'emtmpl_settings_type',
			'meta_value'     => $type,
		] );

		$lang = function_exists( 'icl_get_current_language' ) ? icl_get_current_language() : '';

		if ( empty( $posts ) ) {
			return false;
		}

		if ( $lang ) {
			foreach ( $posts as $i => $post ) {
				$pid   = $post->ID;
				$langs = get_post_meta( $pid, 'emtmpl_setting_languages', true );

				if ( ! empty( $langs ) && is_array( $langs ) ) {
					if ( in_array( $lang, $langs ) ) {
						return $post;
					} else {
						unset( $posts[ $i ] );
					}
				}
			}
		}

		$template          = current( $posts );
		$this->template_id = $template->ID;

		return $template;
	}

	private function render_email_html( $template_id, $old_content = '' ) {
		$post = get_post( $template_id );
		if ( ! $post ) {
			return $old_content;
		}

		$this->email_subject = get_post( $template_id )->post_title;

		$email_render = new Email_Render( [ 'template_id' => $template_id, 'content' => $old_content ] );

		ob_start();
		$email_render->render();
		$message = ob_get_clean();

		$custom_style = $email_render->custom_style();
		$message      = str_replace( '[custom_style]', $custom_style, $message );

		return $message;
	}

	//Replace content ========================
	public function user_signup( $args, $user ) {
		if ( $this->check_ignore( $args['message'] ) ) {
			return $args;
		}

		$template = $this->find_template( 'wp_new_user_notification_email' );

		if ( empty( $template ) ) {
			return $args;
		}

		$message = $this->render_email_html( $template->ID, $args['message'] );

		if ( $message ) {
			$this->rendered = true;
			$key            = get_password_reset_key( $user );

			$replace = [
				'{user_name}'    => $user->user_login,
				'{user_email}'   => $user->user_email,
				'{password_url}' => network_site_url( "wp-login.php?action=rp&key=$key&login=" . rawurlencode( $user->user_login ), 'login' ),
				'{login_url}'    => wp_login_url(),
			];

			$message         = str_replace( array_keys( $replace ), array_values( $replace ), $message );
			$args['message'] = $message;
		}

		return $args;
	}

	public function user_signup_to_admin( $args, $user ) {
		if ( $this->check_ignore( $args['message'] ) ) {
			return $args;
		}

		$template = $this->find_template( 'wp_new_user_notification_email_admin' );

		if ( empty( $template ) ) {
			return $args;
		}


		$message = $this->render_email_html( $template->ID, $args['message'] );

		if ( $message ) {
			$this->rendered = true;

			$replace = [
				'{user_name}'  => $user->user_login,
				'{user_email}' => $user->user_email,
			];

			$message         = str_replace( array_keys( $replace ), array_values( $replace ), $message );
			$args['message'] = $message;
		}

		return $args;
	}

	public function user_change_password( $args, $user, $userdata ) {
		if ( $this->check_ignore( $args['message'] ) ) {
			return $args;
		}

		$template = $this->find_template( 'password_change_email' );

		if ( empty( $template ) ) {
			return $args;
		}


		$message = $this->render_email_html( $template->ID, $args['message'] );

		if ( $message ) {
			$this->rendered = true;

			$replace = [
				'{user_name}'  => $user['user_login'],
				'{user_email}' => $user['user_email'],
			];

			$args['message'] = str_replace( array_keys( $replace ), array_values( $replace ), $message );
		}

		return $args;
	}

	public function retrieve_password_message( $message, $key, $user_login, $user_data ) {

		if ( $this->check_ignore( $message ) ) {
			return $message;
		}

		$template = $this->find_template( 'retrieve_password_message' );

		if ( empty( $template ) ) {
			return $message;
		}

		$message = $this->render_email_html( $template->ID, $message );

		if ( $message ) {
			$this->rendered = true;
			$locale         = get_user_locale( $user_data );

			$replace = [
				'{user_name}'          => $user_login,
				'{reset_password_url}' => network_site_url( "wp-login.php?action=rp&key={$key}&login=" . rawurlencode( $user_login ), 'login' ) . '&wp_lang=' . $locale,
				'{user_IP}'            => ! empty( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( $_SERVER['REMOTE_ADDR'] ) : '',
			];

			$message = str_replace( array_keys( $replace ), array_values( $replace ), $message );
		}

		return apply_filters( 'emtmpl_retrieve_password_message', $message );
	}

	public function new_admin_email_content( $email_text, $new_admin_email ) {
		if ( $this->check_ignore( $email_text ) ) {
			return $email_text;
		}

		$template = $this->find_template( 'new_admin_email_content' );

		if ( empty( $template ) ) {
			return $email_text;
		}


		$message = $this->render_email_html( $template->ID, $email_text );

		if ( $message ) {
			$this->rendered = true;
			$current_user   = wp_get_current_user();
			$hash           = $new_admin_email['hash'];

			$replace = [
				'{user_name}' => $current_user->user_login,
				'{admin_url}' => esc_url( self_admin_url( 'options.php?adminhash=' . $hash ) ),
				'{new_email}' => $new_admin_email['newemail'],
			];

			$message = str_replace( array_keys( $replace ), array_values( $replace ), $message );
		}

		return $message;
	}

	public function new_user_email_content( $email_text, $new_admin_email ) {
		if ( $this->check_ignore( $email_text ) ) {
			return $email_text;
		}

		$template = $this->find_template( 'new_user_email_content' );

		if ( empty( $template ) ) {
			return $email_text;
		}

		$message = $this->render_email_html( $template->ID, $email_text );

		if ( $message ) {
			$this->rendered = true;
			$current_user   = wp_get_current_user();
			$hash           = $new_admin_email['hash'];

			$replace = [
				'{user_name}'        => $current_user->user_login,
				'{change_email_url}' => esc_url( admin_url( 'profile.php?newuseremail=' . $hash ) ),
				'{new_email}'        => $new_admin_email['newemail'],
			];

			$message = str_replace( array_keys( $replace ), array_values( $replace ), $message );
		}

		return $message;
	}

	public function user_password_change_notification_to_admin( $args, $user ) {
		if ( $this->check_ignore( $args['message'] ) ) {
			return $args;
		}

		$template = $this->find_template( 'wp_password_change_notification_email' );

		if ( empty( $template ) ) {
			return $args;
		}


		$message = $this->render_email_html( $template->ID, $args['message'] );

		if ( $message ) {
			$this->rendered = true;

			$replace = [
				'{user_name}' => $user->user_login,
			];

			$args['message'] = str_replace( array_keys( $replace ), array_values( $replace ), $message );
		}

		return $args;
	}

	public function user_confirm_change_email( $args, $user, $userdata ) {
		if ( $this->check_ignore( $args['message'] ) ) {
			return $args;
		}

		$template = $this->find_template( 'email_change_email' );

		if ( empty( $template ) ) {
			return $args;
		}


		$message = $this->render_email_html( $template->ID, $args['message'] );

		if ( $message ) {
			$this->rendered = true;

			$replace = [
				'{user_name}'     => $user['user_login'],
				'{current_email}' => $user['user_email'],
				'{new_email}'     => $userdata['user_email'],
			];

			$args['message'] = str_replace( array_keys( $replace ), array_values( $replace ), $message );
		}

		return $args;
	}

	public function admin_comment_moderation( $notify_message, $comment_id ) {
		if ( $this->check_ignore( $notify_message ) ) {
			return $notify_message;
		}

		$template = $this->find_template( 'comment_moderation_text' );

		if ( empty( $template ) ) {
			return $notify_message;
		}


		$message = $this->render_email_html( $template->ID, $notify_message );

		if ( $message ) {
			$this->rendered = true;
			$comment        = get_comment( $comment_id );
			$post           = get_post( $comment->comment_post_ID );

			$comment_author_domain = '';
			if ( \WP_Http::is_ip_address( $comment->comment_author_IP ) ) {
				$comment_author_domain = gethostbyaddr( $comment->comment_author_IP );
			}

			$replace = [
				'{post_title}'            => $post->post_title,
				'{post_url}'              => get_permalink( $comment->comment_post_ID ),
				'{comment_author}'        => $comment->comment_author,
				'{comment_author_IP}'     => $comment->comment_author_IP,
				'{comment_author_domain}' => $comment_author_domain,
				'{comment_author_email}'  => $comment->comment_author_email,
				'{comment_author_url}'    => $comment->comment_author_url,
				'{comment_content}'       => wp_specialchars_decode( $comment->comment_content ),
				'{approve_comment_url}'   => admin_url( "comment.php?action=approve&c={$comment_id}#wpbody-content" ),
				'{trash_comment_url}'     => admin_url( "comment.php?action=trash&c={$comment_id}#wpbody-content" ),
				'{spam_comment_url}'      => admin_url( "comment.php?action=spam&c={$comment_id}#wpbody-content" ),
				'{delete_comment_url}'    => admin_url( "comment.php?action=delete&c={$comment_id}#wpbody-content" ),
				'{moderation_panel_url}'  => admin_url( 'edit-comments.php?comment_status=moderated#wpbody-content' ),
			];

			$message = str_replace( array_keys( $replace ), array_values( $replace ), $message );
		}

		return $message;
	}

	public function post_author_comment_notification( $notify_message, $comment_id ) {
		if ( $this->check_ignore( $notify_message ) ) {
			return $notify_message;
		}

		$template = $this->find_template( 'comment_notification_text' );

		if ( empty( $template ) ) {
			return $notify_message;
		}


		$message = $this->render_email_html( $template->ID, $notify_message );

		if ( $message ) {
			$this->rendered = true;
			$comment        = get_comment( $comment_id );
			$post           = get_post( $comment->comment_post_ID );

			$comment_author_domain = '';
			if ( \WP_Http::is_ip_address( $comment->comment_author_IP ) ) {
				$comment_author_domain = gethostbyaddr( $comment->comment_author_IP );
			}

			$replace = [
				'{post_title}'            => $post->post_title,
				'{post_url}'              => get_permalink( $comment->comment_post_ID ),
				'{comment_author}'        => $comment->comment_author,
				'{comment_author_IP}'     => $comment->comment_author_IP,
				'{comment_author_domain}' => $comment_author_domain,
				'{comment_author_email}'  => $comment->comment_author_email,
				'{comment_author_url}'    => $comment->comment_author_url,
				'{comment_content}'       => wp_specialchars_decode( $comment->comment_content ),
				'{comment_url}'           => get_comment_link( $comment ),
				'{trash_comment_url}'     => admin_url( "comment.php?action=trash&c={$comment_id}#wpbody-content" ),
				'{spam_comment_url}'      => admin_url( "comment.php?action=spam&c={$comment_id}#wpbody-content" ),
				'{delete_comment_url}'    => admin_url( "comment.php?action=delete&c={$comment_id}#wpbody-content" ),
			];

			$message = str_replace( array_keys( $replace ), array_values( $replace ), $message );
		}

		return $message;
	}

	public function user_request_action_email_content( $content, $email_data ) {
		if ( $this->check_ignore( $content ) ) {
			return $content;
		}

		$template = $this->find_template( 'user_request_action_email_content' );

		if ( empty( $template ) ) {
			return $content;
		}


		$message = $this->render_email_html( $template->ID, $content );

		if ( $message ) {
			$this->rendered = true;

			$replace = [
				'{request_type}' => $email_data['description'],
				'{confirm_url}'  => esc_url_raw( $email_data['confirm_url'] ),
			];

			$message = str_replace( array_keys( $replace ), array_values( $replace ), $message );
		}

		return $message;
	}

	public function user_request_confirmed_email_content( $content, $email_data ) {
		if ( $this->check_ignore( $content ) ) {
			return $content;
		}

		$template = $this->find_template( 'user_request_confirmed_email_content' );

		if ( empty( $template ) ) {
			return $content;
		}


		$message = $this->render_email_html( $template->ID, $content );

		if ( $message ) {
			$this->rendered = true;

			$replace = [
				'{request_type}' => $email_data['description'],
				'{user_email}'   => $email_data['user_email'],
				'{manage_url}'   => esc_url_raw( $email_data['manage_url'] ),
			];

			$message = str_replace( array_keys( $replace ), array_values( $replace ), $message );
		}

		return $message;
	}

	public function send_personal_data_email_content( $email_text, $request_id, $email_data ) {
		if ( $this->check_ignore( $email_text ) ) {
			return $email_text;
		}

		$template = $this->find_template( 'wp_privacy_personal_data_email_content' );

		if ( empty( $template ) ) {
			return $email_text;
		}

		$message = $this->render_email_html( $template->ID, $email_text );

		if ( $message ) {
			$this->rendered = true;

			$replace = [
				'{expiration}'      => $email_data['expiration_date'] ?? '',
				'{export_file_url}' => $email_data['export_file_url'] ?? '',
			];

			$message = str_replace( array_keys( $replace ), array_values( $replace ), $message );
		}

		return $message;
	}

	/**
	 * @param $message
	 * @param \WPForms_WP_Emails $wp_form
	 *
	 * @return mixed
	 */
	public function wpforms_email_message( $message, $wp_form ) {
		if ( $this->check_ignore( $message ) ) {
			return $message;
		}

		$template = null;
		$pattern  = '/{9mail_id=(\d+)}/';
		preg_match( $pattern, $message, $match );

		if ( ! empty( $match[1] ) && intval( $match[1] ) ) {
			$temp_id = intval( $match[1] );
			$temp    = get_post( $temp_id );
			if ( $temp->post_status == 'publish' ) {
				$template = $temp;
			}
		}

		if ( ! $template ) {
			$template = $this->find_template( 'wpforms_email_message' );
		}

		if ( empty( $template ) ) {
			return $message;
		}

		$message = $this->render_email_html( $template->ID, $message );

		if ( $message ) {
			$this->rendered = true;
			$message        = $wp_form->process_tag( $message );
			$message        = str_replace( '{wpform_fields}', '{all_fields}', $message );
			$message        = str_replace( '{all_fields}', $wp_form->wpforms_html_field_value( true ), $message );
		}

		return $message;
	}

	public function use_default_email( $args ) {
		$use_default_tmpl = false;

		if ( ! $this->rendered ) {
			if ( $this->check_ignore( $args['message'] ) ) {
				$args['message'] = str_replace( $this->ignore_key, '', $args['message'] );

				return $args;
			}

			$template_name = 'default';

			// START: Adding Support for dynamic template choosing from wp_mail via headers
			$headers = is_array( $args['headers'] ) ? $args['headers'] : explode( "\n", str_replace( "\r\n", "\n", $args['headers'] ) );

			foreach ( $headers as $header ) {
				if ( empty( $header ) ) {
					continue;
				}
				list( $name, $content ) = explode( ':', trim( $header ), 2 );
				if ( strtolower( trim( $name ) ) == 'template' && isset( $content ) ) {
					$template_name = strtolower( trim( $content ) );
					break;
				}
			}

			$template = $this->find_template( $template_name );

			if ( ! empty( $template ) ) {
				$this->template_id = $template->ID;
				$message           = $this->render_email_html( $template->ID, $args['message'] );

				if ( $message ) {
					$this->rendered = true;
//					$use_default_tmpl = true;
					$use_default_tmpl = $template_name == 'default';
					$args['message']  = $message;
				}
			}
		}

		if ( $this->rendered ) {
			$re              = apply_filters( 'emtmpl_common_shortcodes', Utils::common_shortcodes() );
			$args['message'] = str_replace( array_keys( $re ), array_values( $re ), $args['message'] );
			$args['message'] = Utils::minify_html( $args['message'] );

			if ( ! $use_default_tmpl ) {
				$args['subject'] = str_replace( array_keys( $re ), array_values( $re ), $this->email_subject );
			}

			add_filter( 'wp_mail_content_type', [ $this, 'replace_content_type' ] );
		}

		if ( $this->template_id ) {
			$files = get_post_meta( $this->template_id, 'emtmpl_attachments', true );
			if ( ! empty( $files ) && is_array( $files ) ) {
				foreach ( $files as $file_id ) {
					$args['attachments'][] = get_attached_file( $file_id );
				}
			}
		}

		$this->rendered = false; //Reset

		return $args;
	}

	//End replace content ========================

	public function replace_content_type() {
		return 'text/html';
	}

	public function show_image( $args ) {
		if ( $this->use_default_temp ) {
			$show_image         = get_post_meta( $this->use_default_temp, 'emtmpl_enable_img_for_default_template', true );
			$args['show_image'] = $show_image ? true : false;

			$size               = get_post_meta( $this->use_default_temp, 'emtmpl_img_size_for_default_template', true );
			$args['image_size'] = $size ? [ (int) $size, 300 ] : [ 80, 80 ];
		}

		return $args;
	}

	public function custom_css( $style ) {
		if ( $this->use_default_temp || $this->template_id ) {
			$id    = $this->template_id ? $this->template_id : $this->use_default_temp;
			$style .= get_post_meta( $id, 'emtmpl_custom_css', true );
		}

		return $style;
	}

	public function ignore_9mail_for_woocommerce() {
		echo esc_html( $this->ignore_key );
	}
}

